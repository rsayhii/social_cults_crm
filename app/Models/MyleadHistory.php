<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyleadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
         'company_id',
        'mylead_id',
        'user_id',
        'changes',
        'response',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    protected static function booted()
{
    static::creating(function ($history) {
        if (auth()->check()) {
            $history->company_id = auth()->user()->company_id;
        }
    });
}

    public function mylead()
    {
        return $this->belongsTo(Mylead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}