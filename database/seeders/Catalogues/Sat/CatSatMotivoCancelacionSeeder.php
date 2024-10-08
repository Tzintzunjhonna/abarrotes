<?php

namespace Database\Seeders\Catalogues\Sat;

use App\Models\Sat\CatSatMotivoCancelacion;
use Illuminate\Database\Seeder;

class CatSatMotivoCancelacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatSatMotivoCancelacion::create(['codigo' => '01', 'nombre' => 'Comprobantes emitidos con errores con relación']);
        CatSatMotivoCancelacion::create(['codigo' => '02', 'nombre' => 'Comprobantes emitidos con errores sin relación']);
        CatSatMotivoCancelacion::create(['codigo' => '03', 'nombre' => 'No se llevó a cabo la operación']);
        CatSatMotivoCancelacion::create(['codigo' => '04', 'nombre' => 'Operación nominativa relacionada en una factura global']);
    }
}
