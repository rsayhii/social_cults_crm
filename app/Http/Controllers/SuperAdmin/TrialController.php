<?php
// app/Http/Controllers/TrialController.php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Trial;
use App\Models\SuperAdmin\Customer;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    public function index()
    {
        $activeTrials = Customer::where('status', 'trial')
            ->with(['trials' => function($query) {
                $query->where('status', 'active');
            }])
            ->paginate(10);
            
        $trialsStats = [
            'active' => Customer::where('status', 'trial')->count(),
            'converted' => Trial::where('converted_to_paid', true)->count(),
            'expired' => Trial::where('status', 'expired')->count(),
        ];
        
        return view('superadmin.trials', compact('activeTrials', 'trialsStats'));
    }
    
    public function convertToPaid($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update([
            'status' => 'active',
            'plan' => 'Paid (₹5000/year)',
            'amount_paid' => 5000,
            'subscription_start_date' => now(),
            'subscription_end_date' => now()->addYear(),
        ]);
        
        Trial::where('customer_id', $id)->update([
            'converted_to_paid' => true,
            'status' => 'converted'
        ]);
        
        return back()->with('success', 'Trial converted to paid successfully.');
    }
}