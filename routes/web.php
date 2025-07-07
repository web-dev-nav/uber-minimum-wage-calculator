<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WageCalculatorController;

Route::get('/', [WageCalculatorController::class, 'index'])->name('wage-calculator');
Route::post('/calculate', [WageCalculatorController::class, 'calculate'])->name('calculate-wage');
Route::get('/api/provinces', [WageCalculatorController::class, 'getProvinces'])->name('api.provinces');
