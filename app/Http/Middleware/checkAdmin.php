<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
} 