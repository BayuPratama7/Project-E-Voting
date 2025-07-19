<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // Check if user is authenticated and is an admin
        if (!$user || !($user instanceof User) || !$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        // Check if admin account is active
        if (!$user->isActive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account is inactive.'
            ], 403);
        }

        return $next($request);
    }
}
