<?php
// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('superadmin.settings');
    }
    
    public function update(Request $request)
    {
        // Update settings logic here
        return back()->with('success', 'Settings updated successfully.');
    }
}