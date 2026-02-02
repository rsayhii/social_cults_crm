<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Mylead extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'user_id',
        'client_id',
        'response',
        'next_follow_up',
        'follow_up_time',
        'project_type',
        'status',
    ];
   
   protected $casts = [
        'next_follow_up' => 'date', // or 'datetime' if you store time too
    ];

      protected static function booted()
    {
        static::creating(function ($lead) {
            if (auth()->check()) {
                $lead->company_id = auth()->user()->company_id;
            }
        });
    }
    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}
public function client()
{
    return $this->belongsTo(\App\Models\Client::class, 'client_id');
}

public function histories()
{
    return $this->hasMany(\App\Models\MyleadHistory::class, 'mylead_id');
}
}