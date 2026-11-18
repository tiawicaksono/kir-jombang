<?php

namespace App\Services;

use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class VehicleLookupService
{
    private const DETAIL_FIELDS = [
        'no_chasis' => 'Nomor Rangka',
        'no_mesin' => 'Nomor Mesin',
        'merk' => 'Merek',
        'tipe' => 'Tipe',
        'jenis' => 'Jenis Kendaraan',
        'bahan_bakar' => 'Bahan Bakar',
        'jbb' => 'JBB',
        'jbi' => 'JBI',
        'mst' => 'MST',
        'berat_kosong' => 'Berat Kosong',
        'panjang' => 'Panjang Kendaraan',
        'tinggi' => 'Tinggi Kendaraan',
        'lebar' => 'Lebar Kendaraan',
        'kelas_jalan' => 'Kelas Jalan Terendah',
    ];

    private const PHOTO_FIELDS = [
        'img_depan' => 'Tampak Depan',
        'img_belakang' => 'Tampak Belakang',
        'img_kanan' => 'Tampak Kanan',
        'img_kiri' => 'Tampak Kiri',
    ];

    private const TEST_RESULTS = [
        'lulus_prauji' => 'Prauji',
        'lulus_smoke' => 'Emisi',
        'lulus_pitlift' => 'Pit Lift',
        'lulus_lampu' => 'Lampu',
        'lulus_break' => 'Rem',
    ];

    public function find(
        string $testNumber,
        string $lastFive
    ): ?array {
        $testNumber = $this->normalize($testNumber);
        $lastFive = $this->normalize($lastFive);

        if (
            $testNumber === ''
            || !preg_match('/^[0-9]{5}$/', $lastFive)
        ) {
            return null;
        }

        try {
            $kendaraan = Kendaraan::query()
                ->with([
                    'type',
                    'sertifikasi',
                    'jenisKendaraan',
                ])
                ->where(function ($query) use ($testNumber) {
                    $query->whereRaw(
                        "regexp_replace(
                upper(coalesce(no_uji, '')),
                '[[:space:]]',
                '',
                'g'
            ) = ?",
                        [$testNumber]
                    )
                        ->orWhereRaw(
                            "regexp_replace(
                upper(coalesce(no_kendaraan, '')),
                '[[:space:]]',
                '',
                'g'
            ) = ?",
                            [$testNumber]
                        );
                })
                ->whereRaw(
                    "right(
            regexp_replace(
                upper(coalesce(no_chasis, '')),
                '[[:space:]]',
                '',
                'g'
            ),
            5
        ) = ?",
                    [$lastFive]
                )
                ->first();

            if (!$kendaraan) {
                return null;
            }

            return [
                'nouji' => (string) ($kendaraan->no_uji ?? ''),
                'masaberlakuuji' => (string) (
                    $kendaraan->tgl_mati_uji ?? ''
                ),
                'details' => $this->formatDetails($kendaraan),

                // Foto tidak lagi ditampilkan pada bagian identitas kendaraan.
                // Foto disediakan per riwayat melalui key foto_kendaraan.
                'photos' => [],

                'riwayat' => $this->getRiwayat(
                    (int) $kendaraan->id_kendaraan
                ),
            ];
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Tidak dapat membaca data kendaraan.',
                previous: $exception
            );
        }
    }

    private function normalize(string $value): string
    {
        return mb_strtoupper(
            preg_replace('/\s+/u', '', trim($value)) ?? '',
            'UTF-8'
        );
    }

    private function formatDetails(Kendaraan $kendaraan): array
    {
        $type = $kendaraan->type;
        $jenisKendaraan = $kendaraan->jenisKendaraan;

        $raw = [
            'no_chasis' => $kendaraan->no_chasis,
            'no_mesin' => $kendaraan->no_mesin,
            'merk' => $kendaraan->merk,
            'tipe' => $kendaraan->tipe,
            'jenis' => $jenisKendaraan?->jenis
                ?? $kendaraan->jenis,
            'bahan_bakar' => $type?->bahan_bakar,
            'jbb' => $type?->kemjbb,
            'jbi' => $this->calculateJbi($type),
            'mst' => $type?->mst,
            'berat_kosong' => null,
            'panjang' => $type?->ukuran_panjang,
            'tinggi' => $type?->ukuran_tinggi,
            'lebar' => $type?->ukuran_lebar,
            'kelas_jalan' => $type?->kls_jln,
        ];

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

        return $details;
    }

    private function calculateJbi($type): ?float
    {
        if (!$type) {
            return null;
        }

        $fields = [
            'kemorang',
            'kembarang',
            'bsumbu1',
            'bsumbu2',
            'bsumbu3',
            'bsumbu4',
            'bsumbu5',
        ];

        $total = 0.0;
        $hasValue = false;

        foreach ($fields as $field) {
            $value = $type->{$field};

            if ($value === null || trim((string) $value) === '') {
                continue;
            }

            $number = preg_replace(
                '/[^0-9,.-]/',
                '',
                (string) $value
            );

            $number = str_replace(',', '.', $number);

            if (!is_numeric($number)) {
                continue;
            }

            $total += (float) $number;
            $hasValue = true;
        }

        return $hasValue ? round($total, 2) : null;
    }

    private function formatPhotos($hasilUji): array
    {
        if (!$hasilUji) {
            return [];
        }

        $photos = [];

        foreach (self::PHOTO_FIELDS as $field => $label) {
            $src = $this->imageDataUri($hasilUji->{$field});

            if ($src === null) {
                continue;
            }

            $photos[] = [
                'label' => $label,
                'src' => $src,
            ];
        }

        return $photos;
    }

    private function imageDataUri(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (preg_match(
            '/^data:image\/(jpeg|png|webp);base64,/i',
            $value
        )) {
            return $value;
        }

        $decoded = base64_decode($value, true);

        if ($decoded !== false && $decoded !== '') {
            $mime = $this->detectImageMime($decoded);

            if ($mime !== null) {
                return 'data:' . $mime
                    . ';base64,'
                    . base64_encode($decoded);
            }
        }

        // Mendukung kolom yang menyimpan data gambar dalam bentuk biner.
        $mime = $this->detectImageMime($value);

        if ($mime !== null) {
            return 'data:' . $mime
                . ';base64,'
                . base64_encode($value);
        }

        return null;
    }

    private function detectImageMime(string $binary): ?string
    {
        $imageInfo = @getimagesizefromstring($binary);

        if ($imageInfo === false) {
            return null;
        }

        $mime = $imageInfo['mime'] ?? '';

        return in_array(
            $mime,
            ['image/jpeg', 'image/png', 'image/webp'],
            true
        ) ? $mime : null;
    }

    private function getRiwayat(int $idKendaraan): array
    {
        $jenisUjiDenganHasil = [1, 2, 4, 8];

        $riwayat = DB::table('tbl_retribusi as r')
            ->join(
                'tbl_kendaraan as k',
                'k.id_kendaraan',
                '=',
                'r.id_kendaraan'
            )
            ->leftJoin(
                'tbl_uji as u',
                'u.id_uji',
                '=',
                'r.id_uji'
            )
            ->leftJoin('tbl_daftar as d', function ($join) use ($jenisUjiDenganHasil) {
                $join->on(
                    'd.id_retribusi',
                    '=',
                    'r.id_retribusi'
                )->whereIn('r.id_uji', $jenisUjiDenganHasil);
            })
            ->leftJoin('tbl_hasil_uji as h', function ($join) {
                $join->on(
                    'h.id_daftar',
                    '=',
                    'd.id_daftar'
                );
            })
            ->where('r.id_kendaraan', $idKendaraan)
            ->select([
                'r.id_retribusi',
                'r.id_uji',
                'r.tgl_retribusi',
                'r.tgl_uji',
                'r.tglmati',

                'u.nm_uji',

                'd.id_daftar',
                'd.tgl_uji as tanggal_daftar',

                'h.id_hasil_uji',
                'h.jdatang',
                'h.jselesai',
                'h.lulus_prauji',
                'h.lulus_smoke',
                'h.lulus_pitlift',
                'h.lulus_lampu',
                'h.lulus_break',
                'h.uji_ulang',
                'h.nm_penguji',

                'h.img_depan',
                'h.img_belakang',
                'h.img_kanan',
                'h.img_kiri',
            ])
            ->selectRaw("
            COALESCE(
                d.tgl_uji,
                r.tgl_uji,
                r.tgl_retribusi
            ) AS tanggal_uji
        ")
            ->orderByRaw("
            COALESCE(
                d.tgl_uji,
                r.tgl_uji,
                r.tgl_retribusi
            ) DESC
        ")
            ->orderByDesc('r.id_retribusi')
            ->get();

        if ($riwayat->isEmpty()) {
            return [];
        }

        return $riwayat->map(function ($item) {
            // Baris hasil pemeriksaan hanya tersedia untuk jenis
            // pengujian yang memiliki proses pendaftaran dan hasil uji.
            $hasil = $item->id_hasil_uji ? $item : null;

            return [
                // id_retribusi menjadi identitas riwayat yang baru.
                'id_retribusi' => $item->id_retribusi,

                // Dipertahankan sementara agar frontend lama tidak rusak.
                'id_riwayat' => $item->id_retribusi,

                'id_uji' => $item->id_uji,
                'nama_uji' => $item->nm_uji,

                'tanggal_uji' => $item->tanggal_uji,
                'masa_berlaku' => $item->tglmati,

                // Data ini dahulu berasal dari tbl_riwayat.
                // Belum ada sumber penggantinya pada struktur yang kita gunakan.
                'tempat' => null,
                'tempat_tujuan' => null,
                'catatan' => null,

                'nama_penguji' => $item->nm_penguji,

                'hasil_uji' => $this->formatHasilUji($hasil),
                'foto_kendaraan' => $this->formatPhotos($hasil),
            ];
        })->all();
    }

    private function formatHasilUji($hasil): ?array
    {
        if (!$hasil) {
            return null;
        }

        $results = [];

        foreach (self::TEST_RESULTS as $field => $label) {
            $value = $hasil->{$field};
            $key = str_replace('lulus_', '', $field);

            $results[$key] = array_merge(
                ['label' => $label],
                $this->formatStatus($value)
            );
        }

        return [
            'tanggal_datang' => $hasil->jdatang,
            'tanggal_selesai' => $hasil->jselesai,
            'uji_ulang' => $hasil->uji_ulang,
            ...$results,
        ];
    }

    private function formatStatus(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [
                'status' => 'BELUM ADA DATA',
                'status_class' => 'unknown',
            ];
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            if (in_array($normalized, ['t', 'true', '1'], true)) {
                $value = true;
            } elseif (in_array($normalized, ['f', 'false', '0'], true)) {
                $value = false;
            } else {
                return [
                    'status' => 'BELUM ADA DATA',
                    'status_class' => 'unknown',
                ];
            }
        }

        return (bool) $value
            ? [
                'status' => 'LULUS',
                'status_class' => 'passed',
            ]
            : [
                'status' => 'TIDAK LULUS',
                'status_class' => 'failed',
            ];
    }
}
