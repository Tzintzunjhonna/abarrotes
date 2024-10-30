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
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_impuesto_id')->nullable();
            $table->integer('tipo_factor_id')->nullable();
            $table->decimal('tasa_cuota_porcentage', 10, 2)->nullable();
            $table->boolean('is_retencion')->default(false);
            $table->boolean('is_traslado')->default(false);
            $table->boolean('is_products_new')->default(false);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_settings');
    }
};
