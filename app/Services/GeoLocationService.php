<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeoLocationService
{
    public function getProvinceByIP(string $ip = null): ?string
    {
        if (!$ip || $ip === '127.0.0.1' || $ip === '::1') {
            return 'ON'; // Default to Ontario for local development
        }

        try {
            $cacheKey = 'province_ip_' . md5($ip);
            
            return Cache::remember($cacheKey, 3600, function() use ($ip) {
                // Using free IP geolocation service
                $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data['status'] === 'success' && $data['country'] === 'Canada') {
                        return $this->mapRegionToProvinceCode($data['regionName']);
                    }
                }
                
                return 'ON'; // Default to Ontario
            });
        } catch (\Exception $e) {
            return 'ON'; // Default to Ontario on error
        }
    }

    private function mapRegionToProvinceCode(string $region): string
    {
        $mapping = [
            'Ontario' => 'ON',
            'Quebec' => 'QC',
            'British Columbia' => 'BC',
            'Alberta' => 'AB',
            'Saskatchewan' => 'SK',
            'Manitoba' => 'MB',
            'New Brunswick' => 'NB',
            'Nova Scotia' => 'NS',
            'Prince Edward Island' => 'PE',
            'Newfoundland and Labrador' => 'NL',
            'Northwest Territories' => 'NT',
            'Nunavut' => 'NU',
            'Yukon' => 'YT'
        ];

        return $mapping[$region] ?? 'ON';
    }
}