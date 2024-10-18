<?php

namespace Database\Seeders\Catalogues\Products;

use App\Models\CategorieProducts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatCategoriesProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        CategorieProducts::create(
            [
                'name' => 'Alimentos',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Bebidas',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Productos de Higiene Personal',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Limpieza del Hogar',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Congelados',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Dulcería',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Enlatados y Conservas',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Bebidas alcohólicas (si aplican)',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Productos para Mascotas',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Botanas',
            ]
        );
        CategorieProducts::create(
            [
                'name' => 'Condimentos y Especias',
            ]
        );
    }
}