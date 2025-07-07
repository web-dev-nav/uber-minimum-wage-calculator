<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProvinceWageRate;
use Carbon\Carbon;

class ProvinceWageRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            // Federal (for reference)
            [
                'province_code' => 'FD',
                'province_name' => 'Federal',
                'minimum_wage' => 17.75,
                'digital_platform_wage' => null,
                'effective_date' => '2025-04-01',
                'is_active' => true
            ],
            // Current Ontario rate with Digital Platform Workers' Rights Act
            [
                'province_code' => 'ON',
                'province_name' => 'Ontario',
                'minimum_wage' => 17.20,
                'digital_platform_wage' => 17.20,
                'effective_date' => '2024-10-01',
                'is_active' => true
            ],
            // Forthcoming Ontario rate
            [
                'province_code' => 'ON',
                'province_name' => 'Ontario',
                'minimum_wage' => 17.60,
                'digital_platform_wage' => 17.60,
                'effective_date' => '2025-10-01',
                'is_active' => false
            ],
            // Current BC rate
            [
                'province_code' => 'BC',
                'province_name' => 'British Columbia',
                'minimum_wage' => 17.85,
                'digital_platform_wage' => null,
                'effective_date' => '2025-06-01',
                'is_active' => true
            ],
            [
                'province_code' => 'AB',
                'province_name' => 'Alberta',
                'minimum_wage' => 15.00,
                'digital_platform_wage' => null,
                'effective_date' => '2019-06-26',
                'is_active' => true
            ],
            // Current MB rate
            [
                'province_code' => 'MB',
                'province_name' => 'Manitoba',
                'minimum_wage' => 15.80,
                'digital_platform_wage' => null,
                'effective_date' => '2024-10-01',
                'is_active' => true
            ],
            // Forthcoming MB rate
            [
                'province_code' => 'MB',
                'province_name' => 'Manitoba',
                'minimum_wage' => 16.00,
                'digital_platform_wage' => null,
                'effective_date' => '2025-10-01',
                'is_active' => false
            ],
            [
                'province_code' => 'NB',
                'province_name' => 'New Brunswick',
                'minimum_wage' => 15.65,
                'digital_platform_wage' => null,
                'effective_date' => '2025-04-01',
                'is_active' => true
            ],
            [
                'province_code' => 'NL',
                'province_name' => 'Newfoundland and Labrador',
                'minimum_wage' => 16.00,
                'digital_platform_wage' => null,
                'effective_date' => '2025-04-01',
                'is_active' => true
            ],
            [
                'province_code' => 'NT',
                'province_name' => 'Northwest Territories',
                'minimum_wage' => 16.70,
                'digital_platform_wage' => null,
                'effective_date' => '2024-09-01',
                'is_active' => true
            ],
            // Current NS rate
            [
                'province_code' => 'NS',
                'province_name' => 'Nova Scotia',
                'minimum_wage' => 15.70,
                'digital_platform_wage' => null,
                'effective_date' => '2025-04-01',
                'is_active' => true
            ],
            // Forthcoming NS rate
            [
                'province_code' => 'NS',
                'province_name' => 'Nova Scotia',
                'minimum_wage' => 16.50,
                'digital_platform_wage' => null,
                'effective_date' => '2025-10-01',
                'is_active' => false
            ],
            [
                'province_code' => 'NU',
                'province_name' => 'Nunavut',
                'minimum_wage' => 19.00,
                'digital_platform_wage' => null,
                'effective_date' => '2024-01-01',
                'is_active' => true
            ],
            // Current PE rate
            [
                'province_code' => 'PE',
                'province_name' => 'Prince Edward Island',
                'minimum_wage' => 16.00,
                'digital_platform_wage' => null,
                'effective_date' => '2024-10-01',
                'is_active' => true
            ],
            // Forthcoming PE rate
            [
                'province_code' => 'PE',
                'province_name' => 'Prince Edward Island',
                'minimum_wage' => 16.50,
                'digital_platform_wage' => null,
                'effective_date' => '2025-10-01',
                'is_active' => false
            ],
            [
                'province_code' => 'QC',
                'province_name' => 'Quebec',
                'minimum_wage' => 16.10,
                'digital_platform_wage' => null,
                'effective_date' => '2025-05-01',
                'is_active' => true
            ],
            [
                'province_code' => 'SK',
                'province_name' => 'Saskatchewan',
                'minimum_wage' => 15.00,
                'digital_platform_wage' => null,
                'effective_date' => '2024-10-01',
                'is_active' => true
            ],
            [
                'province_code' => 'YT',
                'province_name' => 'Yukon',
                'minimum_wage' => 17.94,
                'digital_platform_wage' => null,
                'effective_date' => '2025-04-01',
                'is_active' => true
            ]
        ];

        foreach ($provinces as $province) {
            ProvinceWageRate::updateOrCreate(
                [
                    'province_code' => $province['province_code'],
                    'effective_date' => $province['effective_date']
                ],
                $province
            );
        }
    }
}
