<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TaxSettings extends Model
{

    const TABLE      = 'tax_settings';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const NAME = 'name';
    const TIPO_IMPUESTO_ID = 'tipo_impuesto_id';
    const TIPO_FACTOR_ID = 'tipo_factor_id';
    const TASA_CUOTA_PORCENTAGE = 'tasa_cuota_porcentage';
    const IS_RETENCION = 'is_retencion';
    const IS_TRASLADO = 'is_traslado';
    const IS_PRODUCTS_NEW = 'is_products_new';
    const IS_ACTIVE             = 'is_active';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::NAME,
        self::TIPO_IMPUESTO_ID,
        self::TIPO_FACTOR_ID,
        self::TASA_CUOTA_PORCENTAGE,
        self::IS_RETENCION,
        self::IS_TRASLADO,
        self::IS_PRODUCTS_NEW,
        self::IS_ACTIVE,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
        'has_taxes',
    ];
    

    public function has_taxes()
    {
        return $this->hasMany(TaxSettingsHasRecord::CLASS_NAME, TaxSettingsHasRecord::TAX_SETTINGS_ID);
    }
}
