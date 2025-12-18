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
    | Show Login Form
    |--------------------------------------------------------------------------
    */
    public function showLoginForm()
    {
        return view('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Login User
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (!Auth::attempt($credentials, $remember)) {
            // Log failed login attempt
            ActivityLog::create([
                'user_id'     => null,
                'action'      => 'login_failed',
                'description' => 'Failed login attempt for email: ' . $request->email,
            ]);

            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Log successful login
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'login',
            'description' => Auth::user()->name . ' logged in.',
        ]);

        // Role-based redirect
        $role = Auth::user()->role->name;

        return match ($role) {
            'admin', 'librarian' => redirect()->route('admin.dashboard'),
            'borrower', 'student', 'instructor' => redirect()->route('borrower.dashboard'),
            default => abort(403, 'Unknown role'),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Show Registration Form
    |--------------------------------------------------------------------------
    */
    public function showRegisterForm()
    {
        return view('register'); 
    }

    /*
    |--------------------------------------------------------------------------
    | Register New User
    |--------------------------------------------------------------------------
    */
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

        if (!$role) {
            abort(500, 'Borrower role not found. Please seed roles first.');
        }

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

        return redirect()->route('borrower.dashboard')->with('success', 'Registration successful!');
    }

    /*
    |--------------------------------------------------------------------------
    | Logout User
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'logout',
            'description' => Auth::user()->name . ' logged out.',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('users.login');
    }
}
