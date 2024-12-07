<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm(Request $request)
    {
        Log::info('Showing forgot password form');
        
        // If we have foundUser data from the reset page, pass it back
        if ($request->has('foundUser')) {
            return Inertia::render('Auth/ForgotPassword', [
                'foundUser' => $request->input('foundUser')
            ]);
        }
        
        return Inertia::render('Auth/ForgotPassword');
    }

    public function searchUser(Request $request)
    {
        Log::info('Search User Request received', [
            'id_number' => $request->id_number,
            'method' => $request->method(),
            'ajax' => $request->ajax()
        ]);

        try {
            // Changed validation to accept numeric values
            $validated = $request->validate([
                'id_number' => 'required|numeric'
            ]);

            // Convert to string for database query
            $idNumber = (string) $validated['id_number'];

            // First find the student by ID number
            $student = Student::where('id_number', $idNumber)->first();
            
            if (!$student) {
                return Inertia::render('Auth/ForgotPassword', [
                    'error' => 'No student found with this ID number.'
                ]);
            }

            // Then get their associated student account
            $studentAccount = StudentAccount::where('student_id', $student->id)->first();

            if ($studentAccount->status === false) {
                return Inertia::render('Auth/ForgotPassword', [
                    'error' => 'This account has been deactivated.'
                ]);
            }

            if (!$studentAccount) {
                return Inertia::render('Auth/ForgotPassword', [
                    'error' => 'No account has been created for this student yet.'
                ]);
            }

            return Inertia::render('Auth/ForgotPassword', [
                'foundUser' => [
                    'id' => $studentAccount->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'username' => $studentAccount->username,
                    'role' => $studentAccount->role,
                    'college' => $student->college,
                    'id_number' => $student->id_number,
                    'status' => $studentAccount->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in searchUser', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Auth/ForgotPassword', [
                'error' => 'An error occurred while searching for the account.'
            ]);
        }
    }

    public function showResetForm($id)
    {
        Log::info('Show Reset Form Request received', ['id' => $id]);

        try {
            $studentAccount = StudentAccount::findOrFail($id);
            $student = Student::findOrFail($studentAccount->student_id);

            return Inertia::render('Auth/ResetPassword', [
                'user' => [
                    'id' => $studentAccount->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'username' => $studentAccount->username,
                    'id_number' => $student->id_number
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in showResetForm', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('password.request')
                ->with('error', 'Unable to process password reset request.');
        }
    }

    public function resetPassword(Request $request)
    {
        Log::info('Reset Password Request received', ['id' => $request->id]);

        try {
            $request->validate([
                'id' => 'required',
                'new_password' => 'required|min:8|max:16',
                'new_password_confirmation' => 'required|same:new_password'
            ]);

            $studentAccount = StudentAccount::findOrFail($request->id);
            $studentAccount->password = bcrypt($request->new_password);
            $studentAccount->save();

            Log::info('Password reset successful', ['id' => $request->id]);

            return back()->with([
                'success' => true,
                'message' => 'Password has been reset successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in resetPassword', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with([
                'error' => 'Unable to reset password. Please try again.'
            ]);
        }
    }
}
