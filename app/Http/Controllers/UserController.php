<?php
// Updated UserController with fixes for parameter binding consistency
// Changed edit to use User $user, destroy and update already use User $user
// Added missing imports if needed, but assuming they are there

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // List all users
    public function index()
{
    $users = User::where('company_id', auth()->user()->company_id)->get();
    return view('admin.user.users', compact('users'));
}


    // Show create form
    public function create()
    {

        $roles = Role::forCompany(Auth::user()->company_id)->get();
        return view('admin.user.addusers', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'salary' => 'required|numeric',
        ]);

        $authUser = Auth::user();
        $user = User::create([
            'company_id' => $authUser->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'salary' => $request->salary,
        ]);
        if ($request->filled('roles')) {
            $roles = Role::forCompany($authUser->company_id)
                ->whereIn('id', (array)$request->roles)
                ->get();
            $user->syncRoles($roles);
        }
        return redirect()->route('users')->with('success', 'User created successfully!');
    }

    // Show a specific user
    public function show(User $user)
    {
        $this->authorize('manage', $user);

        return view('admin.user.showusers', compact('user'));
    }

    // Show edit form
    public function edit(User $user)  // Changed to User $user for consistency
    {
         $this->authorize('manage', $user);
        $roles = Role::forCompany(Auth::user()->company_id)->get();
        return view('admin.user.editusers', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',  // Made password optional for updates
            'salary' => 'nullable|numeric',  // Made salary optional if not always updated
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->filled('salary')) {
            $updateData['salary'] = $request->salary;
        }

        $user->update($updateData);

        if ($request->has('roles')) {
            $roles = Role::forCompany(Auth::user()->company_id)
                ->whereIn('id', (array)$request->roles)
                ->get();
            $user->syncRoles($roles);
        }

        return redirect()->route('users')->with('success', 'User updated successfully!');
    }

    // Delete user
   public function destroy(User $user)
{
    $this->authorize('manage', $user);

    if (Auth::id() === $user->id) {
        return redirect()
            ->route('users')
            ->with('error', 'You cannot delete your own account.');
    }

    $user->delete();

    return redirect()
        ->route('users')
        ->with('success', 'User deleted successfully!');
}


}
