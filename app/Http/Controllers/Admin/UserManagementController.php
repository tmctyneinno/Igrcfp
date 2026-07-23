<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class UserManagementController extends Controller
{ 
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $scholarship = $request->get('scholarship'); // New filter
        $perPage = $request->get('per_page', 20);

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('status', 'active');
                } elseif ($status === 'inactive') {
                    $query->where('status', '!=', 'active');
                }
            })
            ->when($scholarship !== null, function ($query) use ($scholarship) {
                $query->where('is_scholarship_applicant', $scholarship);
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.users.index', compact('users', 'search', 'status', 'perPage'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'nullable|string|in:admin,learner,tutor',
            'status' => 'nullable|string|in:active,pending,suspended',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'learner',
            'status' => $request->status ?? 'active',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        // ✅ Load all relationships needed for the show page
        $user->load([
            'enrollments.course',
            'assessmentSubmissions.assessment.course',
            'transactions',
            'completedLessons',
        ]);
        
        return view('admin.users.show', compact('user'));
    }

    public function enrollments(User $user)
    {
        $enrollments = $user->enrollments()
            ->with(['course', 'course.modules'])
            ->latest()
            ->paginate(15);
        
        return view('admin.users.enrollments', compact('user', 'enrollments'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'nullable|string|in:admin,learner,tutor',
            'status' => 'nullable|string|in:active,pending,suspended',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role ?? $user->role,
            'status' => $request->status ?? $user->status,
            'phone' => $request->phone,
            'country' => $request->country,
            'city' => $request->city,
            'bio' => $request->bio,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);
        $status = $user->status === 'active' ? 'activated' : 'deactivated';
        
        return back()->with('success', "User {$status} successfully.");
    }

    // NEW: Toggle Scholarship Status
    public function toggleScholarship(Request $request, User $user)
    {
        $isScholar = $request->has('is_scholarship_applicant');
        
        $user->update([
            'is_scholarship_applicant' => $isScholar
        ]);

        $message = $isScholar 
            ? "Scholarship access granted to {$user->name}." 
            : "Scholarship access revoked from {$user->name}.";

        return back()->with('success', $message);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $userIds = $request->user_ids;

        if (!$userIds) {
            return back()->with('error', 'Please select at least one user.');
        }

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => 'active']);
                $message = 'Selected users activated successfully.';
                break;
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                $message = 'Selected users deactivated successfully.';
                break;
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Selected users deleted successfully.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }
}