<?php

namespace App\Http\Controllers;

use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  // Import Hash for password verification
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showLoginForm()
    {
        $hasAdmin = StudentAccount::where('role', 'Admin')->exists();
        \Log::info('Has admin: ' . ($hasAdmin ? 'true' : 'false')); // Debug log

        return Inertia::render('Login', compact('hasAdmin'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
        ]);
    
        // Fetch user
        $studentAccount = StudentAccount::where('username', $credentials['username'])->first();
    
        // Handle errors in a structured way
        if (!$studentAccount) {
            return back()->withErrors(['username' => 'These username do not match our records.']);
        }
    
        if ($studentAccount->role !== $credentials['role']) {
            return back()->withErrors(['role' => 'The selected role is invalid.']);
        }
    
        if (!Hash::check($credentials['password'], $studentAccount->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
        }
        
        // Set role-specific session with additional security data
        $sessionData = [
            'user_id' => $studentAccount->id,
            'role' => $studentAccount->role,
            'login_time' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];

        switch ($studentAccount->role) {
            case 'Admin':
                $request->session()->put('admin_session', $sessionData);
                break;
            case 'Facilitator':
                $request->session()->put('facilitator_session', $sessionData);
                break;
            case 'College Sport Head':
                $request->session()->put('committee_head_session', $sessionData);
                break;
            case 'Sub Admin':
                $request->session()->put('sub_admin_session', $sessionData);
                break;
        }

        // Login with the web guard
        Auth::login($studentAccount, true);
        
        // Regenerate session ID for security
        $request->session()->regenerate();
        
        // Set a longer session lifetime (30 days in minutes)
        config(['session.lifetime' => 10]);
    
        // Store the active role in session
        $request->session()->put('active_role', $studentAccount->role);
    
        switch ($studentAccount->role) {
            case 'Admin':
                return redirect()->intended('/admin');
            case 'Facilitator':
                $encryptedId = Crypt::encryptString($studentAccount->id);
                return redirect()->intended('/facidashboard/' . $encryptedId);
            case 'College Sport Head':
                $encryptedId = Crypt::encryptString($studentAccount->id);
                return redirect()->intended('/cshdashboard/' . $encryptedId);
            case 'Sub Admin':
                return redirect()->intended('/secretary');
            default:
                return redirect()->intended('/');
        }
    }
    
    public function logout(Request $request)
    {
        // Get the active role from session
        $activeRole = $request->session()->get('active_role');
        
        // Remove the specific role session
        switch ($activeRole) {
            case 'Admin':
                $request->session()->forget('admin_session');
                break;
            case 'Facilitator':
                $request->session()->forget('facilitator_session');
                break;
            case 'College Sport Head':
                $request->session()->forget('committee_head_session');
                break;
        }
        
        // Remove the active role
        $request->session()->forget('active_role');
        
        // Check if any other role sessions exist
        $hasOtherSessions = $request->session()->has('admin_session') || 
                           $request->session()->has('facilitator_session') || 
                           $request->session()->has('committee_head_session');
        
        // If no other active sessions, perform full logout
        if (!$hasOtherSessions) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        
        return redirect('/login');
    }

}
