<?php

namespace App\Models\Sat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CatSatUsoCfdi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cat_sat_uso_de_cfdi';
}