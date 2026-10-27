<?php

namespace App\Services;

class NomorKendaraanService
{
    /**
     * Normalisasi nomor agar tidak sensitif
     * terhadap huruf besar/kecil dan spasi.
     */
    public function normalize(string $value): string
    {
        return mb_strtolower(
            preg_replace('/\s+/', '', trim($value)) ?? ''
        );
    }

    /**
     * Normalisasi dan validasi pasangan nomor kendaraan.
     */
    public function normalizeInput(
        string $noUji,
        string $noMesin
    ): array {
        $normalizedNoUji = $this->normalize($noUji);
        $normalizedNoMesin = $this->normalize($noMesin);

        return [
            'no_uji' => $normalizedNoUji,
            'no_mesin' => $normalizedNoMesin,
            'valid' => $normalizedNoUji !== ''
                && preg_match('/^\d{5}$/', $normalizedNoMesin) === 1,
        ];
    }
}
