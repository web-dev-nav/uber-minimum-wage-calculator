<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WageCalculatorController;
use App\Http\Controllers\UberOAuthController;

Route::get('/', [WageCalculatorController::class, 'index'])->name('wage-calculator');
Route::post('/calculate', [WageCalculatorController::class, 'calculate'])->name('calculate-wage');
Route::get('/api/provinces', [WageCalculatorController::class, 'getProvinces'])->name('api.provinces');

// Uber OAuth routes
Route::get('/uber/authorize', [UberOAuthController::class, 'authorize'])->name('uber.authorize');
Route::get('/uber/callback', [UberOAuthController::class, 'callback'])->name('uber.callback');
Route::get('/uber/fetch-data', [UberOAuthController::class, 'fetchData'])->name('uber.fetch-data');
Route::post('/uber/disconnect', [UberOAuthController::class, 'disconnect'])->name('uber.disconnect');
