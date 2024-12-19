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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable(); // Relación con clientes (opcional)
            $table->timestamp('date_sale')->useCurrent(); // Fecha de la venta
            $table->decimal('total', 10, 2)->nullable(); // Monto total de la venta
            $table->decimal('sub_total', 10, 2)->nullable(); // Monto sub total de la venta
            $table->decimal('total_discount', 10, 2)->default(0); // Total con descuento
            $table->decimal('total_taxt', 10, 2)->default(0); // Total de impuestos
            $table->decimal('mount_pay', 10, 2)->nullable(); // Monto que el cliente pagó
            $table->decimal('change', 10, 2)->default(0); // Monto de cambio
            $table->unsignedBigInteger('payment_type_id')->nullable(); // Tipo de pago
            $table->unsignedBigInteger('status_sale_id')->nullable(); // Estado de la venta
            $table->text('comentarios')->nullable(); // Comentarios sobre la venta
            $table->boolean('is_with_invoice')->default(false);
            $table->string('card_payment_reference')->nullable();
            $table->string('voucher_payment_reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('payment_type_id')->references('id')->on('payment_type');
            $table->foreign('status_sale_id')->references('id')->on('status_sale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
