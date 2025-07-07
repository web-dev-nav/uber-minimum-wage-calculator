<?php

namespace App\Services;

class TimeCalculatorService
{
    public function convertHoursMinutesToDecimal(string $hoursMinutes): float
    {
        // Parse formats like "8:30", "8:00", "10:15"
        if (strpos($hoursMinutes, ':') !== false) {
            [$hours, $minutes] = explode(':', $hoursMinutes);
            return (float) $hours + ((float) $minutes / 60);
        }
        
        // If no colon, assume it's just hours
        return (float) $hoursMinutes;
    }

    public function convertDecimalToHoursMinutes(float $decimalHours): string
    {
        $hours = floor($decimalHours);
        $minutes = round(($decimalHours - $hours) * 60);
        
        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function validateTimeFormat(string $time): bool
    {
        // Check if format is H:MM or HH:MM
        if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            [$hours, $minutes] = explode(':', $time);
            return $hours >= 0 && $hours <= 24 && $minutes >= 0 && $minutes <= 59;
        }
        
        // Check if it's just a number (hours only)
        return is_numeric($time) && $time >= 0 && $time <= 24;
    }
}