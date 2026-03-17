<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Display a listing of the users, excluding the Super Admin.
     */
    public function index()
    {
        // Filter out the Super Admin by email to hide it from the management table
        $users = User::where('email', '!=', 'admin@gmail.com')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created user (from the "New Account" modal).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    /**
     * Display the specified user (useful for the View Modal).
     */
    public function show(User $user)
    {
        // Check if trying to view super admin manually via URL
        if ($user->email === 'admin@gmail.com') {
            abort(403, 'Unauthorized access to Super Admin profile.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Update the specified user in storage (from the Edit Modal).
     */
    public function update(Request $request, User $user)
    {
        // Security check: Prevent editing the Super Admin account
        if ($user->email === 'admin@gmail.com') {
            return redirect()->route('users.index')->with('error', 'Cannot modify Super Admin account.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:8', 'confirmed'], 
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Only update password if the user actually typed a new one
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the specified user from storage (from the Delete Modal).
     */
    public function destroy(User $user)
    {
        // Security check: Prevent deleting the Super Admin or yourself
        if ($user->email === 'admin@gmail.com') {
            return redirect()->route('users.index')->with('error', 'The Super Admin account cannot be deleted.');
        }

        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Security Alert: You cannot delete your own account while logged in.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Account has been permanently removed.');
    }
}