<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // If already authenticated, skip token check
        if (Auth::check()) {
            return $next($request);
        }

        $token = $request->cookie('access_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return redirect()->route('login')
                ->withCookie(cookie()->forget('access_token'));
        }

        // Set user without triggering session regeneration
        Auth::setUser($accessToken->tokenable);

        return $next($request);
    }
}
