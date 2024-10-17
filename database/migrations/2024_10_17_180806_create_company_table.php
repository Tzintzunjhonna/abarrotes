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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('rfc', 13);
            $table->string('business_name')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('path_logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
        });

        Schema::create('company_has_address', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
