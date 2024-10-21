<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Catalogues\Products\CatCategoriesProductSeeder;
use Database\Seeders\Catalogues\Products\UnitOfMeasurementSeeder;
use Database\Seeders\Catalogues\Sat\Address\CatSatCountrySeeder;
use Database\Seeders\Catalogues\Sat\Address\CatSatLocationsSeeder;
use Database\Seeders\Catalogues\Sat\Address\CatSatMunicipalitySeeder;
use Database\Seeders\Catalogues\Sat\Address\CatSatStateSeeder;
use Database\Seeders\Catalogues\Sat\Address\CatSatZipCodeSeeder;
use Database\Seeders\Catalogues\Sat\CatSatClaveUnidadSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);

        // CATALOGOS SAT

        $this->call(CatSatCountrySeeder::class);
        $this->call(CatSatLocationsSeeder::class);
        $this->call(CatSatMunicipalitySeeder::class);
        $this->call(CatSatStateSeeder::class);
        $this->call(CatSatZipCodeSeeder::class);

        $this->call(CatSatClaveUnidadSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatClaveProductoSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatExportacionSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatFormaPagoSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatMetodoPagoSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatMonedaSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatMotivoCancelacionSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatRegimenFiscalSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatSatUsoCfdiSeeder::class);
        $this->call(\Database\Seeders\Catalogues\Sat\CatStatusCfdiCancelSeeder::class);

        // CATALOGOS PRODUCTOS
        $this->call(CatCategoriesProductSeeder::class);
        $this->call(UnitOfMeasurementSeeder::class);
        


    }
}
