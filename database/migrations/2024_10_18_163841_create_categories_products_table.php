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
        Schema::create('categories_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->string('unit_of_measurement');
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Llaves foráneas
            $table->foreign('category_id')->references('id')->on('categories_products');
            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_products');
        Schema::dropIfExists('products');
    }
};