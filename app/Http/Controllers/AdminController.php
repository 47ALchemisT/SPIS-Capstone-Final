<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function createStudent(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|unique:students,id_number',
            'univ_email' => 'required|email|unique:students,univ_email',
            'college' => 'required|string|max:255',
            'contact' => 'required|string|max:20'
        ]);

        // Create new student
        $student = Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'id_number' => $request->id_number,
            'univ_email' => $request->univ_email,
            'college' => $request->college,
            'contact' => $request->contact
        ]);

        Log::info('Student created:', ['student' => $student->toArray()]);
        session(['student' => $student]);

        return redirect()->route('create-admin.step2');
    }

    public function showStep2()
    {
        if (!session()->has('student')) {
            return redirect()->route('create-admin');
        }

        $student = session('student');
        Log::info('Showing step 2 with student:', ['student' => $student]);

        return Inertia::render('Auth/CreateAdminAccount', [
            'student' => $student
        ]);
    }

    public function cancelStudent($id)
    {
        try {
            $student = Student::findOrFail($id);
            $studentData = [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'id_number' => $student->id_number,
                'univ_email' => $student->univ_email,
                'college' => $student->college,
                'contact' => $student->contact
            ];
            
            $student->delete();
            session()->forget('student');
            
            Log::info('Student record cancelled:', ['student_id' => $id]);
            
            return Inertia::render('Auth/CreateAdmin', $studentData);
        } catch (\Exception $e) {
            Log::error('Error cancelling student record:', [
                'student_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('create-admin')->with('error', 'Error cancelling registration');
        }
    }

    public function createAccount(Request $request)
    {
        Log::info('Creating admin account with data:', ['request' => $request->all()]);

        $request->validate([
            'username' => 'required|string|unique:student_accounts,username',
            'password' => ['required', 'confirmed', Password::defaults()],
            'student_id' => 'required|exists:students,id'
        ]);

        // Check if student already has an account
        $existingAccount = StudentAccount::where('student_id', $request->student_id)->first();
        if ($existingAccount) {
            Log::warning('Student already has an account:', ['student_id' => $request->student_id]);
            return back()->withErrors(['message' => 'Student already has an account']);
        }

        try {
            // Create the admin account
            $account = StudentAccount::create([
                'student_id' => $request->student_id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'Admin',
                'status' => 1  // 1 represents active status
            ]);

            Log::info('Admin account created successfully:', ['account' => $account->toArray()]);

            return redirect()->back()->with('success', 'Admin account created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating admin account:', [
                'error' => $e->getMessage(),
                'student_id' => $request->student_id,
                'username' => $request->username
            ]);
        }
    }
}
