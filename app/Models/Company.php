<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    const TABLE      = 'company';
    const CLASS_NAME = __CLASS__;

    const ID = 'id';
    const RFC = 'rfc';
    const BUSINESS_NAME = 'business_name';
    const PATH_LOGO = 'path_logo';
    const ZIP_CODE              ='zip_code';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::ID,
        self::RFC,
        self::BUSINESS_NAME,
        self::PATH_LOGO,
        self::ZIP_CODE,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    );

    protected $with = [
        'has_address',
    ];

    public function has_address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            CompanyHasAddress::class,
            CompanyHasAddress::COMPANY_ID,
            self::ID,
        );
    }
}
