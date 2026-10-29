<?php

namespace App\Http\Controllers;

use App\Services\VehicleLookupService;
use Illuminate\Http\Request;
use RuntimeException;

class VehicleCheckController extends Controller
{
    private const VIEW = 'pages.vehicle-check';

    public function showForm()
    {
        return view(self::VIEW, [
            'vehicle' => null,
            'searched' => false,
        ]);
    }

    public function search(
        Request $request,
        VehicleLookupService $lookup
    ) {
        $request->merge([
            'nouji' => $this->normalize($request->input('nouji')),
            'chassis_last5' => $this->normalize(
                $request->input('chassis_last5')
            ),
        ]);

        $validated = $request->validate([
            'nouji' => ['required', 'string', 'max:30'],
            'chassis_last5' => [
                'required',
                'string',
                'regex:/^[0-9]{5}$/',
            ],
        ], [
            'nouji.required' => 'Nomor uji wajib diisi.',
            'nouji.max' => 'Nomor uji maksimal 30 karakter.',
            'chassis_last5.required' =>
                'Lima digit terakhir nomor rangka wajib diisi.',
            'chassis_last5.regex' =>
                'Masukkan tepat 5 digit angka terakhir nomor rangka.',
        ]);

        try {
            $vehicle = $lookup->find(
                $validated['nouji'],
                $validated['chassis_last5']
            );
        } catch (RuntimeException $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'lookup' =>
                        'Layanan pengecekan sedang tidak tersedia. '
                        . 'Silakan coba kembali nanti.',
                ]);
        }

        return view(self::VIEW, [
            'vehicle' => $vehicle,
            'searched' => true,
        ]);
    }

    private function normalize(mixed $value): string
    {
        return mb_strtoupper(
            preg_replace('/\s+/u', '', (string) $value) ?? '',
            'UTF-8'
        );
    }
}
