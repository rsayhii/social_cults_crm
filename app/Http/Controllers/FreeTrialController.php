<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class FreeTrialController extends Controller
{
    /**
     * Show Start Trial Form
     */
    public function create()
    {
        return view('auth.start-trial');
    }

    /**
     * Handle Trial Signup
     */
    public function store(Request $request)
{
    $request->validate([
        // Company
        'company_name'   => 'required|string|unique:companies,name|max:255',
        'address'        => 'required|string|max:1000',
        'company_email'  => 'required|email|unique:companies,email',
        'phone'          => 'required|string|max:20',
        'gstin'          => 'nullable|string|max:20|unique:companies,gstin',

        // Bank
        'bank_name'      => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:30',
        'ifsc_code'      => 'nullable|string|max:15',

        // Admin
        'name'           => 'required|string|max:255',
        'admin_email'    => 'required|email|unique:users,email',
        'password'       => 'required|min:6|confirmed',
    ]);

   DB::transaction(function () use ($request) {

    // Company
    $company = Company::create([
        'name'           => $request->company_name,
        'slug'           => Str::slug($request->company_name) . '-' . uniqid(),
        'address'        => $request->address,
        'email'          => $request->company_email,
        'phone'          => $request->phone,
        'gstin'          => $request->gstin,
        'bank_name'      => $request->bank_name,
        'account_number' => $request->account_number,
        'ifsc_code'      => $request->ifsc_code,
        'trial_ends_at'  => now()->addDays(30),
        'is_paid'        => false,
        'status'         => 'active',
    ]);

    // ✅ Create Default Roles
   $adminRole = \App\Models\Role::where('name', 'admin')
        ->where('company_id', $company->id)
        ->first();

    $clientRole = \App\Models\Role::create([
        'name' => 'Client',
        'company_id' => $company->id,
    ]);

    // Admin user
    $user = User::create([
        'company_id' => $company->id,
        'name'       => $request->name,
        'email'      => $request->admin_email,
        'password'   => Hash::make($request->password),
    ]);

    // ✅ Assign Admin Role
    $user->assignRole($adminRole);

    Auth::login($user);
    });

    return redirect()->route('dashboard')->with('showWelcome', true);
}

}
