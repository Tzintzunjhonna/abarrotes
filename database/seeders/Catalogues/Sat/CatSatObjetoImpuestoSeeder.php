<?php

namespace Database\Seeders\Catalogues\Sat;

use App\Models\Sat\CatSatObjetoImpuesto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatSatObjetoImpuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CatSatObjetoImpuesto::create([
            'codigo' => '01',
            'nombre' => 'No objeto de impuesto'
        ]);
        CatSatObjetoImpuesto::create([
            'codigo' => '02',
            'nombre' => 'Sí objeto de impuesto'
        ]);
        CatSatObjetoImpuesto::create([
            'codigo' => '03',
            'nombre' => 'Sí objeto del impuesto y no obligado al desglose'
        ]);
        CatSatObjetoImpuesto::create([
            'codigo' => '04',
            'nombre' => 'Sí objeto del impuesto y no causa impuesto.'
        ]);
    }
}