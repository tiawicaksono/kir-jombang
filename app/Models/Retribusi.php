<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retribusi extends Model
{
    protected $table = 'tbl_retribusi';

    protected $primaryKey = 'id_retribusi';

    public $timestamps = false;

    public function uji(): BelongsTo
    {
        return $this->belongsTo(
            Uji::class,
            'id_uji',
            'id_uji'
        );
    }
}
