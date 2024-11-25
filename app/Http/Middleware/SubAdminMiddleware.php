<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAccount;


class SubAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the currently authenticated user
        $student = StudentAccount::find(Auth::id());
        
        // Check if user is a facilitator
        if ($student && $student->role === 'Sub Admin') {
            // Store facilitator session separately
            $request->session()->put('sub_admin_session', true);
            return $next($request);
        }

        // If not authenticated as facilitator but has a valid session for another role, allow access
        if (Auth::check() && 
            ($request->session()->has('admin_session') || 
             $request->session()->has('committee_head_session'))) {
            return $next($request);
        }

        // Redirect to home or dashboard if not authenticated or not a facilitator
        return redirect('/')->with('error', 'Access denied. Only facilitators can access this page.');
    }
}
