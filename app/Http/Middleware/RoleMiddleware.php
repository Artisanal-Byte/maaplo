<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // This check is primarily for robustness, though usually redundant if 'auth' is first.
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role ?? null;
        if ($userRole) {
            $userRole = strtolower($userRole);
        }

        // Convert provided roles to lowercase for consistent comparison
        $allowedRoles = array_map('strtolower', $roles);

        // Check if the user's role is in the list of allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Permission Denied'], 403);
            }

            return Inertia::render('errors/PermissionDenied')
                ->toResponse($request)
                ->setStatusCode(403);
        }

        return $next($request);
    }
}
