<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{

    const TABLE      = 'products';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    CONST NAME                  = 'name';
    CONST DESCRIPTION           = 'description';
    CONST BARCODE               = 'barcode';
    CONST PRICE                 = 'price';
    CONST STOCK                 = 'stock';
    CONST CATEGORY_ID           = 'category_id';
    CONST PROVIDER_ID           = 'provider_id';
    CONST UNIT_OF_MEASUREMENT   = 'unit_of_measurement';
    CONST IS_ACTIVE             = 'is_active';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::NAME,
        self::DESCRIPTION,
        self::BARCODE,
        self::PRICE,
        self::STOCK,
        self::CATEGORY_ID,
        self::PROVIDER_ID,
        self::UNIT_OF_MEASUREMENT,
        self::IS_ACTIVE,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
        'has_billing',
        'has_address',
    ];

    public function has_billing(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CustomersHasBilling::class,
            CustomersHasBilling::CUSTOMER_ID,
            self::ID,
        );
    }

    public function has_address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CustomersHasAddress::class,
            CustomersHasAddress::CUSTOMER_ID,
            self::ID,
        );
    }
}
