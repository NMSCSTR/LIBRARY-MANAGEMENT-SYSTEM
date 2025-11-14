<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
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

            $role = Auth::user()->role->name;

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                $role = Auth::user()->role->name;

                return match ($role) {
                    'admin'      => redirect()->route('admin.dashboard'),
                    'librarian'  => redirect()->route('librarian.dashboard'),
                    'instructor' => redirect()->route('instructor.dashboard'),
                    'student'    => redirect()->route('student.dashboard'),
                    'donor'    => redirect()->route('donor.dashboard'),
                    default      => abort(403, 'Unknown role')

                };
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('users.login');

    }
}
