<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_edi = User::create([
            'name' => 'azu',
            'email' => 'azu@corp.com',
            'password' => Hash::make('admin')
        ]);
        $admin_bitfx = User::create([
            'name' => 'admin',
            'email' => 'admin@corp.com',
            'password' => Hash::make('admin')
        ]);

        $admin_edi->assignRole('admin');
        $admin_bitfx->assignRole('admin');
    }
}
