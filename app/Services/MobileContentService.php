<?php

namespace App\Services;

use App\Models\MobileMenu;
use App\Models\MobileSlider;
use App\Models\MobileBerita;
use App\Models\MobileJenisUji;
use App\Models\MobileDetailPersyaratan;

class MobileContentService
{
    public function getMenu(): array
    {
        $data = MobileMenu::query()
            ->where('status', true)
            ->orderBy('sorting')
            ->get();

        return $this->success($data->toArray());
    }

    public function getSlider(): array
    {
        $data = MobileSlider::query()
            ->where('status', true)
            ->get();

        return $this->success($data->toArray());
    }

    public function getBerita(): array
    {
        $data = MobileBerita::query()
            ->where('status', true)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        return $this->success($data->toArray());
    }

    public function getJenisUjiPersyaratan(): array
    {
        $data = MobileJenisUji::query()
            ->where('status', true)
            ->orderBy('sorting')
            ->get();

        return $this->success($data->toArray());
    }

    public function getDetailPersyaratan(?int $idJenisUji = null): array
    {
        $query = MobileDetailPersyaratan::query()
            ->where('status', true);

        if ($idJenisUji !== null) {
            $query->where('id_jenis_uji', $idJenisUji);
        }

        $data = $query
            ->orderBy('id')
            ->get();

        return $this->success($data->toArray());
    }

    private function success(array $data): array
    {
        return [
            'status' => 'ok',
            'status_message' => '-',
            'data' => $data,
        ];
    }
}
