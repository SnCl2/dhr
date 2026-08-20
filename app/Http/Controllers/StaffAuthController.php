<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class StaffAuthController extends Controller
{
    /**
     * Display the staff login view.
     */
    public function showLogin()
    {
        if (Auth::guard('staff')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.staff-login');
    }

    /**
     * Process the staff login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('staff')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var Staff $staff */
            $staff = Auth::guard('staff')->user();

            if (!$staff->is_password_changed) {
                return redirect()->route('staff.password.change')
                    ->with('warning', 'You must change your password on your first login.');
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Logged in successfully as Staff.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the staff out.
     */
    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show first-time password change form.
     */
    public function showPasswordChange()
    {
        /** @var Staff $staff */
        $staff = Auth::guard('staff')->user();
        if (!$staff) {
            return redirect()->route('staff.login');
        }

        if ($staff->is_password_changed) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.staff-password-change');
    }

    /**
     * Update the password for first-time staff.
     */
    public function updatePassword(Request $request)
    {
        /** @var Staff $staff */
        $staff = Auth::guard('staff')->user();
        if (!$staff) {
            return redirect()->route('staff.login');
        }

        if ($staff->is_password_changed) {
            return redirect()->route('admin.dashboard');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $staff->password = Hash::make($request->password);
        $staff->is_password_changed = true;
        $staff->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Password changed successfully! Welcome to the dashboard.');
    }
}
