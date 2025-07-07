<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WageCalculatorService;
use App\Services\GeoLocationService;
use App\Services\TimeCalculatorService;

class WageCalculatorController extends Controller
{
    protected $wageCalculatorService;
    protected $geoLocationService;
    protected $timeCalculatorService;

    public function __construct(
        WageCalculatorService $wageCalculatorService,
        GeoLocationService $geoLocationService,
        TimeCalculatorService $timeCalculatorService
    ) {
        $this->wageCalculatorService = $wageCalculatorService;
        $this->geoLocationService = $geoLocationService;
        $this->timeCalculatorService = $timeCalculatorService;
    }

    public function index(Request $request)
    {
        $provinces = $this->wageCalculatorService->getAvailableProvinces();
        $clientIp = $request->ip();
        $detectedProvince = $this->geoLocationService->getProvinceByIP($clientIp);
        
        return view('wage-calculator', compact('provinces', 'detectedProvince'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'province_code' => 'required|string|size:2',
            'active_time' => 'required|string',
            'net_fare' => 'nullable|numeric|min:0',
            'tips' => 'nullable|numeric|min:0'
        ]);

        try {
            // Validate and convert time format
            if (!$this->timeCalculatorService->validateTimeFormat($request->active_time)) {
                throw new \Exception('Invalid time format. Please use H:MM format (e.g., 8:30)');
            }

            $activeHours = $this->timeCalculatorService->convertHoursMinutesToDecimal($request->active_time);
            $netFare = $request->net_fare ?? 0;
            $tips = $request->tips ?? 0;

            $result = $this->wageCalculatorService->calculateWage(
                $request->province_code,
                $activeHours,
                $netFare,
                $tips
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getProvinces()
    {
        $provinces = $this->wageCalculatorService->getAvailableProvinces();
        return response()->json($provinces);
    }
}
