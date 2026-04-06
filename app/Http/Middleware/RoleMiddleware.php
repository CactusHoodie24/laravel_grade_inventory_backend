<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Debug log for SPA auth
        Log::info('RoleMiddleware hit', [
            'required_role' => $role,
            'user_id' => $request->user()?->id ?? 'unauthenticated',
            'user_role' => $request->user()?->role ?? 'none',
            'cookies' => array_keys($request->cookies->all()),
            'laravel_session' => $request->cookie('laravel_session') ? 'present' : 'missing',
            'XSRF_TOKEN' => $request->cookie('XSRF-TOKEN') ? 'present' : 'missing',
        ]);

        $user = $request->user(); // Laravel session SPA auth

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($user->role !== $role) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
