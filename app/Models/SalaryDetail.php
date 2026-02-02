<?php

// SalaryDetail.php Model
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    protected $fillable = ['salary_id', 'type', 'description', 'amount'];
    
    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}