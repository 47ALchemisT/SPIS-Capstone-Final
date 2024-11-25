<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAccount;

class ComHeadMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the currently authenticated user
        $student = StudentAccount::find(Auth::id());
        
        // Check if user is a committee head
        if ($student && $student->role === 'College Sport Head') {
            // Store committee head session separately
            $request->session()->put('committee_head_session', true);
            return $next($request);
        }

        // If not authenticated as committee head but has a valid session for another role, allow access
        if (Auth::check() && 
            ($request->session()->has('admin_session') || 
             $request->session()->has('facilitator_session'))) {
            return $next($request);
        }

        // Redirect to home or dashboard if not authenticated or not a committee head
        return redirect('/')->with('error', 'Access denied. Only committee heads can access this page.');
    }
}
