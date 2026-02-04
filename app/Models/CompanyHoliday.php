<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'date',
        'category',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
