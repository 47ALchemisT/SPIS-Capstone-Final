<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::where('status', 'active')->get();
        $studentAccounts = StudentAccount::with('student')->where('status', true)->whereIn('role', ['Facilitator', 'Sub Admin', 'College Sport Head' ])->get();
        return Inertia::render('SSCAdmin/Account',[
            'students' => $students,
            'studentAccounts' => $studentAccounts,

        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'role' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        
        // Check if an account already exists for this student
        $existingAccount = StudentAccount::where('student_id', $validated['student_id'])->first();
        
        if ($existingAccount) {
            // Update the existing account
            $existingAccount->update([
                'role' => $validated['role'],
                'status' => true // Force status to true when reactivating
            ]);
            
            return redirect()->back()->with('success', 'Account reactivated successfully!');
        }
        
        // If no existing account, create a new one
        $student = Student::find($validated['student_id']);
        
        // Extract the first name and generate the username
        $first_name = explode(' ', trim($student->first_name))[0];
        
        // Generate username: only the first name in lowercase
        $username = strtolower($first_name);

        // Generate password: first name + "123"
        $password = strtolower($first_name);
        
        // Hash the password before storing it
        $hashedPassword = bcrypt($password);

        // Create the student account
        StudentAccount::create([
            'student_id' => $validated['student_id'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'username' => $username,
            'password' => $hashedPassword,
        ]);

        return redirect()->back()->with('success', 'Account created successfully!');
    }
    

    public function destroy(StudentAccount $account)
    {
        try {
            $account->delete();
            return redirect()->back()->with('success', 'Account deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete account');
        }
    }
}
