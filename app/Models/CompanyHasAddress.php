<?php

namespace App\Models;

use App\Models\Sat\Addresses\CatSatCountry;
use App\Models\Sat\Addresses\CatSatLocation;
use App\Models\Sat\Addresses\CatSatMunicipality;
use App\Models\Sat\Addresses\CatSatState;
use App\Models\Sat\Addresses\CatSatZipCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CompanyHasAddress extends Model
{

    const TABLE      = 'company_has_address';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';

    const COMPANY_ID           = 'company_id';
    const PAIS_ID               = 'pais_id';
    const CODIGO_POSTAL_ID      = 'codigo_postal_id';
    const ESTADO_ID             = 'estado_id';
    const MUNICIPIO_ID          = 'municipio_id';
    const LOCALIDAD_ID          = 'localidad_id';
    const STREET                = 'street';
    const NUMBER                = 'number';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::COMPANY_ID,
        self::PAIS_ID,
        self::CODIGO_POSTAL_ID,
        self::ESTADO_ID,
        self::MUNICIPIO_ID,
        self::LOCALIDAD_ID,
        self::STREET,
        self::NUMBER,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
        'has_pais',
        'has_codigo_postal',
        'has_estado',
        'has_municipio',
        'has_localidad',
    ];

    public function has_pais(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatCountry::class,
            'id',
            self::PAIS_ID,
        );
    }
    public function has_codigo_postal(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatZipCode::class,
            'codigo',
            self::CODIGO_POSTAL_ID,
        );
    }
    public function has_estado(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatState::class,
            'id',
            self::ESTADO_ID,
        );
    }
    public function has_municipio(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatMunicipality::class,
            'id',
            self::MUNICIPIO_ID,
        );
    }
    public function has_localidad(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatLocation::class,
            'id',
            self::LOCALIDAD_ID,
        );
    }
}
