<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class LogAuthDebug
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Auth debug', [
            'cookies' => array_keys($request->cookies->all()),
            'has_laravel_token' => $request->hasCookie('laravel_token'),
            'laravel_token' => $request->cookie('laravel_token') ? 'present' : 'missing',
            'bearer_token' => $request->bearerToken() ? 'present' : 'missing',
            'auth_user' => auth()->user()?->id ?? 'unauthenticated',
        ]);

        return $next($request);
    }
}