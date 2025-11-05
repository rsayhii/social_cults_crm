<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:role', ['only' => ['index','store']]);
    //     $this->middleware('permission:role_create', ['only' => ['create','store']]);
    //     $this->middleware('permission:role_edit', ['only' => ['edit','update']]);
    //     $this->middleware('permission:role_delete', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.roles',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.addroles',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>'required',
        ]);

        $role = Role::create(["name"=> $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles')->with('success','Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        return view('admin.roles.showroles',compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
         $permissions = Permission::all();
        return view('admin.roles.editroles',compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            "name"=>'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles')->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $role = Role::find($id);
          $role->delete();
        return redirect()->route('roles')->with('success','Role deleted successfully');
    }
}
