<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Show the user profile
    public function viewProfile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    // Show the edit profile form
    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.profile-edit', compact('user'));
    }

    // Update the user profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'username' => 'required|string|max:255|unique:userlogin,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:userlogin,email,' . $user->id,
        ]);

       $user->update([
    'username' => $request->username,
    'email' => $request->email,
]);

        return redirect()->route('profile.view')->with('success', 'Profile updated successfully!');
    }
}
