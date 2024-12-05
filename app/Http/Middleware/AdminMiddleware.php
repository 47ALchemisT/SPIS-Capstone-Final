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
        $user = Auth::user();
        $currentBrowserSession = $request->session()->getId();
        
        // Verify user role and browser session
        if (!$user || 
            $user->role !== 'Admin' || 
            session('browser_session') !== $currentBrowserSession) {
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('error', 'Please login as administrator.');
        }

        return $next($request);
    }
}
