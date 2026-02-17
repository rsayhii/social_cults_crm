<?php
// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $superadmin = auth()->guard('superadmin')->user();
        return view('superadmin.settings', compact('superadmin'));
    }
    
    public function update(Request $request)
    {
        $superadmin = auth()->guard('superadmin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('superadmins')->ignore($superadmin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $superadmin->name = $request->name;
        $superadmin->email = $request->email;

        if ($request->filled('password')) {
            $superadmin->password = Hash::make($request->password);
        }

        $superadmin->save();

        return back()->with('success', 'Settings updated successfully.');
    }
}