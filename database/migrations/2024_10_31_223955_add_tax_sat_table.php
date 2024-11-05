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
        Schema::table('products_has_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->integer('tipo_impuesto_id')->nullable();
            $table->integer('tipo_factor_id')->nullable();
            $table->decimal('tasa_cuota_porcentage', 10, 2)->nullable();
            $table->boolean('is_retencion')->default(false);
            $table->boolean('is_traslado')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('products_has_cat_sat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->unsignedBigInteger('clave_producto_id')->nullable();
            $table->unsignedBigInteger('clave_unidad_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('revenue')->nullable();
            $table->unsignedBigInteger('sale_price')->nullable();
            $table->unsignedBigInteger('wholesale_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_has_taxes');
        Schema::dropIfExists('products_has_cat_sat');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['revenue', 'sale_price', 'wholesale_price']);
        });
    }
};
