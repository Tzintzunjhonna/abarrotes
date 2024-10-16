<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cat_sat_codigo_postal', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('codigo_estado');
            $table->string('codigo_municipio');
            $table->string('codigo_localidad');
            $table->string('codigo_borde_fronterizo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_sat_codigo_postal');
    }
};
