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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            // $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');

            $table->enum('order_status', ['new','processing','shipped','delivered','cancelled','refund'])->default('new'); // pending, processing, completed, cancelled
            $table->string('currency')->nullable();
            $table->decimal('shipping_amount', 10, 2)->nullable();
            $table->string('shipping_method')->nullable();
            $table->text('notes')->nullable();
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
