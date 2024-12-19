<?php

namespace App\Models\SalesManagement;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SalesDetail;

class SalesDetailHasTax extends Model
{
    use SoftDeletes;

    const TABLE = 'sales_detail_has_tax';
    const CLASS_NAME = __CLASS__;

    const ID                   = 'id';
    const SALES_DETAIL_ID      = 'sales_detail_id';
    const TIPO_IMPUESTO_ID     = 'tipo_impuesto_id';
    const TIPO_FACTOR_ID       = 'tipo_factor_id';
    const TASA_CUOTA_PORCENTAJE = 'tasa_cuota_porcentage';
    const IS_RETENCION         = 'is_retencion';
    const IS_TRASLADO          = 'is_traslado';
    const CREATED_AT           = 'created_at';
    const UPDATED_AT           = 'updated_at';
    const DELETED_AT           = 'deleted_at';

    protected $table = self::TABLE;

    protected $fillable = [
        self::SALES_DETAIL_ID,
        self::TIPO_IMPUESTO_ID,
        self::TIPO_FACTOR_ID,
        self::TASA_CUOTA_PORCENTAJE,
        self::IS_RETENCION,
        self::IS_TRASLADO,
    ];

    public function salesDetail()
    {
        return $this->belongsTo(SalesDetail::class, self::SALES_DETAIL_ID);
    }
}
