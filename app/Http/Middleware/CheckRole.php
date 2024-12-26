<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth()->user()->role !== $role) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return $next($request);
    }
} 