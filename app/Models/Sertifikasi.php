<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikasi extends Model
{
    protected $table = 'tbl_sertifikasi';

    public $timestamps = false;

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(
            Kendaraan::class,
            'id_kendaraan',
            'id_kendaraan'
        );
    }
}
