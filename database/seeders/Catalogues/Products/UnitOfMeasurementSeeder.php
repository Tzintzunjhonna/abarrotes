<?php

namespace Database\Seeders\Catalogues\Products;

use App\Models\UnitOfMeasurement;
use Illuminate\Database\Seeder;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitOfMeasurement::create(['name' => 'Gramos', 'reference' => 'g']);
        UnitOfMeasurement::create(['name' => 'Kilogramos', 'reference' => 'kg']);
        UnitOfMeasurement::create(['name' => 'Litros', 'reference' => 'l']);
        UnitOfMeasurement::create(['name' => 'Unidades', 'reference' => 'u']);
        UnitOfMeasurement::create(['name' => 'Cajas', 'reference' => 'box']);
        UnitOfMeasurement::create(['name' => 'Bultos', 'reference' => 'bult']);
        // UnitOfMeasurement::create(['name' => 'Rebanadas', 'reference' => 'slices']);
        // UnitOfMeasurement::create(['name' => 'Libras', 'reference' => 'lb']);
        // UnitOfMeasurement::create(['name' => 'Mililitros', 'reference' => 'ml']);
        // UnitOfMeasurement::create(['name' => 'Onzas', 'reference' => 'oz']);
        // UnitOfMeasurement::create(['name' => 'Toneladas', 'reference' => 't']);
        // UnitOfMeasurement::create(['name' => 'Galones', 'reference' => 'gal']);
        // UnitOfMeasurement::create(['name' => 'Pintas', 'reference' => 'pt']);
        // UnitOfMeasurement::create(['name' => 'Cuartos', 'reference' => 'qt']);
        // UnitOfMeasurement::create(['name' => 'Tazas', 'reference' => 'cup']);
        // UnitOfMeasurement::create(['name' => 'Onzas líquidas', 'reference' => 'fl oz']);
        // UnitOfMeasurement::create(['name' => 'Porciones', 'reference' => 'servings']);
        // UnitOfMeasurement::create(['name' => 'Paquetes', 'reference' => 'pkg']);
    }
}