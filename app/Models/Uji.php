<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uji extends Model
{
    protected $table = 'tbl_uji';

    protected $primaryKey = 'id_uji';

    public $timestamps = false;

    public function retribusi(): HasMany
    {
        return $this->hasMany(
            Retribusi::class,
            'id_uji',
            'id_uji'
        );
    }
}