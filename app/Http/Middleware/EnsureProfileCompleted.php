<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Skip check for profile completion routes
        if ($request->routeIs('profile.complete') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if user needs to complete profile
        if ($user && $user->needsProfileCompletion()) {
            return redirect()->route('profile.complete');
        }

        return $next($request);
    }
}
