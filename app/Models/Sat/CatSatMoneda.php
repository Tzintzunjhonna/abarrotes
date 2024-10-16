<?php

namespace App\Models\Sat;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatSatMoneda extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cat_sat_moneda';

    const MXN = 'MXN';
    const USD = 'USD';
    const EURO = 'EUR';
    
    const MXN_ID = 99;
    const USD_ID = 147;
    const EURO_ID = 49;
}
