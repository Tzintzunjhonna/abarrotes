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
            $table->char('ticket_no', 30)->unique(); // Relación con clientes (opcional)
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
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->foreign('status_sale_id')->references('id')->on('status_sale');
        });

        Schema::create('sales_detail', function (Blueprint $table) {
            $table->id();  // ID auto-incremental
            $table->unsignedBigInteger('venta_id')->nullable(); // Relación con ventas
            $table->unsignedBigInteger('producto_id')->nullable(); // Relación con productos
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('cantidad', 10, 2); // Cantidad del producto
            $table->decimal('precio_unitario', 10, 2); // Precio unitario del producto
            $table->decimal('descuento', 10, 2)->default(0); // Descuento aplicado al producto
            $table->decimal('importe', 10, 2); // Subtotal del producto (cantidad * precio - descuento)
            $table->boolean('is_with_tax')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('venta_id')->references('id')->on('sales');
            $table->foreign('producto_id')->references('id')->on('products');
        });

        Schema::create('sales_detail_has_tax', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_detail_id')->nullable(); // Relación con ventas
            $table->integer('tipo_impuesto_id')->nullable();
            $table->integer('tipo_factor_id')->nullable();
            $table->decimal('tasa_cuota_porcentage', 10, 2)->nullable();
            $table->boolean('is_retencion')->default(false);
            $table->boolean('is_traslado')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sales_detail_id')->references('id')->on('sales_detail');
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
