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
        Schema::table('orders', function (Blueprint $table) {
            // Update status enum to include new statuses
            $table->enum('status', [
                'pending',           // قيد الانتظار (بعد الطلب مباشرة)
                'confirmed',         // تمت الموافقة
                'preparing',         // قيد التحضير
                'out_for_delivery',  // تم التسليم للمندوب
                'delivered',         // تم التوصيل/مكتمل
                'cancelled'          // ملغي
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};
