<?php

namespace App\Services;

use App\Models\ProvinceWageRate;
use Carbon\Carbon;

class WageCalculatorService
{
    public function calculateWage(string $provinceCode, float $activeHours, float $netFare = 0, float $tips = 0, ?Carbon $date = null): array
    {
        $date = $date ?? now();
        
        $provinceWageRate = ProvinceWageRate::where('province_code', strtoupper($provinceCode))
            ->where('is_active', true)
            ->where('effective_date', '<=', $date)
            ->orderBy('effective_date', 'desc')
            ->first();

        if (!$provinceWageRate) {
            throw new \Exception("Province wage rate not found for: {$provinceCode}");
        }

        $applicableWage = $this->getApplicableWage($provinceWageRate, $provinceCode);
        $minimumWageTotal = $activeHours * $applicableWage;
        $hourTotal = $minimumWageTotal - $netFare;
        $totalEarnings = $hourTotal + $netFare + $tips;

        $hours = floor($activeHours);
        $minutes = round(($activeHours - $hours) * 60);
        $formattedTime = sprintf('%d:%02d', $hours, $minutes);

        return [
            'province_code' => $provinceCode,
            'province_name' => $provinceWageRate->province_name,
            'active_hours' => $activeHours,
            'active_time_formatted' => $formattedTime,
            'hourly_rate' => $applicableWage,
            'minimum_wage_total' => round($minimumWageTotal, 2),
            'hour_total' => round($hourTotal, 2),
            'net_fare' => round($netFare, 2),
            'tips' => round($tips, 2),
            'total_earnings' => round($totalEarnings, 2),
            'calculation_date' => $date->toDateString(),
            'wage_type' => $this->getWageType($provinceWageRate, $provinceCode)
        ];
    }

    private function getApplicableWage(ProvinceWageRate $provinceWageRate, string $provinceCode): float
    {
        if (strtoupper($provinceCode) === 'ON' && $provinceWageRate->digital_platform_wage) {
            return $provinceWageRate->digital_platform_wage;
        }

        return $provinceWageRate->minimum_wage;
    }

    private function getWageType(ProvinceWageRate $provinceWageRate, string $provinceCode): string
    {
        if (strtoupper($provinceCode) === 'ON' && $provinceWageRate->digital_platform_wage) {
            return 'Digital Platform Workers Rights Act';
        }

        return 'Provincial Minimum Wage';
    }

    public function getAvailableProvinces(): array
    {
        return ProvinceWageRate::where('is_active', true)
            ->orderBy('province_name')
            ->get(['province_code', 'province_name', 'minimum_wage', 'digital_platform_wage'])
            ->toArray();
    }
}