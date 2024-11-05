<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductsHasCatSat extends Model
{

    const TABLE      = 'products_has_cat_sat';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const PRODUCTS_ID           = 'products_id';
    const CLAVE_PRODUCTO_ID     = 'clave_producto_id';
    const CLAVE_UNIDAD_ID       = 'clave_unidad_id';

    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';
    const DELETED_AT            = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::PRODUCTS_ID,
        self::CLAVE_PRODUCTO_ID,
        self::CLAVE_UNIDAD_ID,
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
