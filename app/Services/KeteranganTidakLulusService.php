<?php

namespace App\Services;

use App\Models\ListKelulusan;

class KeteranganTidakLulusService
{
    public function getByHasilUji(
        int $idHasilUji,
        string $kategori
    ): array {
        $query = ListKelulusan::query()
            ->select([
                'id_hasil_uji',
                'input_tl',
                'kelulusan',
            ])
            ->where('id_hasil_uji', $idHasilUji);

        if (strtolower(trim($kategori)) !== 'all') {
            $query->where('input_tl', trim($kategori));
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            return [
                'status' => 'error',
                'status_message' => 'Data Kosong',
                'data' => [],
            ];
        }

        return [
            'status' => 'ok',
            'status_message' => '-',
            'data' => $items->map(function (ListKelulusan $item) {
                return [
                    'kategori_tidak_lulus' => $item->input_tl,
                    'keterangan' => $item->kelulusan,
                ];
            })->values()->all(),
        ];
    }
}
