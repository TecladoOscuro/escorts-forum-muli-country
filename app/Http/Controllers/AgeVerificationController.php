<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgeVerificationController extends Controller
{
    public function show()
    {
        if (request()->cookie('age_verified')) {
            return redirect()->route('home');
        }

        return view('age-verification');
    }

    public function verify(Request $request)
    {
        $request->validate(['confirm' => 'accepted']);

        return redirect()->route('home')
            ->withCookie(cookie('age_verified', true, 60 * 24 * 30)); // 30 days
    }
}
