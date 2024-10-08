<?php

namespace Database\Seeders\Catalogues\Sat;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatSatUsoCfdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(public_path('sql/catalogos/sat/cat_sat_uso_cfdi.sql')));
    }
}
