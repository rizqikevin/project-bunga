<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('admin/dashboard');
            } else {
                return redirect()->intended('karyawan/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('employee.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}