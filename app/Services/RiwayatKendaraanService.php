<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RiwayatKendaraanService
{
    public function __construct(
        private NomorKendaraanService $nomorKendaraanService,
        private TanggalUjiService $tanggalUjiService
    ) {
    }

    /**
     * Riwayat kendaraan berdasarkan Nomor Uji dan
     * 5 digit terakhir Nomor Rangka/Chasis.
     *
     * Sumber utama: tbl_retribusi.
     *
     * id_uji 1, 8, 2, 4:
     * Mengambil data tambahan dari tbl_daftar dan tbl_hasil_uji.
     *
     * id_uji 3, 5:
     * Hanya mengambil data dari tbl_retribusi.
     */
    public function getByNomor(
        string $nomor,
        string $noChasis
    ): array {
        $input = $this->nomorKendaraanService->normalizeInput(
            $nomor,
            $noChasis
        );

        if (!$input['valid']) {
            return [
                'status' => false,
                'message' => 'Nomor Uji dan 5 digit terakhir Nomor Rangka wajib diisi dengan benar.',
                'data' => null,
            ];
        }

        $normalizedNoUji = $input['no_uji'];
        $normalizedNoChasis = $input['no_chasis'];

        $jenisUjiDenganHasil = [1, 8, 2, 4];

        $riwayat = DB::table('tbl_retribusi as r')

            // Kendaraan
            ->join(
                'tbl_kendaraan as k',
                'k.id_kendaraan',
                '=',
                'r.id_kendaraan'
            )

            // Jenis pengujian
            ->leftJoin(
                'tbl_uji as u',
                'u.id_uji',
                '=',
                'r.id_uji'
            )

            // Pendaftaran hanya untuk jenis uji tertentu
            ->leftJoin('tbl_daftar as d', function ($join) use (
                $jenisUjiDenganHasil
            ) {
                $join->on(
                    'd.id_retribusi',
                    '=',
                    'r.id_retribusi'
                )->whereIn(
                    'r.id_uji',
                    $jenisUjiDenganHasil
                );
            })

            // Hasil uji hanya untuk jenis uji tertentu
            ->leftJoin('tbl_hasil_uji as h', function ($join) use (
                $jenisUjiDenganHasil
            ) {
                $join->on(
                    'h.id_daftar',
                    '=',
                    'd.id_daftar'
                )->whereIn(
                    'r.id_uji',
                    $jenisUjiDenganHasil
                );
            })

            // Pencarian kendaraan
            ->whereRaw(
                "REPLACE(LOWER(COALESCE(k.no_uji, '')), ' ', '') = ?",
                [$normalizedNoUji]
            )
            ->whereRaw(
                "RIGHT(
                    REPLACE(LOWER(COALESCE(k.no_chasis, '')), ' ', ''),
                    5
                ) = ?",
                [$normalizedNoChasis]
            )

            ->select([
                'r.id_retribusi',
                'r.id_uji',
                'r.tgl_retribusi',
                'r.tglmati',

                'k.no_uji',
                'k.no_kendaraan',

                'u.nm_uji',

                'd.id_daftar',
                'd.tgl_uji',

                'h.id_hasil_uji',
                'h.nm_penguji',
                'h.lulus_prauji',
                'h.lulus_smoke',
                'h.lulus_break',
                'h.lulus_pitlift',
                'h.lulus_lampu',
            ])

            ->selectRaw(
                'COALESCE(d.tgl_uji, r.tgl_uji, r.tgl_retribusi) AS tanggal_uji'
            )

            ->orderByRaw(
                'COALESCE(d.tgl_uji, r.tgl_uji, r.tgl_retribusi) DESC'
            )
            ->orderByDesc('r.id_retribusi')
            ->get();

        if ($riwayat->isEmpty()) {
            return [
                'status' => 'error',
                'status_message' => 'Data Kosong',
                'data' => [],
            ];
        }

        $data = $riwayat->map(function ($item) {
            $tanggalUji = $item->tanggal_uji;

            $tanggalMati = $item->tglmati
                ? $this->tanggalUjiService->formatTanggal(
                    $item->tglmati
                )
                : $this->tanggalUjiService->tanggalMatiUji(
                    $tanggalUji
                );

            return [
                'no_uji' => $item->no_uji ?? '',
                'no_kendaraan' => $item->no_kendaraan ?? '',

                'tgl_uji' => $this->tanggalUjiService
                    ->formatTanggal($tanggalUji),

                'tglmati' => $tanggalMati,

                'nm_uji' => $item->nm_uji ?? '',

                'status_lulus' => $item->id_hasil_uji
                    ? $this->statusKelulusan($item)
                    : '-',

                'nm_penguji' => $item->nm_penguji ?? '',

                'id_hasil_uji' => $item->id_hasil_uji,
            ];
        })->values()->all();

        return [
            'status' => 'ok',
            'status_message' => '-',
            'data' => $data,
        ];
    }

    /**
     * Status kelulusan berdasarkan seluruh tahapan pengujian.
     */
    private function statusKelulusan(object $hasil): string
    {
        $kolomKelulusan = [
            'lulus_prauji',
            'lulus_smoke',
            'lulus_break',
            'lulus_pitlift',
            'lulus_lampu',
        ];

        foreach ($kolomKelulusan as $kolom) {
            if (!$this->isTrue($hasil->{$kolom})) {
                return 'TIDAK LULUS';
            }
        }

        return 'LULUS';
    }

    /**
     * Konversi nilai boolean dari database.
     */
    private function isTrue(mixed $value): bool
    {
        return in_array(
            strtolower((string) $value),
            ['1', 'true', 't', 'yes', 'y'],
            true
        );
    }
}
