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
        if (Auth::check() && $user->needsProfileCompletion()) {
            // Allow access to profile completion routes
            if ($request->routeIs('profile.complete') ||
                $request->routeIs('logout') ||
                $request->routeIs('invitation.*')) {
                return $next($request);
            }

            return redirect()->route('profile.complete')
                ->with('info', 'Por favor completa tu perfil para continuar.');
        }

        return $next($request);
    }
}
