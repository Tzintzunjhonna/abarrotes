<?php

namespace App\Models\Products;

use App\Models\Configuration\TaxSettings;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductsHasTaxes extends Model
{

    const TABLE      = 'products_has_taxes';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';

    const PRODUCTS_ID               = 'products_id';
    const TAX_SETTINGS_ID           = 'tax_settings_id';

    const CREATED_AT                = 'created_at';
    const UPDATED_AT                = 'updated_at';
    const DELETED_AT                = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::PRODUCTS_ID,
        self::TAX_SETTINGS_ID,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
    ];


    public function has_product(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            Products::class,
            Products::ID,
            self::PRODUCTS_ID,
        );
    }

    public function tax_setting(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            TaxSettings::class,
            TaxSettings::ID,
            self::TAX_SETTINGS_ID,
        );
    }
}
