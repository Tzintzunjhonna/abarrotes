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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('rfc')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('name_contact')->nullable();
            $table->string('path_logo')->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('customers_has_address', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('pais_id')->nullable();
            $table->integer('codigo_postal_id')->nullable();
            $table->integer('estado_id')->nullable();
            $table->integer('municipio_id')->nullable();
            $table->integer('localidad_id')->nullable();
            $table->string('street')->nullable();
            $table->integer('number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('customers_has_billing', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('uso_cdfi_id')->nullable();
            $table->integer('regimen_fiscal_id')->nullable();
            $table->integer('metodo_pago_id')->nullable();
            $table->integer('forma_pago_id')->nullable();
            $table->integer('tipo_exportacion_id')->nullable();
            $table->integer('zip_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customers_has_address');
        Schema::dropIfExists('customers_has_billing');
    }
};
