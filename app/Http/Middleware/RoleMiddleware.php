<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // kalau belum login
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // kalau user tidak punya role (hindari error null)
        if (!$user || !$user->role) {
            abort(403, 'Role tidak ditemukan');
        }

        // cek role
        if ($user->role !== $role) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}