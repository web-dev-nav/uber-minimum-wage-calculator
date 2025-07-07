<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UberApiService;
use Carbon\Carbon;

class UberOAuthController extends Controller
{
    protected $uberApiService;

    public function __construct(UberApiService $uberApiService)
    {
        $this->uberApiService = $uberApiService;
    }

    public function authorize(Request $request)
    {
        $redirectUri = url('/uber/callback');
        $authUrl = $this->uberApiService->getAuthorizationUrl($redirectUri);
        
        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect('/')->with('error', 'Authorization failed');
        }

        try {
            $redirectUri = url('/uber/callback');
            $tokenData = $this->uberApiService->exchangeCodeForToken($code, $redirectUri);
            
            // Store token in session for demo purposes
            session(['uber_access_token' => $tokenData['access_token']]);
            
            return redirect('/')->with('success', 'Connected to Uber successfully!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to connect to Uber: ' . $e->getMessage());
        }
    }

    public function fetchData(Request $request)
    {
        $accessToken = session('uber_access_token');
        
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to Uber. Please authorize first.'
            ], 401);
        }

        try {
            $endDate = Carbon::now()->format('Y-m-d');
            $startDate = Carbon::now()->subDays(7)->format('Y-m-d'); // Last 7 days
            
            // Fetch trips and earnings
            $trips = $this->uberApiService->getDriverTrips($accessToken, $startDate, $endDate);
            $earnings = $this->uberApiService->getDriverEarnings($accessToken, $startDate, $endDate);
            
            // Calculate data
            $activeTime = $this->uberApiService->calculateActiveTimeFromTrips($trips);
            $netFare = $this->uberApiService->calculateNetFareFromEarnings($earnings);
            $tips = $this->uberApiService->calculateTipsFromEarnings($earnings);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'active_time' => $activeTime,
                    'net_fare' => $netFare,
                    'tips' => $tips,
                    'period' => "{$startDate} to {$endDate}"
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Uber data: ' . $e->getMessage()
            ], 400);
        }
    }

    public function disconnect()
    {
        session()->forget('uber_access_token');
        return redirect('/')->with('success', 'Disconnected from Uber');
    }
}
