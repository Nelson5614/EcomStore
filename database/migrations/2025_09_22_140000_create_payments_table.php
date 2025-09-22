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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->string('payment_method'); // mpesa, cod, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('LSL');
            $table->string('phone_number')->nullable(); // For M-Pesa payments
            $table->string('status')->default('pending'); // pending, processing, success, failed
            $table->json('response_data')->nullable(); // M-Pesa API response
            $table->json('callback_data')->nullable(); // M-Pesa callback data
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_id');
            $table->index('order_id');
            $table->index('payment_method');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
