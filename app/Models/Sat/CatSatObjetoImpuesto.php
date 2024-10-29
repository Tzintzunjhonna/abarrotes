<?php

namespace App\Models\Sat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CatSatObjetoImpuesto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cat_sat_objeto_impuesto';
}
