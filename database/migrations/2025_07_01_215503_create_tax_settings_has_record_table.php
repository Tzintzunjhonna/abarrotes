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
        Schema::create('tax_settings_has_record', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_settings_id');
            $table->unsignedBigInteger('tipo_impuesto_id')->nullable();
            $table->unsignedBigInteger('tipo_factor_id')->nullable();
            $table->decimal('tasa_cuota_porcentage', 10, 2)->nullable();
            $table->boolean('is_retencion')->default(false);
            $table->boolean('is_traslado')->default(false);
            $table->boolean('is_ieps')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_settings_has_record');
    }
};
