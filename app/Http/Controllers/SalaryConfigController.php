<?php

// SalaryConfigController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryConfig;

class SalaryConfigController extends Controller
{
    public function getConfig()
    {
        $config = SalaryConfig::first();
        
        if (!$config) {
            // Create default config if not exists
            $config = SalaryConfig::create([
                'working_days_per_month' => 22,
                'overtime_rate_per_hour' => 0,
                'half_day_percentage' => 50,
                'deduct_for_late' => false,
                'late_deduction_rate' => 0
            ]);
        }
        
        return response()->json([
            'success' => true,
            'config' => $config
        ]);
    }
    
    public function updateConfig(Request $request)
    {
        $request->validate([
            'working_days_per_month' => 'required|integer|min:1|max:31',
            'overtime_rate_per_hour' => 'required|numeric|min:0',
            'half_day_percentage' => 'required|numeric|min:0|max:100',
            'deduct_for_late' => 'required|boolean',
            'late_deduction_rate' => 'required|numeric|min:0|max:100'
        ]);
        
        $config = SalaryConfig::first();
        
        if (!$config) {
            $config = SalaryConfig::create($request->all());
        } else {
            $config->update($request->all());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Configuration updated successfully',
            'config' => $config
        ]);
    }
}