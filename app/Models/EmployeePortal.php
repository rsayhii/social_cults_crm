<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EmployeePortal extends Model
{
    use SoftDeletes;

    protected $table = 'employee_portals';

    protected $fillable = [
        'company_id',
        'employee_name',
        'employee_email',
        'employee_mobile',
        'employee_position',
        'sent_to',
        'subject',
        'leave_type',
        'from_date',
        'to_date',
        'total_days',
        'reason',
        'status',
        'admin_remarks',
        'attachments',
        'timeline',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'total_days' => 'integer',
        'attachments' => 'array',
        'timeline' => 'array',
        'applied_date' => 'datetime',
    ];

    /**
     * Get the leave type name
     */
    public function getLeaveTypeNameAttribute(): string
    {
        $types = [
            'casual' => 'Casual Leave',
            'sick' => 'Sick Leave',
            'paid' => 'Paid Leave',
            'emergency' => 'Emergency Leave',
            'halfday' => 'Half Day',
            'wfh' => 'Work From Home',
        ];
        
        return $types[$this->leave_type] ?? $this->leave_type;
    }

    /**
     * Get the status class for UI
     */
    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'approved' => 'status-approved',
            'rejected' => 'status-rejected',
            default => 'status-pending',
        };
    }

    /**
     * Get the leave type class for UI
     */
    public function getLeaveTypeClassAttribute(): string
    {
        return match($this->leave_type) {
            'casual' => 'leave-casual',
            'sick' => 'leave-sick',
            'paid' => 'leave-paid',
            'emergency' => 'leave-emergency',
            'halfday' => 'leave-halfday',
            'wfh' => 'leave-wfh',
            default => '',
        };
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope for filtering by leave type
     */
    public function scopeLeaveType($query, $type)
    {
        if ($type) {
            return $query->where('leave_type', $type);
        }
        return $query;
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        if ($from && $to) {
            return $query->whereBetween('from_date', [$from, $to])
                        ->orWhereBetween('to_date', [$from, $to]);
        }
        return $query;
    }

    /**
     * Add a timeline entry
     */
    public function addTimelineEntry(string $action, string $icon = 'fas fa-info-circle'): void
    {
        $timeline = $this->timeline ?? [];
        $timeline[] = [
            'date' => now()->format('Y-m-d H:i:s'),
            'action' => $action,
            'icon' => $icon,
        ];
        
        $this->timeline = $timeline;
        $this->save();
    }
     protected static function booted()
    {
        static::creating(function ($leave) {
            if (auth()->check()) {
                $leave->company_id = auth()->user()->company_id;
            }
        });
    }
}