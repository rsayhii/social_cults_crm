<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCompanyAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $company = $user->company;

        // No company → logout
        if (!$company) {
            Auth::logout();
            return redirect()->route('login');
        }

        // 🔒 TRIAL EXPIRED & NOT PAID
        if (
            !$company->is_paid &&
            now()->greaterThan($company->trial_ends_at)
        ) {

            // Allow ONLY upgrade & auth routes
            if (
                $request->routeIs('upgrade.index') ||
                $request->routeIs('logout') ||
                $request->routeIs('login')
            ) {
                return $next($request);
            }

            // Everything else BLOCKED
            return redirect()->route('upgrade.index');
        }

        return $next($request);
    }
}
