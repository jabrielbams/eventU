<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle Registration Request
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:student,organizer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat registrasi')->withInput();
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return back()->withErrors([
                    'email' => 'Email atau password salah.',
                ])->onlyInput('email');
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect()->intended('dashboard')
                ->withCookie(cookie('access_token', $token, 60 * 24 * 7, '/', null, false, true));
        } catch (Exception $e) {
            return back()->withErrors([
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                'email' => $request->email,
                'error' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $user->tokens()->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->withCookie(cookie()->forget('access_token'));
    }
}
