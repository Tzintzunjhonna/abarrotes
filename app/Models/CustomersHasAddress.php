<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomersHasAddress extends Model
{

    const TABLE      = 'customers_has_address';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';

    const CUSTOMER_ID           = 'customer_id';
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
        self::CUSTOMER_ID,
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
}
