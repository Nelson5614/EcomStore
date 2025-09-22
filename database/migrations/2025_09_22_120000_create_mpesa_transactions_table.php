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
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('originator_conversation_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('transaction_desc')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('LSL');
            $table->string('customer_msisdn');
            $table->string('service_provider_code');
            $table->string('status')->default('pending');
            $table->string('response_code')->nullable();
            $table->text('response_message')->nullable();
            $table->json('raw_response')->nullable();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_method')->default('mpesa');
            $table->boolean('callback_received')->default(false);
            $table->json('callback_data')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_id');
            $table->index('conversation_id');
            $table->index('transaction_reference');
            $table->index('status');
            $table->index('customer_msisdn');
            $table->index('order_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
