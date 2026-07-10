<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRegistration
{
    /**
     * Fortify doesn't expose a way to attach a rate limiter to just the
     * register route, and its routes load too late for route-level
     * middleware to be attached during boot. Throttle registration bursts
     * here instead, scoped to POST /register only.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('post') || ! $request->routeIs('register.store')) {
            return $next($request);
        }

        $key = 'register:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, __('Too many registration attempts. Please try again later.'));
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
