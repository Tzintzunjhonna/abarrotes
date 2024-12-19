<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::create([
            'name' => 'Efectivo',
        ]);
        PaymentType::create([
            'name' => 'Tarjeta de débito o crédito',
        ]);
        PaymentType::create([
            'name' => 'Vales',
        ]);
    }
}
