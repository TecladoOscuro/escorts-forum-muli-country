<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        $tenant = Tenant::where('domain', $host)
            ->orWhere('domain', 'localhost')
            ->where('is_active', true)
            ->first();

        if (!$tenant) {
            $tenant = Tenant::where('is_active', true)->first();
        }

        if (!$tenant) {
            abort(503, 'No active tenant found.');
        }

        app()->instance('currentTenant', $tenant);
        app()->setLocale($tenant->locale);
        config(['app.timezone' => $tenant->timezone]);

        view()->share('currentTenant', $tenant);

        return $next($request);
    }
}
