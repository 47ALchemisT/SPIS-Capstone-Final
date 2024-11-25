<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCommitteeHead
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated with the committee_head guard
        if (Auth::guard('committee_head')->check() && Auth::guard('committee_head')->user()->role === 'College Sport Head') {
            return $next($request);
        }

        // If not authenticated with committee_head guard but authenticated with another guard, allow access
        if (Auth::guard('admin')->check() || Auth::guard('facilitator')->check()) {
            return $next($request);
        }

        return redirect('/');
    }
}
