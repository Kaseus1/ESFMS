<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users with filtering options
     */
    public function index(Request $request)
    {
        $query = User::query()->where('role', '!=', 'guest'); // Exclude guests

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->withQueryString();

        // Return the USERS view, not the dashboard view
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,faculty,student',
            'status' => 'required|in:pending,approved,rejected',
            'contact_number' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'contact_number' => $request->contact_number,
                'email_verified_at' => now(),
                'approved_by' => $request->status === 'approved' ? auth()->id() : null,
                'approved_at' => $request->status === 'approved' ? now() : null,
                'status_updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create user error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load user's reservations and related data
        $user->load(['reservations' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }, 'approvedBy']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:admin,faculty,student',
            'status' => 'required|in:pending,approved,rejected',
            'contact_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'contact_number' => $request->contact_number,
            ];

            // Handle status changes
            if ($user->status !== $request->status) {
                $updateData['status'] = $request->status;
                $updateData['status_updated_at'] = now();
                
                if ($request->status === 'approved') {
                    $updateData['approved_by'] = auth()->id();
                    $updateData['approved_at'] = now();
                } elseif ($user->status === 'approved') {
                    $updateData['approved_by'] = null;
                    $updateData['approved_at'] = null;
                }
            }

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update user error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin' && auth()->user()->role === 'admin') {
            return back()->with('error', 'You cannot delete another admin account.');
        }

        try {
            DB::beginTransaction();

            if (method_exists($user, 'reservations')) {
                $user->reservations()->delete();
            }

            $name = $user->name;
            $user->delete();

            DB::commit();

            return back()->with('success', "User {$name} has been deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete user error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the user. Please try again.');
        }
    }

    /**
     * Approve a user
     */
    public function approve(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot approve your own account.');
        }

        if ($user->role === 'admin' && auth()->user()->role === 'admin') {
            return back()->with('error', 'You cannot approve another admin account.');
        }

        $user->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'status_updated_at' => now(),
        ]);

        return back()->with('success', "User {$user->name} has been approved successfully.");
    }

    /**
     * Reject a user
     */
    public function reject(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot reject your own account.');
        }

        if ($user->role === 'admin' && auth()->user()->role === 'admin') {
            return back()->with('error', 'You cannot reject another admin account.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'status_updated_at' => now(),
        ]);

        return back()->with('success', "User {$user->name} has been rejected.");
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot reset your own password here. Use profile settings instead.');
        }

        if ($user->role === 'admin' && auth()->user()->role === 'admin') {
            return back()->with('error', 'You cannot reset another admin password.');
        }

        try {
            DB::beginTransaction();

            // Generate a new password or use the one provided
            $newPassword = $request->password ?: strtolower(str_replace(' ', '', $user->name)) . rand(1000, 9999);
            
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            DB::commit();

            return back()->with('success', 
                "Password for {$user->name} has been reset successfully. New password: {$newPassword}"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reset password error: ' . $e->getMessage());
            return back()->with('error', 'Failed to reset password. Please try again.');
        }
    }

    /**
     * Update the status of a single user
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        if ($user->role === 'admin' && auth()->user()->role === 'admin') {
            return back()->with('error', 'You cannot change the status of another admin.');
        }

        $updateData = [
            'status' => $request->status,
            'status_updated_at' => now(),
            'approved_by' => $request->status === 'approved' ? auth()->id() : null,
            'approved_at' => $request->status === 'approved' ? now() : null,
        ];

        $user->update($updateData);

        return back()->with('success', "User {$user->name} has been " . ucfirst($request->status) . ".");
    }

    /**
     * Bulk approve multiple users
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = array_filter($request->user_ids, fn($id) => $id != auth()->id());

        if (empty($userIds)) {
            return back()->with('error', 'You cannot approve your own account.');
        }

        $users = User::whereIn('id', $userIds)
            ->where('role', '!=', 'admin')
            ->where('status', '!=', 'approved')
            ->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'No valid users selected for approval.');
        }

        try {
            DB::beginTransaction();

            $updatedCount = User::whereIn('id', $users->pluck('id'))
                ->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'status_updated_at' => now(),
                ]);

            DB::commit();

            return back()->with('success', "{$updatedCount} user(s) have been approved successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk approve users error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while approving users. Please try again.');
        }
    }

    /**
     * Bulk update status for multiple users (Approve or Reject)
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'status' => 'required|string',
        ]);

        User::whereIn('id', $validated['user_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Selected users updated.');
    }

    /**
     * Bulk delete multiple users
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = array_filter($request->user_ids, fn($id) => $id != auth()->id());

        if (empty($userIds)) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $users = User::whereIn('id', $userIds)
            ->where('role', '!=', 'admin')
            ->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'No valid users selected. Admin accounts cannot be deleted.');
        }

        try {
            DB::beginTransaction();

            foreach ($users as $user) {
                if (method_exists($user, 'reservations')) {
                    $user->reservations()->delete();
                }
            }

            $deletedCount = User::whereIn('id', $users->pluck('id'))->delete();

            DB::commit();

            return back()->with('success', "{$deletedCount} user(s) have been deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk delete users error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting users. Please try again.');
        }
    }
}