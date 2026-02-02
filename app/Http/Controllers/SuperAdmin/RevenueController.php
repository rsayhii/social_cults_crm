<?php
// app/Http/Controllers/RevenueController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\SuperAdmin\Payment;
use App\Models\SuperAdmin\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {
        $yearlyRevenue = Payment::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
            
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
            
        $conversionRate = $this->calculateConversionRate();
        
        $monthlyRevenueData = $this->getMonthlyRevenueData();
        $clientDistribution = $this->getClientDistribution();
        
        return view('superadmin.revenue', compact(
            'yearlyRevenue',
            'monthlyRevenue',
            'conversionRate',
            'monthlyRevenueData',
            'clientDistribution'
        ));
    }
    
    private function calculateConversionRate()
    {
        $totalTrials = Customer::where('status', 'trial')->count();
        $converted = Customer::where('status', 'active')->whereNotNull('subscription_start_date')->count();
        
        return $totalTrials > 0 ? ($converted / $totalTrials) * 100 : 0;
    }
    
    private function getMonthlyRevenueData()
    {
        // Return monthly revenue data for chart
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => [45000, 52000, 67000, 58000, 72000, 85000, 101000, 98000, 112000, 125000, 138000, 145000]
        ];
    }
    
    private function getClientDistribution()
    {
        return [
            'active' => Customer::where('status', 'active')->count(),
            'expired' => Customer::where('status', 'expired')->count(),
            'trial' => Customer::where('status', 'trial')->count(),
        ];
    }
}