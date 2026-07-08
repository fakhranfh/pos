<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpGeolocationService
{
    /**
     * Resolve the IANA timezone identifier for the given IP address.
     *
     * When the IP is private/loopback (e.g. local development behind no proxy),
     * it cannot be geolocated, so we fall back to letting ip-api.com resolve the
     * timezone from the server's own outbound public IP instead.
     */
    public function resolveTimezone(?string $ip): ?string
    {
        $isPublicIp = $ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        $target = $isPublicIp ? $ip : '';

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$target}", [
                'fields' => 'status,timezone',
            ]);

            if (! $response->successful() || $response->json('status') !== 'success') {
                return null;
            }

            return $response->json('timezone');
        } catch (\Throwable $e) {
            Log::warning('Failed to resolve timezone from IP address.', [
                'ip' => $ip,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
