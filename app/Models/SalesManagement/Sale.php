<?php

namespace App\Models\SalesManagement;

use App\Models\Customers;
use App\Models\PaymentType;
use App\Models\StatusSale;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use SoftDeletes;

    const TABLE = 'sales';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const DATE_SALE = 'date_sale';
    const TOTAL = 'total';
    const SUB_TOTAL = 'sub_total';
    const TOTAL_DISCOUNT = 'total_discount';
    const TOTAL_TAXT = 'total_taxt';
    const MOUNT_PAY = 'mount_pay';
    const CHANGE = 'change';
    const PAYMENT_TYPE_ID = 'payment_type_id';
    const STATUS_SALE_ID = 'status_sale_id';
    const COMENTARIOS = 'comentarios';
    const IS_WITH_INVOICE = 'is_with_invoice';
    const CARD_PAYMENT_REFERENCE = 'card_payment_reference';
    const VOUCHER_PAYMENT_REFERENCE = 'voucher_payment_reference';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $table = self::TABLE;

    protected $fillable = [
        self::CUSTOMER_ID,
        self::DATE_SALE,
        self::TOTAL,
        self::SUB_TOTAL,
        self::TOTAL_DISCOUNT,
        self::TOTAL_TAXT,
        self::MOUNT_PAY,
        self::CHANGE,
        self::PAYMENT_TYPE_ID,
        self::STATUS_SALE_ID,
        self::COMENTARIOS,
        self::IS_WITH_INVOICE,
        self::CARD_PAYMENT_REFERENCE,
        self::VOUCHER_PAYMENT_REFERENCE,
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, self::CUSTOMER_ID, Customers::ID);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, self::PAYMENT_TYPE_ID, PaymentType::ID);
    }

    public function statusSale()
    {
        return $this->belongsTo(StatusSale::class, self::STATUS_SALE_ID, StatusSale::ID);
    }
}
