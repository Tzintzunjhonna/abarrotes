<?php

namespace Database\Seeders\Catalogues\Sat;

use App\Models\Sat\CatSatTasaOCuota;
use Illuminate\Database\Seeder;

class CatSatTasaOCuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $data = [
            ['Fijo',  null,  0.000000, 'IVA', 'Tasa', 'Sí', 'No'],
            ['Fijo',  null,  0.160000, 'IVA', 'Tasa', 'Sí', 'No'],
            ['Rango', null,  0.160000, 'IVA', 'Tasa', 'No', 'Sí'],
            ['Fijo',  null,  0.080000, 'IVA', 'Tasa', 'Sí', 'No'],
            ['Fijo',  null,  0.265000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.300000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.530000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.500000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  1.600000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.304000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.250000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.090000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.080000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.070000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.060000, 'IEPS', 'Tasa', 'Sí', 'Sí'],
            ['Fijo',  null,  0.030000, 'IEPS', 'Tasa', 'Sí', 'No'],
            ['Fijo',  null,  0.000000, 'IEPS', 'Tasa', 'Sí', 'No'],
            ['Rango', null,  0.591449, 'IEPS', 'Cuota', 'Sí', 'Sí'],
            ['Rango', null,  0.350000, 'ISR', 'Tasa', 'No', 'Sí'],
        ];

        foreach ($data as $item) {
            CatSatTasaOCuota::create([
                'rango_o_fijo' => $item[0],
                'valor_minimo' => $item[1],
                'valor_maximo' => $item[2],
                'impuesto' => $item[3],
                'factor' => $item[4],
                'traslado' => $item[5],
                'retencion' => $item[6],
            ]);
        }
    }
}