<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm($id = null)
    {
        try {
            // If no ID is provided, use the authenticated user
            $account = $id ? StudentAccount::with('student')->findOrFail($id) 
                         : auth()->user()->load('student');
            
            return Inertia::render('Auth/ChangePassword', [
                'account' => [
                    'id' => $account->id,
                    'name' => $account->student->first_name . ' ' . $account->student->last_name,
                    'username' => $account->username,
                    'id_number' => $account->student->id_number,
                    'role' => $account->role
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in showChangePasswordForm', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Unable to load password change form.');
        }
    }

    public function changePassword(Request $request)
    {
        Log::info('Change Password Request received', ['id' => $request->id]);

        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|max:16',
                'new_password_confirmation' => 'required|same:new_password'
            ]);

            // If no ID is provided, use the authenticated user
            $account = $request->has('id') 
                ? StudentAccount::findOrFail($request->id)
                : auth()->user();

            // Verify current password
            if (!Hash::check($request->current_password, $account->password)) {
                return back()->withErrors([
                    'current_password' => 'The current password is incorrect.'
                ])->with('error', 'The current password is incorrect.');
            }

            $account->password = bcrypt($request->new_password);
            $account->save();

            Log::info('Password change successful', ['id' => $account->id]);

            return back()->with([
                'status' => 'success',
                'message' => 'Password has been changed successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in changePassword', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with([
                'status' => 'error',
                'message' => 'Unable to change password. Please try again.'
            ]);
        }
    }
}
