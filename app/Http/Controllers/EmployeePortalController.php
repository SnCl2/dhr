<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OfferLetter;
use App\Models\Payslip;
use App\Models\Bulletin;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeePortalController extends Controller
{
    /**
     * Display the employee self-service dashboard.
     */
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        $bulletins = Bulletin::where('is_active', true)->latest()->take(3)->get();
        
        $stats = [
            'letters_count' => OfferLetter::where('employee_id', $employee->id)->count(),
            'payslips_count' => Payslip::where('employee_id', $employee->id)->count(),
        ];

        return view('employee.dashboard', compact('employee', 'bulletins', 'stats'));
    }

    /**
     * Handle request for profile update.
     * Maps it into an Inquiry with a specific subject for admin action.
     */
    public function requestProfileUpdate(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'request_notes' => ['required', 'string'],
        ]);

        $message = "Employee ID: {$employee->employee_id}\n";
        $message .= "Name: {$employee->full_name}\n";
        $message .= "Requested Phone Update: {$request->phone}\n\n";
        $message .= "Notes: {$request->request_notes}";

        Inquiry::create([
            'name' => $employee->full_name,
            'email' => $employee->email,
            'phone' => $request->phone,
            'subject' => "Profile Update Request: " . $employee->employee_id,
            'message' => $message,
            'status' => 'unread',
        ]);

        return back()->with('success', 'Your profile update request has been submitted to the Admin team for review.');
    }

    /**
     * Display document center.
     */
    public function documents()
    {
        $employee = Auth::guard('employee')->user();
        $offerLetters = OfferLetter::where('employee_id', $employee->id)->with('template')->get();
        $payslips = Payslip::where('employee_id', $employee->id)->latest()->get();

        return view('employee.documents', compact('offerLetters', 'payslips'));
    }

    /**
     * Download offer letter.
     */
    public function downloadOfferLetter(OfferLetter $offerLetter)
    {
        $employee = Auth::guard('employee')->user();

        // Enforce that employee can only download their own letter
        if ($offerLetter->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $path = public_path($offerLetter->pdf_path);
        if (!file_exists($path)) {
            abort(404, 'PDF file not found on disk.');
        }

        return response()->download($path);
    }

    /**
     * Download payslip.
     */
    public function downloadPayslip(Payslip $payslip)
    {
        $employee = Auth::guard('employee')->user();

        // Enforce that employee can only download their own payslip
        if ($payslip->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $path = public_path($payslip->pdf_path);
        if (!file_exists($path)) {
            abort(404, 'PDF file not found on disk.');
        }

        return response()->download($path);
    }

    /**
     * Display all active bulletins.
     */
    public function bulletins()
    {
        $bulletins = Bulletin::where('is_active', true)->latest()->get();
        return view('employee.bulletins', compact('bulletins'));
    }
}
