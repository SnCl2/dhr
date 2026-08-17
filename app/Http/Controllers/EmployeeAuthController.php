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
     * Display candidate self-registration view.
     */
    public function showRegister()
    {
        return view('auth.employee-register');
    }

    /**
     * Process candidate self-registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Generate unique Employee ID: EMP-YYYY-XXXX
        $year = date('Y');
        $prefix = "EMP-{$year}-";

        $lastEmployee = Employee::where('employee_id', 'like', $prefix . '%')
            ->orderBy('employee_id', 'desc')
            ->first();

        if ($lastEmployee) {
            $lastNum = (int) substr($lastEmployee->employee_id, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        $employeeId = $prefix . $nextNum;

        // Create the employee with status 'pending_review'
        $employee = Employee::create([
            'employee_id' => $employeeId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'pending_review',
            'is_password_changed' => true, // Already customized by candidate since they register with their own chosen password
        ]);

        return redirect()->route('login')
            ->with('success', "Registration successful! Your generated Employee ID is: {$employeeId}. Please login using this ID and your chosen password.");
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
