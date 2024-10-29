<?php

namespace Database\Seeders\Catalogues\Sat;

use App\Models\Sat\CatSatTipoFactor;
use Illuminate\Database\Seeder;

class CatSatTipoFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CatSatTipoFactor::create([
            'codigo' => 'Tasa',
            'nombre' => 'Tasa'
        ]);
        CatSatTipoFactor::create([
            'codigo' => 'Cuota',
            'nombre' => 'Cuota'
        ]);
        CatSatTipoFactor::create([
            'codigo' => 'Exento',
            'nombre' => 'Exento'
        ]);
    }
}