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
        Schema::table('tax_settings', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_impuesto_id',
                'tipo_factor_id',
                'tasa_cuota_porcentage',
                'is_retencion',
                'is_traslado',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_impuesto_id')->nullable();
            $table->unsignedBigInteger('tipo_factor_id')->nullable();
            $table->string('tasa_cuota_porcentage')->nullable();
            $table->boolean('is_retencion')->default(false);
            $table->boolean('is_traslado')->default(false);
        });
    }
};
