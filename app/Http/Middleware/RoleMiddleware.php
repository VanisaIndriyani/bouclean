<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan $roles adalah array yang bersih (handle jika dikirim sebagai string admin,user)
        $allowedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, explode(',', $role));
            } else {
                $allowedRoles[] = $role;
            }
        }

        $allowedRoles = array_values(array_filter(array_map(static fn ($r) => trim((string) $r), $allowedRoles)));
        $userRole = $request->user() ? trim((string) $request->user()->role) : null;

        if (! $request->user() || ! in_array($userRole, $allowedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            if ($request->user() && in_array($request->user()->role, ['admin', 'user', 'warga'])) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
