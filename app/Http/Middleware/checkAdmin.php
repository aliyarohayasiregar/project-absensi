<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role != 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}