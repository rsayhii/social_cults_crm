<?php
// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers\SuperAdmin;


use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Customer;
use App\Models\SuperAdmin\Payment;
use App\Models\SuperAdmin\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    // Show all customers
    public function index()
    {
        // For testing, create some dummy customers if none exist
        if (Customer::count() === 0) {
            // Create customer
            $customer = Customer::create([
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh@example.com',
                'phone' => '+91 9876543210',
                'business_name' => 'Kumar Enterprises',
                'address' => '123 Business Street, Mumbai',
                'status' => 'active',
                'plan' => 'Paid (₹5000/year)',
                'amount_paid' => 5000,
                'license_key' => 'CRM-LIC-' . Str::upper(Str::random(10)),
                'login_url' => 'https://crm.example.com/login/rajesh-kumar',
                'subscription_start_date' => now(),
                'subscription_end_date' => now()->addYear(),
                'payment_method' => 'credit_card'
            ]);
            
            // Create payment record for this customer
            Payment::create([
                'customer_id' => $customer->id,
                'amount' => 5000,
                'total_amount' => 5000,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'notes' => 'Initial subscription payment',
                'currency' => 'INR',
                'payment_date' => now(),
                'transaction_id' => 'PAY-' . Str::upper(uniqid()),
            ]);
        }
        
        $customers = Customer::latest()->paginate(10);
        return view('superadmin.customers.customers', compact('customers'));
    }

    // Show create customer form
    public function create()
    {
        return view('superadmin.customers.create');
    }

    // Store new customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string',
            'business_name' => 'nullable|string',
            'address' => 'nullable|string',
            'subscription_type' => 'required|in:trial,paid',
            'status' => 'required|in:active,trial,pending'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generate license key and login URL
            $licenseKey = 'CRM-LIC-' . Str::upper(Str::random(10));
            $loginUrl = 'https://crm.example.com/login/' . Str::slug($validated['name']);
            
            // Prepare customer data
            $customerData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'business_name' => $validated['business_name'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'license_key' => $licenseKey,
                'login_url' => $loginUrl,
            ];
            
            // Set plan and dates based on subscription type
            if ($request->subscription_type === 'trial') {
                $customerData['plan'] = 'Trial';
                $customerData['trial_start_date'] = now();
                $customerData['trial_end_date'] = now()->addDays(30);
                $customerData['amount_paid'] = 0;
            } else {
                $customerData['plan'] = 'Paid (₹5000/year)';
                $customerData['subscription_start_date'] = now();
                $customerData['subscription_end_date'] = now()->addYear();
                $customerData['amount_paid'] = 5000;
                $customerData['payment_method'] = 'credit_card';
            }
            
            // Create customer
            $customer = Customer::create($customerData);
            
            // If it's a paid subscription, create a payment record
            if ($request->subscription_type === 'paid') {
                $paymentData = [
                    'customer_id' => $customer->id,
                    'amount' => 5000,
                    'total_amount' => 5000,
                    'payment_method' => 'credit_card',
                    'status' => 'completed',
                    'notes' => 'Initial subscription payment for ' . $customer->name,
                    'currency' => 'INR',
                    'payment_date' => now(),
                    'transaction_id' => 'PAY-' . Str::upper(uniqid()),
                ];
                
                // Add subscription_id and payment_type if columns exist
                if (Schema::hasColumn('payments', 'payment_type')) {
                    $paymentData['payment_type'] = 'subscription';
                }
                
                // Create payment
                Payment::create($paymentData);
            }
            
            DB::commit();
            
            return redirect()->route('superadmin.customers.index')
                ->with('success', 'Customer created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating customer: ' . $e->getMessage());
        }
    }

    // Show single customer
    public function show(Customer $customer)
    {
        return view('superadmin.customers.show', compact('customer'));
    }

    // Show edit customer form
    public function edit(Customer $customer)
    {
        return view('superadmin.customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string',
            'business_name' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:active,trial,expired,pending,cancelled',
            'plan' => 'required|string',
            'trial_start_date' => 'nullable|date',
            'trial_end_date' => 'nullable|date',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date',
            'amount_paid' => 'nullable|numeric',
            'payment_method' => 'nullable|string'
        ]);
        
        $customer->update($validated);
        
        return redirect()->route('superadmin.customers.show', $customer)
            ->with('success', 'Customer updated successfully!');
    }

    // Delete customer
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        return redirect()->route('superadmin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}