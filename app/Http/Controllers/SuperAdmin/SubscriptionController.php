<?php
// app/Http/Controllers/SubscriptionController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\SuperAdmin\Subscription;
use App\Models\SuperAdmin\Customer;
use App\Models\SuperAdmin\Payment;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubscriptionController extends Controller
{
    // public function index()
    // {
    //     // Get active subscriptions with their customers
    //     $activeSubscriptions = Customer::where('status', 'active')
    //         ->whereNotNull('subscription_start_date')
    //         ->whereNotNull('subscription_end_date')
    //         ->where('subscription_end_date', '>=', now())
    //         ->with(['subscriptions' => function($query) {
    //             $query->where('status', 'active');
    //         }])
    //         ->paginate(10);
            
    //     // Calculate stats correctly
    //     $activeCustomersCount = Customer::where('status', 'active')
    //         ->whereNotNull('subscription_start_date')
    //         ->whereNotNull('subscription_end_date')
    //         ->where('subscription_end_date', '>=', now())
    //         ->count();
            
    //     // Get subscription payments for MRR calculation
    //     $subscriptionPayments = Payment::whereIn('payment_type', ['subscription', 'renewal'])
    //         ->where('status', 'completed')
    //         ->get();
        
    //     $subscriptionStats = [
    //         'total' => $activeCustomersCount,
    //         'mrr' => $subscriptionPayments->sum('total_amount'),
    //         'avg_length' => Subscription::where('status', 'active')->avg('days_remaining') ?? 0,
    //     ];
        
    //     return view('superadmin.subscriptions', compact('activeSubscriptions', 'subscriptionStats'));
    // }
    
    // /**
    //  * Show form to create new subscription
    //  */
    // public function create()
    // {
    //     $customers = Customer::active()->get();
    //     return view('superadmin.subscriptions.create', compact('customers'));
    // }
    
    // /**
    //  * Store a new subscription for a customer
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'customer_id' => 'required|exists:customers,id',
    //         'plan_name' => 'required|string|max:255',
    //         'price' => 'required|numeric|min:0',
    //         'billing_cycle' => 'required|in:monthly,yearly',
    //         'payment_method' => 'required|in:credit_card,bank_transfer,upi,cash,cheque,online,wallet',
    //         'transaction_id' => 'nullable|string|unique:payments,transaction_id',
    //     ]);
        
    //     try {
    //         DB::beginTransaction();
            
    //         $customer = Customer::findOrFail($request->customer_id);
            
    //         // Calculate end date based on billing cycle
    //         $startDate = now();
    //         if ($request->billing_cycle === 'monthly') {
    //             $endDate = now()->addMonth();
    //             $daysRemaining = 30;
    //         } else {
    //             $endDate = now()->addYear();
    //             $daysRemaining = 365;
    //         }
            
    //         // Create subscription record
    //         $subscription = Subscription::create([
    //             'customer_id' => $customer->id,
    //             'plan_name' => $request->plan_name,
    //             'price' => $request->price,
    //             'billing_cycle' => $request->billing_cycle,
    //             'start_date' => $startDate,
    //             'end_date' => $endDate,
    //             'days_remaining' => $daysRemaining,
    //             'status' => 'active',
    //             'auto_renewal' => true,
    //         ]);
            
    //         // Create payment record for the subscription
    //         $payment = Payment::create([
    //             'customer_id' => $customer->id,
    //             'subscription_id' => $subscription->id,
    //             'amount' => $request->price,
    //             'total_amount' => $request->price,
    //             'payment_method' => $request->payment_method,
    //             'status' => 'completed',
    //             'payment_type' => 'subscription',
    //             'transaction_id' => $request->transaction_id ?? 'SUB-' . strtoupper(uniqid()),
    //             'notes' => 'Subscription purchase for ' . $request->plan_name,
    //             'currency' => 'INR',
    //             'payment_date' => now(),
    //         ]);
            
    //         // Update customer subscription dates
    //         $customer->update([
    //             'subscription_start_date' => $startDate,
    //             'subscription_end_date' => $endDate,
    //         ]);
            
    //         DB::commit();
            
    //         return redirect()->route('subscriptions.index')
    //             ->with('success', 'Subscription created and payment recorded successfully!');
                
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Error creating subscription: ' . $e->getMessage());
    //     }
    // }
    
    // public function renew($id)
    // {
    //     try {
    //         DB::beginTransaction();
            
    //         $subscription = Subscription::findOrFail($id);
    //         $customer = $subscription->customer;
            
    //         // Calculate new end date
    //         if ($subscription->billing_cycle === 'monthly') {
    //             $newEndDate = now()->addMonth();
    //             $daysRemaining = 30;
    //         } else {
    //             $newEndDate = now()->addYear();
    //             $daysRemaining = 365;
    //         }
            
    //         // Update subscription
    //         $subscription->update([
    //             'end_date' => $newEndDate,
    //             'days_remaining' => $daysRemaining,
    //             'start_date' => now(),
    //         ]);
            
    //         // Create payment record for renewal
    //         $payment = Payment::create([
    //             'customer_id' => $customer->id,
    //             'subscription_id' => $subscription->id,
    //             'amount' => $subscription->price,
    //             'total_amount' => $subscription->price,
    //             'payment_method' => 'online',
    //             'status' => 'completed',
    //             'payment_type' => 'renewal',
    //             'transaction_id' => 'REN-' . strtoupper(uniqid()),
    //             'notes' => 'Subscription renewal for ' . $subscription->plan_name,
    //             'currency' => 'INR',
    //             'payment_date' => now(),
    //         ]);
            
    //         // Update customer subscription end date
    //         $customer->update([
    //             'subscription_end_date' => $newEndDate,
    //         ]);
            
    //         DB::commit();
            
    //         return back()->with('success', 'Subscription renewed successfully.');
            
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Error renewing subscription: ' . $e->getMessage());
    //     }
    // }

      public function index()
    {
        // Paid & active companies
        $activeSubscriptions = Company::where('is_paid', true)
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // Stats
        $subscriptionStats = [
            'total' => Company::where('is_paid', true)->count(),

            // Example MRR (static for now, you can replace with invoice sum)
            'mrr' => Company::where('is_paid', true)->count() * 999,

            // Avg length (dummy logic, replace when plan table exists)
            'avg_length' => 12,
        ];

        return view(
            'superadmin.subscriptions',
            compact('activeSubscriptions', 'subscriptionStats')
        );
    }
}