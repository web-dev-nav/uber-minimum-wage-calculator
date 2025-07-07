<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class UberApiService
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $authUrl;

    public function __construct()
    {
        $this->clientId = config('services.uber.client_id');
        $this->clientSecret = config('services.uber.client_secret');
        
        // Use sandbox URLs for test environment
        $isSandbox = config('services.uber.sandbox', true);
        
        if ($isSandbox) {
            $this->baseUrl = 'https://sandbox-api.uber.com/v1.2';
            $this->authUrl = 'https://login.uber.com/oauth/v2';
        } else {
            $this->baseUrl = 'https://api.uber.com/v1.2';
            $this->authUrl = 'https://login.uber.com/oauth/v2';
        }
    }

    public function getAuthorizationUrl(string $redirectUri): string
    {
        $params = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
            'scope' => 'partner.trips profile',
        ]);

        return "{$this->authUrl}/authorize?{$params}";
    }

    public function exchangeCodeForToken(string $code, string $redirectUri): array
    {
        $response = Http::post("{$this->authUrl}/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to exchange code for token: ' . $response->body());
    }

    public function getDriverTrips(string $accessToken, string $startDate, string $endDate): array
    {
        $response = Http::withToken($accessToken)
            ->get("{$this->baseUrl}/partners/trips", [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'limit' => 50
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch trips: ' . $response->body());
    }

    public function getDriverEarnings(string $accessToken, string $startDate, string $endDate): array
    {
        $response = Http::withToken($accessToken)
            ->get("{$this->baseUrl}/partners/payments", [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch earnings: ' . $response->body());
    }

    public function calculateActiveTimeFromTrips(array $trips): float
    {
        $totalActiveTime = 0;

        foreach ($trips['trips'] ?? [] as $trip) {
            if (isset($trip['start_time']) && isset($trip['end_time'])) {
                $startTime = strtotime($trip['start_time']);
                $endTime = strtotime($trip['end_time']);
                $tripDuration = ($endTime - $startTime) / 3600; // Convert to hours
                $totalActiveTime += $tripDuration;
            }
        }

        return round($totalActiveTime, 2);
    }

    public function calculateNetFareFromEarnings(array $earnings): float
    {
        $totalNetFare = 0;

        foreach ($earnings['payments'] ?? [] as $payment) {
            if (isset($payment['fare_amount'])) {
                $totalNetFare += $payment['fare_amount'];
            }
        }

        return round($totalNetFare, 2);
    }

    public function calculateTipsFromEarnings(array $earnings): float
    {
        $totalTips = 0;

        foreach ($earnings['payments'] ?? [] as $payment) {
            if (isset($payment['tips_amount'])) {
                $totalTips += $payment['tips_amount'];
            }
        }

        return round($totalTips, 2);
    }
}