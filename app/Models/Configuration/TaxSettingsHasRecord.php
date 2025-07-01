<?php

namespace App\Models\Configuration;

use App\Models\Sat\CatSatImpuesto;
use App\Models\Sat\CatSatTipoFactor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TaxSettingsHasRecord extends Model
{

    const TABLE      = 'tax_settings_has_record';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const TAX_SETTINGS_ID           = 'tax_settings_id';
    const TIPO_IMPUESTO_ID          = 'tipo_impuesto_id';
    const TIPO_FACTOR_ID            = 'tipo_factor_id';
    const TASA_CUOTA_PORCENTAGE     = 'tasa_cuota_porcentage';
    const IS_RETENCION              = 'is_retencion';
    const IS_TRASLADO               = 'is_traslado';
    const IS_IEPS                   = 'is_ieps';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::TAX_SETTINGS_ID,
        self::TIPO_IMPUESTO_ID,
        self::TIPO_FACTOR_ID,
        self::TASA_CUOTA_PORCENTAGE,
        self::IS_RETENCION,
        self::IS_TRASLADO,
        self::IS_IEPS,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
        'has_tipo_impuesto',
        'has_tipo_factor',
    ];
    public function has_tipo_impuesto(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatImpuesto::class,
            'id',
            self::TIPO_IMPUESTO_ID,
        );
    }

    public function has_tipo_factor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatTipoFactor::class,
            'id',
            self::TIPO_FACTOR_ID,
        );
    }
}
