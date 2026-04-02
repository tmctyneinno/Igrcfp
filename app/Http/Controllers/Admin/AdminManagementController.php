<?php
// app/Http/Controllers/Admin/AdminManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    // Remove the __construct method completely
    
    public function index()
    {
        $admins = Admin::with('createdBy')
            ->when(auth()->guard('admin')->user()->isOriginAdmin(), function($query) {
                return $query->where('created_by', auth()->guard('admin')->id())
                            ->orWhere('id', auth()->guard('admin')->id());
            })
            ->latest()
            ->paginate(10);
        
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = $this->getAvailableRoles();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in($this->getAvailableRoles())],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'created_by' => auth()->guard('admin')->id(),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function show(Admin $admin)
    {
        if (!$this->canManageAdmin($admin)) {
            abort(403, 'You do not have permission to view this admin.');
        }

        return view('admin.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        if (!$this->canManageAdmin($admin)) {
            abort(403, 'You do not have permission to edit this admin.');
        }

        $roles = $this->getAvailableRoles();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        if (!$this->canManageAdmin($admin)) {
            abort(403, 'You do not have permission to update this admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'role' => ['required', Rule::in($this->getAvailableRoles())],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === auth()->guard('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if (!$this->canManageAdmin($admin)) {
            abort(403, 'You do not have permission to delete this admin.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user deleted successfully.');
    }

    public function toggleStatus(Admin $admin)
    {
        if (!$this->canManageAdmin($admin)) {
            abort(403, 'You do not have permission to modify this admin.');
        }

        if ($admin->id === auth()->guard('admin')->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $admin->update(['is_active' => !$admin->is_active]);

        return back()->with('success', 'Admin status updated successfully.');
    }

    private function getAvailableRoles()
    {
        $currentAdmin = auth()->guard('admin')->user();
        
        if ($currentAdmin->isSuperAdmin()) {
            return ['super_admin', 'admin', 'moderator'];
        }
        
        if ($currentAdmin->isOriginAdmin()) {
            return ['admin', 'moderator'];
        }
        
        return [];
    }

    private function canManageAdmin(Admin $admin)
    {
        $currentAdmin = auth()->guard('admin')->user();
        
        if ($currentAdmin->isSuperAdmin()) {
            return true;
        }
        
        if ($currentAdmin->isOriginAdmin()) {
            return $admin->created_by === $currentAdmin->id || $admin->id === $currentAdmin->id;
        }
        
        if ($currentAdmin->isAdmin()) {
            return $admin->id === $currentAdmin->id;
        }
        
        return false;
    }
}