<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HelpAndSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'client_id',
        'company_id',
        'assigned_agent_id',
        'assigned_team',
        'sla_due_at',
        'resolved_at',
        'conversations', // Store conversations as JSON
        'attachments',   // Store attachments as JSON
        'issue_permissions'
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
        'resolved_at' => 'datetime',
        'conversations' => 'array',
        'attachments' => 'array',
        'issue_permissions' => 'array'
    ];

    /**
     * Relationship with client (User)
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relationship with assigned agent (User)
     */
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    /**
     * Boot method for generating ticket ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_id)) {
                $ticket->ticket_id = 'TKT-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
            
            // Initialize empty arrays if not set
            if (empty($ticket->conversations)) {
                $ticket->conversations = [];
            }
            if (empty($ticket->attachments)) {
                $ticket->attachments = [];
            }
        });
    }

    /**
     * Add conversation to ticket
     */
    public function addConversation($message, $userId, $isInternal = false, $attachments = [])
    {
        $conversations = $this->conversations ?? [];
        
        $conversations[] = [
            'id' => uniqid(),
            'user_id' => $userId,
            'message' => $message,
            'is_internal' => $isInternal,
            'attachments' => $attachments,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        $this->conversations = $conversations;
        return $this->save();
    }

    /**
     * Add attachment to ticket
     */
    public function addAttachment($fileName, $filePath, $fileType, $fileSize)
    {
        $attachments = $this->attachments ?? [];
        
        $attachments[] = [
            'id' => uniqid(),
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'created_at' => now()->toDateTimeString()
        ];

        $this->attachments = $attachments;
        return $this->save();
    }

    /**
     * Get ticket status badge class
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            'open' => 'bg-blue-100 text-blue-800',
            'in-progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get ticket priority badge class
     */
    public function getPriorityBadgeClass()
    {
        $classes = [
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
        ];

        return $classes[$this->priority] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if ticket is overdue
     */
    public function isOverdue()
    {
        return $this->sla_due_at && $this->sla_due_at->isPast() && 
               in_array($this->status, ['open', 'in-progress']);
    }

    /**
     * Get conversations sorted by oldest first (chronological)
     */
    public function getConversationsAttribute($value)
    {
        $conversations = json_decode($value, true) ?? [];
        
        // Sort by created_at ascending (oldest first)
        usort($conversations, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });
        
        return $conversations;
    }

    /**
     * Get attachments
     */
    public function getAttachmentsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
