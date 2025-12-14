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
        $token = $request->cookie('access_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return redirect()->route('login')
                ->withCookie(cookie()->forget('access_token'));
        }

        Auth::login($accessToken->tokenable);

        return $next($request);
    }
}
