<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // 2. Attempt Authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // 3. Success Response
            return response()->json([
                'status' => 'success',
                'redirect' => '/dashboard',
                'message' => 'Login successful.'
            ], 200);
        }

        // 4. Failure Response
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
