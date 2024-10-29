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
        Schema::create('cat_sat_impuesto', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('cat_sat_objeto_impuesto', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('cat_sat_tipo_factor', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('cat_sat_tasa_o_cuota', function (Blueprint $table) {
            $table->id();
            $table->string('rango_o_fijo')->nullable();
            $table->string('valor_minimo')->nullable();
            $table->string('valor_maximo')->nullable();
            $table->string('impuesto')->nullable();
            $table->string('factor')->nullable();
            $table->string('traslado')->nullable();
            $table->string('retencion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_sat_impuesto');
        Schema::dropIfExists('cat_sat_objeto_impuesto');
        Schema::dropIfExists('cat_sat_tasa_o_cuota');
        Schema::dropIfExists('cat_sat_tipo_factor');
    }
};
