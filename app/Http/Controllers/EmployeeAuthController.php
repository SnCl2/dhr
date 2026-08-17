<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeAuthController extends Controller
{
    /**
     * Display the employee login view.
     */
    public function showLogin()
    {
        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }

        return view('auth.employee-login');
    }

    /**
     * Process the employee login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'employee_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Retrieve the employee to check their status before attempting login
        $employee = Employee::where('employee_id', $credentials['employee_id'])->first();

        if (!$employee) {
            return back()->withErrors([
                'employee_id' => 'This Employee ID is not registered in our system.',
            ])->onlyInput('employee_id');
        }

        if (in_array($employee->status, ['inactive', 'terminated'])) {
            return back()->withErrors([
                'employee_id' => 'Your account is deactivated or terminated. Please contact support.',
            ])->onlyInput('employee_id');
        }

        // Attempt authentication
        $loginAttempt = Auth::guard('employee')->attempt([
            'employee_id' => $credentials['employee_id'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if ($loginAttempt) {
            $request->session()->regenerate();

            // Redirect based on whether they changed their password yet
            if (!$employee->is_password_changed) {
                return redirect()->route('employee.password.change')
                    ->with('warning', 'Please customize your temporary password to secure your account.');
            }

            return redirect()->intended(route('employee.dashboard'))
                ->with('success', 'Welcome back, ' . $employee->first_name . '!');
        }

        return back()->withErrors([
            'password' => 'The provided password does not match our records.',
        ]);
    }

    /**
     * Display forced password change view.
     */
    public function showPasswordChange()
    {
        return view('auth.employee-password-change');
    }

    /**
     * Process forced password change.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $employee = Auth::guard('employee')->user();
        
        // Update password and flag
        $employee->password = Hash::make($request->password);
        $employee->is_password_changed = true;
        $employee->save();

        return redirect()->route('employee.dashboard')
            ->with('success', 'Your password has been successfully updated.');
    }

    /**
     * Log the employee out.
     */
    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
