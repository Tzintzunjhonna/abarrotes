<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductsHasTaxes extends Model
{

    const TABLE      = 'products_has_taxes';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';

    const PRODUCTS_ID               = 'products_id';
    const TIPO_IMPUESTO_ID          = 'tipo_impuesto_id';
    const TIPO_FACTOR_ID            = 'tipo_factor_id';
    const TASA_CUOTA_PORCENTAGE     = 'tasa_cuota_porcentage';
    const IS_RETENCION              = 'is_retencion';
    const IS_TRASLADO               = 'is_traslado';

    const CREATED_AT                = 'created_at';
    const UPDATED_AT                = 'updated_at';
    const DELETED_AT                = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::PRODUCTS_ID,
        self::TIPO_IMPUESTO_ID,
        self::TIPO_FACTOR_ID,
        self::TASA_CUOTA_PORCENTAGE,
        self::IS_RETENCION,
        self::IS_TRASLADO,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
    ];

}
