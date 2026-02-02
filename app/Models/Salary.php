<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Salary extends Model
{
    protected $fillable = [
        'company_id',
        'employee_id',
        'salary_month',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'overtime_amount',
        'net_salary',
        'total_present_days',
        'total_absent_days',
        'total_late_days',
        'total_half_days',
        'per_day_salary',
        'status',
        'payment_date',
        'notes'
    ];
    
    protected $dates = ['salary_month', 'payment_date'];


    protected static function booted()
    {
        static::creating(function ($salary) {
            if (auth()->check()) {
                $salary->company_id = auth()->user()->company_id;
            }
        });
    }
    
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    
    public function details()
    {
        return $this->hasMany(SalaryDetail::class);
    }
    
    // Add this method to calculate salary
   // In Salary.php model
// In Salary.php model
public function calculateSalary($employee, $month, $year)
{
    // Get configuration or use defaults
    $config = SalaryConfig::first();
    
    if (!$config) {
        // Default configuration
        $workingDaysPerMonth = 22;
        $overtimeRatePerHour = 0;
        $halfDayPercentage = 50;
        $deductForLate = false;
        $lateDeductionRate = 0;
    } else {
        $workingDaysPerMonth = $config->working_days_per_month;
        $overtimeRatePerHour = $config->overtime_rate_per_hour;
        $halfDayPercentage = $config->half_day_percentage;
        $deductForLate = $config->deduct_for_late;
        $lateDeductionRate = $config->late_deduction_rate;
    }
    
    // Calculate date range for the month
    $startDate = Carbon::create($year, $month, 1);
    $endDate = $startDate->copy()->endOfMonth();
    
    // Get attendance records for the month - FIXED: using 'employee_id' instead of 'user_id'
    $attendances = MyAttendance::where('employee_id', $employee->id)
        ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
        ->get();
    
    // If the 'date' column doesn't exist, try 'created_at'
    if ($attendances->isEmpty()) {
        $attendances = MyAttendance::where('employee_id', $employee->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();
    }
    
    // Check if employee has ANY attendance records for the month
    if ($attendances->isEmpty()) {
        // Create salary record with 0 values for employees with no attendance
        $salary = $this->create([
            'employee_id' => $employee->id,
            'salary_month' => $startDate->format('Y-m-d'),
            'basic_salary' => $employee->salary,
            'per_day_salary' => $employee->salary / $workingDaysPerMonth,
            'total_present_days' => 0,
            'total_late_days' => 0,
            'total_half_days' => 0,
            'total_absent_days' => $workingDaysPerMonth, // All days considered absent
            'total_allowances' => 0,
            'total_deductions' => 0,
            'overtime_amount' => 0,
            'net_salary' => 0, // Salary will be 0 for no attendance
            'status' => 'pending',
        ]);
        
        // Load the employee relationship
        $salary->load('employee');
        return $salary;
    }
    
    // Count attendance types based on your MyAttendance model status values
    // First, let's debug what status values actually exist
    $statusCounts = $attendances->groupBy('status')->map->count();
    
    // Adjust these based on your actual status values
    // Common status values might be: 'Present', 'Absent', 'Late', 'Half Day', etc.
    $presentDays = 0;
    $lateDays = 0;
    $halfDays = 0;
    $absentDays = 0;
    
    foreach ($attendances as $attendance) {
        $status = strtolower(trim($attendance->status));
        
        if (str_contains($status, 'present')) {
            $presentDays++;
        } elseif (str_contains($status, 'late')) {
            $lateDays++;
        } elseif (str_contains($status, 'half') || str_contains($status, 'half_day')) {
            $halfDays++;
        } elseif (str_contains($status, 'absent')) {
            $absentDays++;
        } else {
            // If status is empty or unknown, count as absent
            $absentDays++;
        }
    }
    
    // Calculate per day salary
    $perDaySalary = $employee->salary / $workingDaysPerMonth;
    
    // Calculate base salary for present days
    $presentSalary = $presentDays * $perDaySalary;
    
    // Calculate salary for half days (percentage of daily salary based on config)
    $halfDaySalary = $halfDays * ($perDaySalary * ($halfDayPercentage / 100));
    
    // Calculate salary for late days
    $lateSalary = $lateDays * $perDaySalary;
    if ($deductForLate) {
        $lateSalary = $lateDays * ($perDaySalary * (1 - $lateDeductionRate / 100));
    }
    
    // Calculate overtime - check if overtime_seconds exists in your model
    $totalOvertimeSeconds = 0;
    $overtimeAmount = 0;
    
    if (isset($attendances->first()->overtime_seconds)) {
        $totalOvertimeSeconds = $attendances->sum('overtime_seconds');
        $overtimeHours = $totalOvertimeSeconds / 3600;
        $overtimeAmount = $overtimeHours * $overtimeRatePerHour;
    }
    
    // Calculate allowances (you can customize these)
    $baseSalary = $presentSalary + $halfDaySalary + $lateSalary;
    
    // Only calculate allowances if there are working days
    $housingAllowance = 0;
    $transportAllowance = 0;
    $medicalAllowance = 0;
    $totalAllowances = 0;
    
    if ($baseSalary > 0) {
        $housingAllowance = $baseSalary * 0.10; // 10% housing
        $transportAllowance = 1000; // Fixed transport
        $medicalAllowance = 500; // Fixed medical
        $totalAllowances = $housingAllowance + $transportAllowance + $medicalAllowance;
    }
    
    // Calculate deductions (only if there are working days)
    $tax = 0;
    $providentFund = 0;
    $totalDeductions = 0;
    
    if ($baseSalary > 0) {
        $tax = $baseSalary * 0.05; // 5% tax
        $providentFund = $baseSalary * 0.08; // 8% PF
        $totalDeductions = $tax + $providentFund;
    }
    
    // Calculate net salary
    $netSalary = $baseSalary + $totalAllowances + $overtimeAmount - $totalDeductions;
    
    // Save salary record
    $salary = $this->create([
        'employee_id' => $employee->id,
        'salary_month' => $startDate->format('Y-m-d'),
        'basic_salary' => $employee->salary,
        'per_day_salary' => $perDaySalary,
        'total_present_days' => $presentDays,
        'total_late_days' => $lateDays,
        'total_half_days' => $halfDays,
        'total_absent_days' => $absentDays,
        'total_allowances' => $totalAllowances,
        'total_deductions' => $totalDeductions,
        'overtime_amount' => $overtimeAmount,
        'net_salary' => $netSalary,
        'status' => 'pending'
    ]);
    
    // Save salary breakdown details only if there are working days
    if ($baseSalary > 0) {
        // Clear existing details if any
        $salary->details()->delete();
        
        // Add allowance details
        if ($housingAllowance > 0) {
            $salary->details()->create([
                'type' => 'allowance',
                'description' => 'Housing Allowance',
                'amount' => $housingAllowance
            ]);
        }
        
        if ($transportAllowance > 0) {
            $salary->details()->create([
                'type' => 'allowance',
                'description' => 'Transport Allowance',
                'amount' => $transportAllowance
            ]);
        }
        
        if ($medicalAllowance > 0) {
            $salary->details()->create([
                'type' => 'allowance',
                'description' => 'Medical Allowance',
                'amount' => $medicalAllowance
            ]);
        }
        
        // Add deduction details
        if ($tax > 0) {
            $salary->details()->create([
                'type' => 'deduction',
                'description' => 'Income Tax',
                'amount' => $tax
            ]);
        }
        
        if ($providentFund > 0) {
            $salary->details()->create([
                'type' => 'deduction',
                'description' => 'Provident Fund',
                'amount' => $providentFund
            ]);
        }
        
        // Add overtime if any
        if ($overtimeAmount > 0) {
            $salary->details()->create([
                'type' => 'allowance',
                'description' => 'Overtime Payment',
                'amount' => $overtimeAmount
            ]);
        }
    }
    
    // Load the employee relationship
    $salary->load('employee');
    
    return $salary;
}

}