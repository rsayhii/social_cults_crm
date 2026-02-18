<?php
// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers\SuperAdmin;


use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\SuperAdmin\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    // Show all customers (Companies)
    public function index()
    {
        $customers = Company::with(['user', 'payments'])->latest()->paginate(10);
        return view('superadmin.customers.customers', compact('customers'));
    }

    // Show create customer form
    public function create()
    {
        return view('superadmin.customers.create');
    }

    // Store new customer (Company + Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Company Details
            'company_name' => 'required|string|max:255|unique:companies,name',
            'business_name' => 'nullable|string|max:255', // Optional, can map to something else or just ignore if not in DB
            'email' => 'required|email|unique:companies,email', // Company Email
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            
            // Admin User Details
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            
            // Subscription
            'subscription_type' => 'required|in:trial,paid',
            'status' => 'required|in:active,trial,pending',
            
            // Payment (if paid)
            'amount_paid' => 'nullable|numeric',
            'payment_method' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            // 1. Create Company
            $companyData = [
                'name' => $validated['company_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                // Slug handled by observer
            ]; 

            if ($request->subscription_type === 'trial') {
                $companyData['trial_ends_at'] = now()->addDays(30);
                $companyData['is_paid'] = false;
            } else {
                $companyData['trial_ends_at'] = null; // Or keep it null/past
                $companyData['is_paid'] = true; // Assuming paid subscription means is_paid = true
            }

            $company = Company::create($companyData);

            // 2. Create Roles for the Company
            $adminRole = Role::create(['name' => 'admin', 'company_id' => $company->id]);
            Role::create(['name' => 'employee', 'company_id' => $company->id]);
            Role::create(['name' => 'client', 'company_id' => $company->id]);
            // Add other default roles if needed
            
            // 3. Create Admin User
            $user = User::create([
                'company_id' => $company->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 4. Assign Admin Role
            $user->assignRole($adminRole);

            // 5. Create Payment Record if Paid
            if ($request->subscription_type === 'paid') {
                 Payment::create([
                    'customer_id' => $company->id, // Storing company_id in customer_id column
                    'amount' => $request->amount_paid ?? 5000,
                    'total_amount' => $request->amount_paid ?? 5000,
                    'payment_method' => $request->payment_method ?? 'manual',
                    'status' => 'completed',
                    'notes' => 'Initial subscription payment for ' . $company->name,
                    'currency' => 'INR',
                    'payment_date' => now(),
                    'transaction_id' => 'PAY-' . Str::upper(uniqid()),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('superadmin.customers.index')
                ->with('success', 'Customer (Company) created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating customer: ' . $e->getMessage())->withInput();
        }
    }

    // Show single customer (Company)
    public function show($id)
    {
         $id = decrypt($id);
        $customer = Company::with(['user', 'invoices'])->findOrFail($id); // Eager load relations
        // We pass it as $customer to keep view variable names compatible mostly
        return view('superadmin.customers.show', compact('customer'));
    }

    // Show edit customer form
    public function edit($id)
    {
         $id = decrypt($id);
        $customer = Company::findOrFail($id);
        return view('superadmin.customers.edit', compact('customer'));
    }

    // Update customer (Company)
    public function update(Request $request, $id)
    {
            $id = decrypt($id);
           
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,trial,expired,pending,cancelled',
            'admin_name' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email|unique:users,email,' . ($company->user->id ?? 'NULL'),
        ]);
        
        $company->update($validated);
        
        // Update admin user if provided
        if ($company->user && ($request->filled('admin_name') || $request->filled('admin_email'))) {
            $adminData = [];
            if ($request->filled('admin_name')) {
                $adminData['name'] = $validated['admin_name'];
            }
            if ($request->filled('admin_email')) {
                $adminData['email'] = $validated['admin_email'];
            }
            $company->user->update($adminData);
        }
        
        return redirect()->route('superadmin.customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    // Delete customer (Company)
    public function destroy($id)
    {
         $id = decrypt($id);
        $company = Company::findOrFail($id);
        
        // Optional: Delete related users, payments, etc?
        // For now, standard delete (soft delete if model has it, or hard delete)
        $company->delete();
        
        return redirect()->route('superadmin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}