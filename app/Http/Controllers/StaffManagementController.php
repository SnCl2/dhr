<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\Permission;

class StaffManagementController extends Controller
{
    /**
     * Display a listing of management staff members.
     */
    public function index()
    {
        $staffMembers = Staff::with('permissions')->get();
        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.staff.create', compact('permissions'));
    }

    /**
     * Store a newly created staff member in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'],
            'password' => ['required', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_password_changed' => false,
        ]);

        if ($request->has('permissions')) {
            $staff->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Management staff member created successfully.');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff)
    {
        $permissions = Permission::all();
        $assignedPermissionIds = $staff->permissions()->pluck('permissions.id')->toArray();
        
        return view('admin.staff.edit', compact('staff', 'permissions', 'assignedPermissionIds'));
    }

    /**
     * Update the specified staff member in database.
     */
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff,email,' . $staff->id],
            'password' => ['nullable', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $staff->name = $request->name;
        $staff->email = $request->email;

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        if ($request->has('permissions')) {
            $staff->permissions()->sync($request->permissions);
        } else {
            $staff->permissions()->detach();
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Management staff member updated successfully.');
    }

    /**
     * Remove the specified staff member from database.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Management staff member deleted successfully.');
    }
}
