<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class VehicleLookupService
{
    private const DETAIL_FIELDS = [
        'norangka' => 'Nomor Rangka',
        'nomesin' => 'Nomor Mesin',
        'merek' => 'Merek',
        'tipe' => 'Tipe',
        'jenis' => 'Jenis Kendaraan',
        'bahanbakar' => 'Bahan Bakar',
        'jbb' => 'JBB',
        'jbi' => 'JBI',
        'mst' => 'MST',
        'beratkosong' => 'Berat Kosong',
        'panjangkendaraan' => 'Panjang Kendaraan',
        'tinggikendaraan' => 'Tinggi Kendaraan',
        'lebarkendaraan' => 'Lebar Kendaraan',
        'kelasjalanterendah' => 'Kelas Jalan Terendah',
        'masaberlakuuji' => 'Masa Berlaku Uji',
    ];

    private const PHOTO_FIELDS = [
        'fotodepansmall' => 'Tampak Depan',
        'fotobelakangsmall' => 'Tampak Belakang',
        'fotokanansmall' => 'Tampak Kanan',
        'fotokirismall' => 'Tampak Kiri',
    ];

    private function normalize(string $value): string
    {
        return mb_strtoupper(
            preg_replace('/\s+/u', '', $value) ?? '',
            'UTF-8'
        );
    }

    public function find(string $testNumber, string $lastFive): ?array
    {
        $testNumber = $this->normalize($testNumber);
        $lastFive = $this->normalize($lastFive);

        try {
            $columns = array_merge(
                ['nouji'],
                array_keys(self::DETAIL_FIELDS),
                array_keys(self::PHOTO_FIELDS)
            );

            $row = DB::connection()
                ->table('public.datapengujian')
                ->select($columns)
                ->whereRaw(
                    "regexp_replace(upper(nouji), '[[:space:]]', '', 'g') = ?",
                    [$testNumber]
                )
                ->whereRaw(
                    "RIGHT(regexp_replace(upper(norangka), '[[:space:]]', '', 'g'), 5) = ?",
                    [$lastFive]
                )
                ->orderByDesc('idx')
                ->first();

            if (!$row) {
                return null;
            }

            $raw = (array) $row;
            $details = [];

            foreach (self::DETAIL_FIELDS as $field => $label) {
                $value = $raw[$field] ?? null;

                if ($value === null || trim((string) $value) === '') {
                    continue;
                }

                $details[] = [
                    'label' => $label,
                    'value' => (string) $value,
                ];
            }

            $photos = [];

            foreach (self::PHOTO_FIELDS as $field => $label) {
                $image = $this->imageDataUri($raw[$field] ?? null);

                if ($image !== null) {
                    $photos[] = [
                        'label' => $label,
                        'src' => $image,
                    ];
                }
            }

            return [
                'nouji' => (string) ($raw['nouji'] ?? ''),
                'masaberlakuuji' => (string) ($raw['masaberlakuuji'] ?? ''),
                'details' => $details,
                'photos' => $photos,
            ];
        } catch (Throwable $e) {
            report($e);

            throw new RuntimeException(
                'Tidak dapat membaca data pengujian.',
                previous: $e
            );
        }
    }

    private function imageDataUri(mixed $value): ?string
    {
        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        // PostgreSQL BYTEA dalam format hexadecimal: \xFFD8...
        if (str_starts_with($value, '\\x')) {
            $hex = substr($value, 2);

            if (
                $hex !== ''
                && strlen($hex) % 2 === 0
                && ctype_xdigit($hex)
            ) {
                $decoded = hex2bin($hex);

                if ($decoded !== false) {
                    $value = $decoded;
                }
            }
        }

        $imageInfo = @getimagesizefromstring($value);

        if ($imageInfo === false || empty($imageInfo['mime'])) {
            return null;
        }

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($imageInfo['mime'], $allowedMimes, true)) {
            return null;
        }

        return 'data:' . $imageInfo['mime']
            . ';base64,'
            . base64_encode($value);
    }
}
