<?php
// app/Models/Payment.php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'payments';
    
    protected $fillable = [
        'payment_id',
        'customer_id',
        'invoice_id',
        'amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'transaction_id',
        'status',
        'notes',
        'payment_date',
        'currency',
        'payment_gateway',
        'metadata'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'date',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    protected $attributes = [
        'status' => 'pending',
        'currency' => 'USD'
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            if (empty($payment->payment_id)) {
                $payment->payment_id = 'PAY-' . strtoupper(uniqid());
            }
            
            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }
            
            if (empty($payment->total_amount)) {
                $payment->total_amount = $payment->amount + $payment->tax_amount;
            }
        });
    }
    
    /**
     * Get the customer that owns the payment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    /**
     * Get the invoice associated with the payment.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(AdminInvoice::class);
    }
    
    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }
    
    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    
    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
    
    /**
     * Mark payment as completed.
     */
    public function markAsCompleted(string $transactionId = null): void
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'payment_date' => now()
        ]);
    }
    
    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $notes = null): void
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes ? ($this->notes . "\n" . $notes) : $this->notes
        ]);
    }
}