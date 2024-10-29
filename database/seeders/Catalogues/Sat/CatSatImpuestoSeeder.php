<?php

namespace Database\Seeders\Catalogues\Sat;

use App\Models\Sat\CatSatImpuesto;
use Illuminate\Database\Seeder;

class CatSatImpuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CatSatImpuesto::create([
            'codigo' => '001',
            'nombre' => 'ISR'
        ]);
        CatSatImpuesto::create([
            'codigo' => '002',
            'nombre' => 'IVA'
        ]);
        CatSatImpuesto::create([
            'codigo' => '003',
            'nombre' => 'IEPS'
        ]);
    }
}