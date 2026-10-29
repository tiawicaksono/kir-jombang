<?php

use App\Http\Controllers\VehicleCheckController;
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'pages.home')->name('home');
// CEK KENDARAAN
Route::get('/cek-kendaraan', [VehicleCheckController::class, 'showForm'])->name('vehicles.form');
Route::post('/cek-kendaraan', [VehicleCheckController::class, 'search'])->middleware('throttle:10,1')->name('vehicles.search');
// PERSYARATAN
Route::view('/persyaratan-pengujian', 'pages.persyaratan')->name('requirements');
// JADWAL PELAYANAN
Route::view('/jadwal-pelayanan', 'pages.jadwal-pelayanan')->name('service.schedule');
// BERITA
Route::get('/berita-pengumuman', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{id}', [BeritaController::class, 'show'])->whereNumber('id')->name('berita.show');
