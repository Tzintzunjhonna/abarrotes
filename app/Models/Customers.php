<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{

    const TABLE      = 'customers';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const NAME = 'name';
    const BUSINESS_NAME = 'business_name';
    const ADDRESS = 'address';
    const PHONE = 'phone';
    const EMAIL = 'email';
    const NAME_CONTACT = 'name_contact';
    const PATH_LOGO = 'path_logo';
    const IS_ACTIVE = 'is_active';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::NAME,
        self::BUSINESS_NAME,
        self::ADDRESS,
        self::PHONE,
        self::EMAIL,
        self::NAME_CONTACT,
        self::PATH_LOGO,
        self::IS_ACTIVE,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );
}
