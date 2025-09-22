<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'conversation_id',
        'originator_conversation_id',
        'transaction_reference',
        'transaction_desc',
        'amount',
        'currency',
        'customer_msisdn',
        'service_provider_code',
        'status',
        'response_code',
        'response_message',
        'raw_response',
        'order_id',
        'user_id',
        'payment_method',
        'callback_received',
        'callback_data',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'callback_received' => 'boolean',
        'callback_data' => 'array',
        'raw_response' => 'array',
        'processed_at' => 'datetime',
    ];

    // Transaction Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_TIMEOUT = 'timeout';

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the transaction.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include successful transactions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    /**
     * Scope a query to only include failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Check if the transaction was successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Check if the transaction failed.
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if the transaction is still pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Mark the transaction as successful.
     */
    public function markAsSuccess(array $responseData = []): void
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'response_code' => $responseData['response_code'] ?? null,
            'response_message' => $responseData['response_message'] ?? 'Payment successful',
            'raw_response' => $responseData,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark the transaction as failed.
     */
    public function markAsFailed(array $responseData = []): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'response_code' => $responseData['response_code'] ?? null,
            'response_message' => $responseData['response_message'] ?? 'Payment failed',
            'raw_response' => $responseData,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark the transaction as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
        ]);
    }

    /**
     * Update callback data.
     */
    public function updateCallbackData(array $callbackData): void
    {
        $this->update([
            'callback_received' => true,
            'callback_data' => $callbackData,
        ]);
    }
}
