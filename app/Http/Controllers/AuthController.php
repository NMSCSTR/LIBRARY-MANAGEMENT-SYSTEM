<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
{
    return view('register');
}

public function register(Request $request)
{
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|unique:users,email',
        'password'       => 'required|string|min:8|confirmed',
        'contact_number' => 'nullable|string|max:20',
        'address'        => 'nullable|string|max:255',
        'role'           => 'required|exists:roles,id',
    ]);

    $user = User::create([
        'name'           => $validated['name'],
        'email'          => $validated['email'],
        'password'       => $validated['password'],
        'contact_number' => $validated['contact_number'] ?? null,
        'address'        => $validated['address'] ?? null,
        'role_id'        => $validated['role'],
    ]);

    // Log registration
    ActivityLog::create([
        'user_id'     => $user->id,
        'action'      => 'register',
        'description' => $user->name . ' registered an account.',
    ]);

    // Auto-login
    Auth::login($user);

    // Redirect based on role
    $role = $user->role->name;
    return match ($role) {
        'admin', 'librarian' => redirect()->route('admin.dashboard'),
        'instructor', 'student' => redirect()->route('borrower.dashboard'),
        default => abort(403, 'Unknown role'),
    };
}



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Log successful login
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'login',
                'description' => Auth::user()->name . ' logged in.',
            ]);

        $role = Auth::user()->role->name;

        return match ($role) {
            'admin', 'librarian' => redirect()->route('admin.dashboard'),
            'instructor', 'student' => redirect()->route('borrower.dashboard'),
            default => abort(403, 'Unknown role'),
        };

        }

        // Log failed login attempt
        ActivityLog::create([
            'user_id'     => null,
            'action'      => 'login_failed',
            'description' => 'Failed login attempt for email: ' . $request->email,
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

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
