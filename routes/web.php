<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\PondController;
use App\Http\Controllers\WaterQualityStandardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

Route::prefix('admin')->name('admin.')->group(function () {
    // Halaman non-CRUD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis');

    // CRUD
    Route::resource('ponds', PondController::class);
    Route::post('/ponds/{pond}/fishes', [PondController::class, 'storeFish'])->name('ponds.fishes.store');
    Route::delete('/ponds/{pond}/fishes/{fish}', [PondController::class, 'destroyFish'])->name('ponds.fishes.destroy');

    Route::resource('fishes', FishController::class);
    Route::resource('fishes.standards', WaterQualityStandardController::class);

    Route::resource('measurements', MeasurementController::class);
});
