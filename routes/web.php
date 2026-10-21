<?php

use App\Http\Controllers\VehicleCheckController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::get('/cek-kendaraan', [VehicleCheckController::class, 'showForm'])->name('vehicles.form');
Route::post('/cek-kendaraan', [VehicleCheckController::class, 'search'])->middleware('throttle:10,1')->name('vehicles.search');
