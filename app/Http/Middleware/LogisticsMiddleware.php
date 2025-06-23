<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogisticsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!Auth::check() || (!$user->hasRole('logistics') && !$user->hasRole('admin'))) {
            abort(403, 'Access denied. Logistics privileges required.');
        }

        return $next($request);
    }
}
