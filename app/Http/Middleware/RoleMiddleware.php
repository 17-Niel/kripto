<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user sudah login
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Ambil role user (pastikan kolom "role" ada pada tabel users)
        $userRole = $user->role ?? null;

        // Jika role user tidak terdaftar / tidak sesuai
        if (!$userRole || !in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Forbidden: You do not have permission to access this resource.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
