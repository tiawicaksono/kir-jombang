<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DetailRiwayatService;
use App\Services\HasilUjiService;
use App\Services\KendaraanService;
use App\Services\KeteranganTidakLulusService;
use App\Services\RiwayatKendaraanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class KendaraanController extends Controller
{
    public function __construct(
        private readonly KendaraanService $kendaraanService,
        private readonly HasilUjiService $hasilUjiService,
        private readonly KeteranganTidakLulusService $keteranganTidakLulusService,
        private readonly RiwayatKendaraanService $riwayatKendaraanService,
        private readonly DetailRiwayatService $detailRiwayatService,
    ) {}

    /**
     * Detail kendaraan.
     */
    public function detail(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nouji' => ['required', 'string', 'max:100'],
                'nomesin' => ['required', 'string', 'regex:/^\d{5}$/'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Nomor Uji dan 5 digit terakhir Nomor Mesin wajib diisi. Nomor Mesin harus tepat 5 digit angka.',
                'errors' => $e->errors(),
                'data' => null,
            ], 422);
        }

        return response()->json(
            $this->kendaraanService->detail(
                $validated['nouji'],
                $validated['nomesin']
            )
        );
    }

    /**
     * Hasil pengujian kendaraan.
     */
    public function hasilUji(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nouji' => ['required', 'string', 'max:100'],
        ]);

        $result = $this->hasilUjiService->hasilUji(
            $validated['nouji']
        );

        return response()->json($result);
    }

    /**
     * Keterangan kendaraan tidak lulus uji.
     */
    public function keteranganTidakLulus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'idhasiluji' => ['required', 'integer', 'min:1'],
            'kategori' => ['required', 'string', 'max:100'],
        ]);

        $result = $this->keteranganTidakLulusService->getByHasilUji(
            (int) $validated['idhasiluji'],
            $validated['kategori']
        );

        return response()->json($result);
    }

    /**
     * Riwayat pengujian kendaraan.
     */
    public function riwayat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nouji' => ['required', 'string', 'max:100'],
            'nomesin' => ['required', 'string', 'regex:/^\d{5}$/'],
        ]);

        $result = $this->riwayatKendaraanService->getByNomor(
            strtoupper(trim($validated['nouji'])),
            $validated['nomesin']
        );

        return response()->json($result);
    }

    /**
     * Detail riwayat pengujian kendaraan.
     */
    public function detailRiwayat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'idhasiluji' => ['required', 'integer', 'min:1'],
            'nouji' => ['required', 'string', 'max:50'],
        ]);

        $result = $this->detailRiwayatService->getByIdHasilUji(
            (int) $validated['idhasiluji'],
            trim($validated['nouji'])
        );

        return response()->json($result);
    }
}
