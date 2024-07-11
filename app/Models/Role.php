<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles as HasRolesTrait;

class Role extends SpatieRole
{
    use HasRolesTrait;
    const TABLE      = 'roles';
    const CLASS_NAME = __CLASS__;

    const ID            = 'id';
    const NAME          = 'name';
    const GUARD_NAME    = 'guard_name';
    const IS_ACTIVE     = 'is_active';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const DELETED_AT  = 'deleted_at';

    use SoftDeletes;
    protected $table    = self::TABLE;
    public $timestamps  = false;

    protected $fillable = array(
        self::NAME,
        self::GUARD_NAME,
        self::IS_ACTIVE,
    );
}
