<?php

use App\Http\Controllers\Api\VehicleLookupController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/kendaraan/cek', [VehicleLookupController::class, 'check'])
        ->middleware('throttle:10,1');
});
