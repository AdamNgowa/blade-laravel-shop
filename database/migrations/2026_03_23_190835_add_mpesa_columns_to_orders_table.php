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
        $table->string('payment_method')->default('stk_push')->after('notes');
        $table->string('checkout_request_id')->nullable()->after('payment_method');
        $table->string('mpesa_receipt_number')->nullable()->after('checkout_request_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'payment_method',
            'checkout_request_id',
            'mpesa_receipt_number'
        ]);
    });
    }
};
