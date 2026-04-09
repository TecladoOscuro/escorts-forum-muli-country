<?php

namespace App\Http\Controllers;

use App\Models\TokenPackage;
use App\Models\TokenTransaction;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index()
    {
        $packages = TokenPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $transactions = auth()->user()
            ->tokenTransactions()
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

        return view('tokens.index', compact('packages', 'transactions'));
    }

    public function purchase(Request $request, TokenPackage $tokenPackage)
    {
        // For now, mock purchase — in production, redirect to Verotel/CCBill
        $user = auth()->user();
        $user->increment('token_balance', $tokenPackage->tokens);

        TokenTransaction::create([
            'tenant_id' => app('currentTenant')->id,
            'user_id' => $user->id,
            'type' => 'purchase',
            'amount' => $tokenPackage->tokens,
            'balance_after' => $user->fresh()->token_balance,
            'description' => "Kauf: {$tokenPackage->name} ({$tokenPackage->tokens} Tokens)",
        ]);

        return redirect()->route('tokens.index')
            ->with('success', "{$tokenPackage->tokens} Tokens erfolgreich gekauft!");
    }

    public function spend(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:blog_renewal,featured,top,sponsor',
            'amount' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $amount = (int) $validated['amount'];

        if ($user->token_balance < $amount) {
            return back()->with('error', 'Nicht genügend Tokens!');
        }

        $user->decrement('token_balance', $amount);

        $descriptions = [
            'blog_renewal' => 'Blog-Verlängerung (30 Tage)',
            'featured' => 'Featured-Platzierung (7 Tage)',
            'top' => 'Top-Platzierung (24 Stunden)',
            'sponsor' => 'Sponsoring auf Startseite (24 Stunden)',
        ];

        TokenTransaction::create([
            'tenant_id' => app('currentTenant')->id,
            'user_id' => $user->id,
            'type' => 'spend',
            'amount' => -$amount,
            'balance_after' => $user->fresh()->token_balance,
            'description' => $descriptions[$validated['action']],
            'reference_type' => $validated['action'],
        ]);

        // Apply the effect to escort profile
        $profile = $user->escortProfile;
        if ($profile) {
            match ($validated['action']) {
                'blog_renewal' => $profile->update(['blog_visible_until' => now()->addDays(30)]),
                'featured' => $profile->update(['featured_until' => now()->addDays(7)]),
                'top' => $profile->update(['top_until' => now()->addHours(24)]),
                'sponsor' => null, // handled separately
            };
        }

        return back()->with('success', 'Tokens erfolgreich ausgegeben!');
    }
}
