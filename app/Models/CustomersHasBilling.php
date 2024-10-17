<?php

namespace App\Models;

use App\Models\Sat\CatSatExportacion;
use App\Models\Sat\CatSatFormaPago;
use App\Models\Sat\CatSatMetodoPago;
use App\Models\Sat\CatSatRegimenFiscal;
use App\Models\Sat\CatSatUsoCfdi;
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

    protected $with = [
        'has_uso_cdfi',
        'has_regimen_fiscal',
        'has_metodo_pago',
        'has_forma_pago',
        'has_tipo_exportacion',
    ];

    public function has_uso_cdfi(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatUsoCfdi::class,
            'id',
            self::USO_CDFI_ID,
        );
    }
    public function has_regimen_fiscal(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatRegimenFiscal::class,
            'id',
            self::REGIMEN_FISCAL_ID,
        );
    }
    public function has_metodo_pago(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatMetodoPago::class,
            'id',
            self::METODO_PAGO_ID,
        );
    }
    public function has_forma_pago(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatFormaPago::class,
            'id',
            self::FORMA_PAGO_ID,
        );
    }
    public function has_tipo_exportacion(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CatSatExportacion::class,
            'id',
            self::TIPO_EXPORTACION_ID,
        );
    }
}
