<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated with the admin guard
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'Admin') {
            return $next($request);
        }

        return redirect('/');
    }
}
