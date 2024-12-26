<?php

namespace Database\Seeders;

use App\Models\StatusSale;
use Illuminate\Database\Seeder;

class StatusSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusSale::create([
            'name' => 'Pendiente',
        ]);
        StatusSale::create([
            'name' => 'Completado',
        ]);
        StatusSale::create([
            'name' => 'Cancelado',
        ]);
        StatusSale::create([
            'name' => 'Timbrado',
        ]);
        StatusSale::create([
            'name' => 'Factura pendiente',
        ]);
    }
}
