<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{

    const TABLE      = 'payment_types';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const NAME = 'name';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::NAME,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
    ];
}
