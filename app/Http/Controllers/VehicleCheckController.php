<?php

namespace App\Http\Controllers;

use App\Services\VehicleLookupService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VehicleCheckController extends Controller
{
    public function showForm()
    {
        return view('pages.vehicle-check', [
            'vehicle' => null,
            'searched' => false,
        ]);
    }

    public function search(Request $request, VehicleLookupService $lookup)
    {
        // Normalisasi input: hapus semua whitespace dan ubah ke huruf kapital.
        $normalize = static function ($value): string {
            return mb_strtoupper(
                preg_replace('/\s+/u', '', (string) $value) ?? '',
                'UTF-8'
            );
        };

        $request->merge([
            'nouji' => $normalize($request->input('nouji')),
            'chassis_last5' => $normalize($request->input('chassis_last5')),
        ]);

        $validated = $request->validate([
            'nouji' => ['required', 'string', 'max:30'],
            'chassis_last5' => ['required', 'regex:/^[0-9]{5}$/'],
        ], [
            'nouji.required' => 'Nomor uji wajib diisi.',
            'nouji.max' => 'Nomor uji maksimal 30 karakter.',
            'chassis_last5.required' => 'Lima digit terakhir nomor rangka wajib diisi.',
            'chassis_last5.regex' => 'Masukkan tepat 5 digit angka terakhir nomor rangka.',
        ]);

        try {
            $vehicle = $lookup->find(
                $validated['nouji'],
                $validated['chassis_last5']
            );
        } catch (\RuntimeException $e) {
            report($e);

            return back()->withInput()->withErrors([
                'lookup' => 'Layanan pengecekan sedang belum tersedia. Silakan coba kembali nanti.',
            ]);
        }

        return view('pages.vehicle-check', [
            'vehicle' => $vehicle,
            'searched' => true,
        ]);
    }
}
