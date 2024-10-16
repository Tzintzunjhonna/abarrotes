<?php

namespace App\Models\Sat\Addresses;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatSatState extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cat_sat_estados';

    public function country(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatCountry::class,
            'codigo_pais',
            'codigo_pais'
        );
    }

}
