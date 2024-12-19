<?php

namespace App\Models\SalesManagement;

use App\Models\Products\Products;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use SoftDeletes;

    const TABLE = 'sales_detail';
    const CLASS_NAME = __CLASS__;

    const ID               = 'id';
    const VENTA_ID         = 'venta_id';
    const PRODUCTO_ID      = 'producto_id';
    const NAME             = 'name';
    const DESCRIPTION      = 'description';
    const BARCODE          = 'barcode';
    const CANTIDAD         = 'cantidad';
    const PRECIO_UNITARIO  = 'precio_unitario';
    const DESCUENTO        = 'descuento';
    const IMPORTE          = 'importe';
    const IS_WITH_TAX      = 'is_with_tax';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';
    const DELETED_AT       = 'deleted_at';

    protected $table = self::TABLE;

    protected $fillable = [
        self::VENTA_ID,
        self::PRODUCTO_ID,
        self::NAME,
        self::DESCRIPTION,
        self::BARCODE,
        self::CANTIDAD,
        self::PRECIO_UNITARIO,
        self::DESCUENTO,
        self::IMPORTE,
        self::IS_WITH_TAX,
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, self::VENTA_ID);
    }

    public function product()
    {
        return $this->belongsTo(Products::class, self::PRODUCTO_ID);
    }
}
