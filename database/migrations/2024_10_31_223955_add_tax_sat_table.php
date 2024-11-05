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
        Schema::create('products_has_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->unsignedBigInteger('tax_settings_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Llaves foráneas
            $table->foreign('tax_settings_id')->references('id')->on('tax_settings');
            $table->foreign('products_id')->references('id')->on('products');
        });
        Schema::create('products_has_cat_sat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->unsignedBigInteger('clave_producto_id')->nullable();
            $table->unsignedBigInteger('clave_unidad_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Llaves foráneas
            $table->foreign('clave_producto_id')->references('id')->on('cat_sat_clave_producto');
            $table->foreign('clave_unidad_id')->references('id')->on('cat_sat_clave_unidad');
            $table->foreign('products_id')->references('id')->on('products');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('revenue')->nullable();
            $table->unsignedBigInteger('sale_price')->nullable();
            $table->unsignedBigInteger('wholesale_price')->nullable();
            $table->boolean('is_with_tax')->default(false);
            $table->boolean('is_with_discount')->default(false);
        });

        Schema::table('tax_settings', function (Blueprint $table) {
            $table->string('name');
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

        Schema::table('tax_settings', function (Blueprint $table) {
            $table->dropColumn(['name']);
        });
    }
};
