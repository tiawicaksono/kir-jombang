<?php

use App\Http\Controllers\Api\V1\KendaraanController;
use App\Http\Controllers\Api\V1\MobileContentController;
use App\Http\Controllers\Api\V1\TotalUjiHariIniController;
use App\Http\Controllers\Api\VehicleLookupController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/kendaraan/cek', [VehicleLookupController::class, 'check'])
        ->middleware('throttle:10,1');

    Route::get(
        '/kendaraan/detail',
        [KendaraanController::class, 'detail']
    )->name('api.v1.kendaraan.detail');

    Route::get(
        '/kendaraan/hasil-uji',
        [KendaraanController::class, 'hasilUji']
    )->name('api.v1.kendaraan.hasil-uji');

    Route::get('/kendaraan/keterangan-tidak-lulus', [
        KendaraanController::class,
        'keteranganTidakLulus',
    ])->name('api.v1.kendaraan.keterangan-tidak-lulus');

    Route::get('/kendaraan/riwayat', [
        KendaraanController::class,
        'riwayat',
    ])->name('api.v1.kendaraan.riwayat');

    Route::get('/kendaraan/riwayat/detail', [
        KendaraanController::class,
        'detailRiwayat',
    ])->name('api.v1.kendaraan.riwayat.detail');

    Route::get(
        '/uji/total-hari-ini',
        [TotalUjiHariIniController::class, 'index']
    );

    Route::prefix('')->controller(MobileContentController::class)->group(function () {
        Route::get('/menu', 'menu');
        Route::get('/slider', 'slider');
        Route::get('/berita', 'berita');
        Route::get('/jenisujipersyaratan', 'jenisUjiPersyaratan');
        Route::get('/detailpersyaratan', 'detailPersyaratan');
    });
});
