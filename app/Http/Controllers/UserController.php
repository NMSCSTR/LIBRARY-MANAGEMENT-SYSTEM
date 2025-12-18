<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $request->session()->regenerate();

        // Role-based redirect
        if (auth()->user()->role->name === 'admin'
            || auth()->user()->role->name === 'librarian') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('borrower.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:6|confirmed',
            'contact_number' => 'nullable|string|max:15',
            'address'        => 'nullable|string|max:255',
        ]);

        $role = Role::where('name', 'borrower')->first();

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'role_id'        => $role->id,
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'register',
            'description' => "New user registered: {$user->name} (ID: {$user->id})",
        ]);

        Auth::login($user);

        return redirect()->route('borrower.dashboard')
            ->with('success', 'Registration successful!');
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('users.login');
    }
}
