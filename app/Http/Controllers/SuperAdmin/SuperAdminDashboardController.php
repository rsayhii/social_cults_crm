<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Customer;
use App\Models\SuperAdmin\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $activeTrials = Customer::where('status', 'trial')->count();
        $paidMembers = Customer::where('status', 'active')->count();
        
        $totalRevenue = Payment::where('status', 'completed')->sum('total_amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
        
        $recentCustomers = Customer::latest()->take(5)->get();
        
        // Get dynamic data for charts
        $revenueData = $this->getRevenueData();
        $customerGrowthData = $this->getCustomerGrowthData();
        
        return view('superadmin.dashboard', compact(
            'totalCustomers',
            'activeTrials',
            'paidMembers',
            'totalRevenue',
            'monthlyRevenue',
            'recentCustomers',
            'revenueData',
            'customerGrowthData'
        ));
    }
    
    /**
     * Get revenue data for last 12 months
     */
    private function getRevenueData()
    {
        $revenueData = [];
        $months = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M');
            $months[] = $month;
            
            $revenue = Payment::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');
            
            $revenueData[] = $revenue;
        }
        
        return [
            'labels' => $months,
            'data' => $revenueData
        ];
    }
    
    /**
     * Get customer growth data for last 12 months
     */
    private function getCustomerGrowthData()
    {
        $growthData = [];
        $months = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M');
            $months[] = $month;
            
            $customers = Customer::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $growthData[] = $customers;
        }
        
        return [
            'labels' => $months,
            'data' => $growthData
        ];
    }
}