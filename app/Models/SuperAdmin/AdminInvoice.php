<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AdminInvoice extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'status',
        'currency',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'client_gstin',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_gstin',
        'company_bank_details',
        'company_logo',
        'items',
        'tax_rate',
        'tax_type',
        'subtotal',
        'tax_amount',
        'discount',
        'grand_total',
        'description',
        'terms_conditions',
        'signature',
        'payment_method',
        'transaction_id',
        'payment_date',
    ];
    
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];
    
    /**
     * Generate next invoice number
     */
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Get the last invoice for this year
        $lastInvoice = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInvoice && $lastInvoice->invoice_number) {
            // Try to extract the sequential number from existing invoice number
            $lastNumber = $lastInvoice->invoice_number;
            
            // Pattern: INV-YYYY-XXXXX
            if (preg_match('/INV-\d{4}-(\d+)/', $lastNumber, $matches)) {
                $sequence = intval($matches[1]) + 1;
            } else {
                // If pattern doesn't match, count invoices for this year
                $count = self::whereYear('created_at', $year)->count();
                $sequence = $count + 1;
            }
        } else {
            $sequence = 1;
        }
        
        return "INV-{$year}-" . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get items attribute (ensure it's always an array)
     */
    public function getItemsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }
    
    /**
     * Set items attribute
     */
    public function setItemsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['items'] = json_encode($value);
        } else {
            $this->attributes['items'] = $value;
        }
    }
    
    /**
     * Scope for pending invoices
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
    
    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
    
    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
    
    /**
     * Get formatted grand total
     */
    public function getFormattedTotalAttribute()
    {
        return $this->currency . number_format($this->grand_total, 2);
    }
    
    /**
     * Get formatted invoice date
     */
    public function getFormattedInvoiceDateAttribute()
    {
        return $this->invoice_date->format('d M Y');
    }
    
    /**
     * Get formatted due date
     */
    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? $this->due_date->format('d M Y') : 'N/A';
    }
}