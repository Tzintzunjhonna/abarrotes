<?php

namespace App\Models\Products;

use App\Models\Providers;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\CategorieProducts;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{

    const TABLE      = 'products';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const NAME                  = 'name';
    const DESCRIPTION           = 'description';
    const BARCODE               = 'barcode';
    const PRICE                 = 'price';
    const DISCOUNT              = 'discount';
    const STOCK                 = 'stock';
    const CATEGORY_ID           = 'category_id';
    const PROVIDER_ID           = 'provider_id';
    const UNIT_OF_MEASUREMENT   = 'unit_of_measurement';
    const IS_ACTIVE             = 'is_active';
    const REVENUE               = 'revenue';
    const SALE_PRICE            = 'sale_price';
    const WHOLESALE_PRICE       = 'wholesale_price';
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
        self::DISCOUNT,
        self::PRICE,
        self::STOCK,
        self::CATEGORY_ID,
        self::PROVIDER_ID,
        self::UNIT_OF_MEASUREMENT,
        self::IS_ACTIVE,
        self::REVENUE,
        self::SALE_PRICE,
        self::WHOLESALE_PRICE,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
        'has_categorie_products',
        'has_provider',
        'has_unit_of_measurement',
    ];

    public function has_categorie_products(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CategorieProducts::class,
            CategorieProducts::ID,
            self::CATEGORY_ID,
        );
    }

    public function has_provider(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            Providers::class,
            Providers::ID,
            self::PROVIDER_ID,
        );
    }
    public function has_unit_of_measurement(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            UnitOfMeasurement::class,
            UnitOfMeasurement::ID,
            self::UNIT_OF_MEASUREMENT,
        );
    }
}
