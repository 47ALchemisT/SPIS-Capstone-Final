<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAccount;


class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the currently authenticated user
        $student = StudentAccount::find(Auth::id());
        
        // Check if user is an admin
        if ($student && $student->role === 'Admin') {
            // Store admin session separately
            $request->session()->put('admin_session', true);
            return $next($request);
        }

        // If not authenticated as admin but has a valid session for another role, allow access
        if (Auth::check() && 
            ($request->session()->has('facilitator_session') || 
             $request->session()->has('committee_head_session'))) {
            return $next($request);
        }

        // Redirect to home or dashboard if not authenticated or not an admin
        return redirect('/')->with('error', 'Access denied. Admins can access this page.');
    }
}
