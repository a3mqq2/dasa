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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('delivery_address');
            $table->enum('delivery_type', ['male', 'female']);
            $table->decimal('delivery_fee', 10, 2)->default(10.00);
            $table->enum('order_type', ['instant', 'reservation']);
            $table->date('delivery_date')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer']);
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
