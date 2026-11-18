<?php

namespace App\Http\Controllers;

use App\Models\MobileJenisUji;
use App\Models\MobileDetailPersyaratan;

class RequirementsController extends Controller
{
    public function index()
    {
        $jenisUji = MobileJenisUji::query()
            ->where('status', true)
            ->orderBy('sorting')
            ->orderBy('id')
            ->get();

        $detailPersyaratan = MobileDetailPersyaratan::query()
            ->where('status', true)
            ->orderBy('id')
            ->get()
            ->groupBy('id_jenis_uji');

        return view('pages.persyaratan', compact(
            'jenisUji',
            'detailPersyaratan'
        ));
    }
}
