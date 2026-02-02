<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
     protected $fillable = [
        'invoice_number',
        'invoice_date',
        'status',
        'currency',
        'tax_rate',
        'tax_mode',
        'discount',
        'client_name',
        'client_phone',
        'client_email',
        'client_address',
        'description',
        'subtotal',
        'tax_amount',
        'grand_total',
        'admin_signature',
        'terms',
        'company_id',
        'user_id'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'tax_rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function getFormattedTotalAttribute()
    {
        return $this->currency . number_format($this->grand_total, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Paid' => 'bg-emerald-100 text-emerald-800',
            'Overdue' => 'bg-red-100 text-red-800'
        ];

        $class = $statuses[$this->status] ?? $statuses['Pending'];
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $this->status . '</span>';
    }
}