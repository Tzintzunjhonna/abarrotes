<?php

namespace App\Models\Sat;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatSatFormaPago extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cat_sat_forma_pago';
}
