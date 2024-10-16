<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomersHasBilling extends Model
{

    const TABLE      = 'customers_has_billing';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const CUSTOMER_ID           ='customer_id';
    const USO_CDFI_ID           ='uso_cdfi_id';
    const REGIMEN_FISCAL_ID     ='regimen_fiscal_id';
    const METODO_PAGO_ID        ='metodo_pago_id';
    const FORMA_PAGO_ID         ='forma_pago_id';
    const TIPO_EXPORTACION_ID   ='tipo_exportacion_id';
    const ZIP_CODE              ='zip_code';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::CUSTOMER_ID,
        self::USO_CDFI_ID,
        self::REGIMEN_FISCAL_ID,
        self::METODO_PAGO_ID,
        self::FORMA_PAGO_ID,
        self::TIPO_EXPORTACION_ID,
        self::ZIP_CODE,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );
}
