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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->decimal('discount', 10, 2)->nullable()->change();
            $table->decimal('revenue', 10, 2)->nullable()->change();
            $table->decimal('sale_price', 10, 2)->nullable()->change();
            $table->decimal('wholesale_price', 10, 2)->nullable()->change();
        });
        Schema::table('tax_settings', function (Blueprint $table) {
            $table->decimal('tasa_cuota_porcentage', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
