<?php

namespace App\Http\Controllers;

use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showLoginForm()
    {
        $hasAdmin = StudentAccount::where('role', 'Admin')->get();

        return Inertia::render('Login', [
            'hasAdmin' => $hasAdmin
        ]);
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

        // Clear existing session in current browser only
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Start new session
        Auth::login($studentAccount);
        $request->session()->regenerate();
        
        // Store session data with browser identifier
        $browserSessionId = $request->session()->getId();
        session([
            'user_id' => $studentAccount->id,
            'role' => $studentAccount->role,
            'browser_session' => $browserSessionId,
            'login_time' => now()
        ]);

        // Set session lifetime
        config(['session.lifetime' => 10]);

        // Redirect based on role
        switch ($studentAccount->role) {
            case 'Admin':
                return redirect()->intended('/admin');
            case 'Facilitator':
                
                if ($studentAccount->status == 0 || $studentAccount->status === false || $studentAccount->status === '0') {
                    Auth::logout();
                    return back()->withErrors([
                        'message' => 'Your account has been deactivated. Please contact the administrator.'
                    ]);
                }
                $encryptedId = Crypt::encryptString($studentAccount->id);
                return redirect()->intended('/facidashboard/' . $encryptedId);
            case 'College Sport Head':
                Log::info('Student Account Status:', [
                    'status' => $studentAccount->status,
                    'type' => gettype($studentAccount->status)
                ]);
                
                if ($studentAccount->status == 0 || $studentAccount->status === false || $studentAccount->status === '0') {
                    Auth::logout();
                    return back()->withErrors([
                        'message' => 'Your account has been deactivated. Please contact the administrator.'
                    ]);
                }
                $encryptedId = Crypt::encryptString($studentAccount->id);
                return redirect()->intended('/cshdashboard/' . $encryptedId);
            case 'Sub Admin':
                Log::info('Student Account Status:', [
                    'status' => $studentAccount->status,
                    'type' => gettype($studentAccount->status)
                ]);
                
                if ($studentAccount->status == 0 || $studentAccount->status === false || $studentAccount->status === '0') {
                    Auth::logout();
                    return back()->withErrors([
                        'message' => 'Your account has been deactivated. Please contact the administrator.'
                    ]);
                }
                return redirect()->intended('/secretary');
            default:
                return redirect()->intended('/');
        }
    }

    public function logout(Request $request)
    {
        // Clear only current browser session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
