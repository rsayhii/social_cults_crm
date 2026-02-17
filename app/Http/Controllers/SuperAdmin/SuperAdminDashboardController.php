<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SuperAdmin\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Total companies (customers)
        $totalCustomers = Company::count();
        
        // Active trials: companies with trial_ends_at in the future
        $activeTrials = Company::where('trial_ends_at', '>', now())
            ->where('is_paid', false)
            ->count();
        
        // Paid members: companies with is_paid = true
        $paidMembers = Company::where('is_paid', true)->count();
        
        // Revenue data
        $totalRevenue = Payment::where('status', 'completed')->sum('total_amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        
        // Recent customers (companies)
        $recentCustomers = Company::with('user')->latest()->take(5)->get();
        
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
            
            $customers = Company::whereMonth('created_at', $date->month)
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