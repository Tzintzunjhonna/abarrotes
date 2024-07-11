<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);

        $permisionCreateRole = Permission::create(['name' => 'Nuevo rol']);
        $permisionReadRole = Permission::create(['name' => 'Leer rol']);
        $permisionUpdateRole = Permission::create(['name' => 'Modificar rol']);
        $permisionDeleteRole = Permission::create(['name' => 'Eliminar rol']);

        $permisionCreateUsuario = Permission::create(['name' => 'Nuevo usuario']);
        $permisionReadUsuario = Permission::create(['name' => 'Leer usuario']);
        $permisionUpdateUsuario = Permission::create(['name' => 'Modificar usuario']);
        $permisionDeleteUsuario = Permission::create(['name' => 'Eliminar usuario']);

        $permisionCreatePermision = Permission::create(['name' => 'Nuevo permisos']);
        $permisionReadPermision = Permission::create(['name' => 'Leer permisos']);
        $permisionUpdatePermision = Permission::create(['name' => 'Modificar permisos']);
        $permisionDeletePermision = Permission::create(['name' => 'Eliminar permisos']);

        $permissionAdmin = [$permisionCreateRole, $permisionReadRole, $permisionUpdateRole, $permisionDeleteRole, 
            $permisionCreateUsuario, $permisionReadUsuario, $permisionUpdateUsuario, $permisionDeleteUsuario,
            $permisionCreatePermision, $permisionReadPermision, $permisionUpdatePermision, $permisionDeletePermision];

        $admin->syncPermissions($permissionAdmin);

    }
}
