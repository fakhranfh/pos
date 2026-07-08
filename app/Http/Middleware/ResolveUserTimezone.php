<?php

namespace App\Http\Middleware;

use App\Services\IpGeolocationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveUserTimezone
{
    public function __construct(private IpGeolocationService $ipGeolocationService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->timezone) {
            $timezone = $this->ipGeolocationService->resolveTimezone($request->ip());

            if ($timezone) {
                $user->update(['timezone' => $timezone]);
            }
        }

        return $next($request);
    }
}
