<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFacilitator
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated with the facilitator guard
        if (Auth::guard('facilitator')->check() && Auth::guard('facilitator')->user()->role === 'Facilitator') {
            return $next($request);
        }

        // If not authenticated with facilitator guard but authenticated with another guard, allow access
        if (Auth::guard('admin')->check() || Auth::guard('committee_head')->check()) {
            return $next($request);
        }

        return redirect('/');
    }
}
