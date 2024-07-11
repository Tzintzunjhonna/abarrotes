<?php

namespace ABARROTES\Admin\User\Infrastructure\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class UserEloquentModel extends Authenticatable
{
    use HasApiTokens, HasRoles, HasUuids;

    protected $table     = 'users';
    public $incrementing = false;
    protected $keyType   = 'string';
    protected $guarded   = [];

    const ID         = 'id';
    const NAME       = 'name';
    const FIRST_NAME = 'first_name';
    const LAST_NAME  = 'last_name';
    const PHONE      = 'phone';
    const EMAIL      = 'email';
    const ROLE       = 'role';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
