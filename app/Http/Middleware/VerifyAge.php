<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAge
{
    protected array $excluded = [
        'age-verification',
        'age-verify',
        'impressum',
        'privacy',
        'terms',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->excluded as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        if (!$request->cookie('age_verified')) {
            return redirect()->route('age-verification');
        }

        return $next($request);
    }
}
