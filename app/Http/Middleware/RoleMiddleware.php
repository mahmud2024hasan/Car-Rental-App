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
     * @param  mixed ...$roles  Allowed roles (admin, customer etc)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // Check if the user is authenticated. If the user is not authenticated, abort with a 403 status code
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // This will convert the roles to lowercase and check if the user's role is in the allowed roles
        $allowedRoles = array_map('strtolower', $roles);

        // Check if the user's role is in the allowed roles
        $userRole = strtolower($user->role->value);

        // If the user's role is not in the allowed roles, abort with a 403 status code
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}

