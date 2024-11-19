<?php

namespace App\Http\Controllers;

use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  // Import Hash for password verification
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showLoginForm()
    {
        return Inertia::render('Login');  // Your custom Vue component
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
        
        // Login and redirect
        Auth::login($studentAccount);
        $request->session()->regenerate();
    
        switch ($studentAccount->role) {
            case 'Admin':
                return redirect()->intended('/admindashboard');
            case 'Facilitator':
                return redirect()->intended('/facidashboard/' . $studentAccount->id);
            case 'College Sport Head':
                return redirect()->intended('/cshdashboard');
            default:
                return redirect()->intended('/login');
        }
    }
    
      

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
