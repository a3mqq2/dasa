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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('variant_combination')->nullable(); // JSON string for variant combination (e.g., {"size":"L","color":"Red"})
            $table->integer('quantity')->default(0);
            $table->timestamps();

            // Unique constraint: one stock entry per product or product+variant combination
            $table->unique(['product_id', 'product_variant_id', 'variant_combination'], 'product_stock_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock');
    }
};
