<?php

namespace Database\Seeders\Catalogues\Sat\Address;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatSatZipCode extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(public_path('sql/catalogos/sat/address/defaultdb_cat_sat_zip_codes.sql')));
    }
}
