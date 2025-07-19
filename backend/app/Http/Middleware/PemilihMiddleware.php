<?php

namespace App\Http\Middleware;

use App\Pemilih;
use Closure;

class PemilihMiddleware
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

        // Check if user is authenticated and is a pemilih
        if (!$user || !($user instanceof Pemilih)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Pemilih privileges required.'
            ], 403);
        }

        // Check if pemilih account is active
        if ($user->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Account is inactive.'
            ], 403);
        }

        return $next($request);
    }
}
