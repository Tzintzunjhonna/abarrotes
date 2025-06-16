<?php

namespace Database\Seeders\Catalogues\Products;

use App\Models\CategorieProducts;
use App\Models\Products\CategorieProducts as ProductsCategorieProducts;
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


        ProductsCategorieProducts::create(
            [
                'name' => 'Alimentos',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Bebidas',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Productos de Higiene Personal',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Limpieza del Hogar',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Congelados',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Dulcería',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Enlatados y Conservas',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Bebidas alcohólicas (si aplican)',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Productos para Mascotas',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Botanas',
            ]
        );
        ProductsCategorieProducts::create(
            [
                'name' => 'Condimentos y Especias',
            ]
        );
    }
}