<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvinceWageRate extends Model
{
    protected $fillable = [
        'province_code',
        'province_name',
        'minimum_wage',
        'digital_platform_wage',
        'effective_date',
        'is_active'
    ];

    protected $casts = [
        'minimum_wage' => 'decimal:2',
        'digital_platform_wage' => 'decimal:2',
        'effective_date' => 'date',
        'is_active' => 'boolean'
    ];
}
