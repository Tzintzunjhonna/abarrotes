<?php

namespace App\Models\Sat\Addresses;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatSatCountry extends Model
{
    use HasFactory, SoftDeletes;
    

    protected $table = 'cat_sat_pais';
}