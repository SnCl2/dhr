<?php

namespace App\Services;

class DocumentGeneratorService
{
    /**
     * Generate an Offer Letter PDF using FPDF and return the relative path.
     */
    public function generateOfferLetterPdf($employee, $type, $customData)
    {
        // 1. Ensure output directory exists
        $dir = public_path('storage/offer_letters');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileName = 'Offer_Letter_' . $employee->employee_id . '_' . time() . '.pdf';
        $filePath = $dir . '/' . $fileName;

        // 2. Initialize FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(20, 20, 20);

        // 3. Dynamic layout based on type (Internal vs External)
        if ($type === 'internal') {
            // Internal layout: Clean, corporate, compact style
            $pdf->SetFillColor(243, 244, 246); // Light gray header strip
            $pdf->Rect(0, 0, 210, 40, 'F');

            $pdf->SetTextColor(30, 58, 138); // Royal Navy Blue
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->Text(20, 22, 'RMHRSOLUTIONS');
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(107, 114, 128);
            $pdf->Text(20, 28, 'Internal Appointment Letter');

            // Draw line
            $pdf->SetDrawColor(37, 99, 235); // Brand Blue
            $pdf->SetLineWidth(0.8);
            $pdf->Line(20, 39, 190, 39);

            $pdf->SetY(48);
        } else {
            // External layout: Formal, elegant client-facing style with border frame
            $pdf->SetDrawColor(30, 58, 138); // Royal Navy Blue frame border
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(8, 8, 194, 281);

            // Double header lines
            $pdf->SetY(15);
            $pdf->SetFont('Arial', 'B', 22);
            $pdf->SetTextColor(30, 58, 138); // Royal Navy Blue
            $pdf->Cell(0, 10, 'RMHRSOLUTIONS RECRUITMENT SERVICES', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(107, 114, 128);
            $pdf->Cell(0, 4, 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503', 0, 1, 'C');
            $pdf->Cell(0, 4, 'Email: info@rmhrsolutions.in | Phone: +91 94323 13430', 0, 1, 'C');

            $pdf->SetDrawColor(229, 231, 235);
            $pdf->SetLineWidth(0.3);
            $pdf->Line(15, 38, 195, 38);

            $pdf->SetY(45);
        }

        // 4. Content - Date, Subject & Address
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell(0, 5, 'Date: ' . date('d-M-Y'), 0, 1, 'R');
        $pdf->Cell(0, 5, 'Ref ID: ' . $employee->employee_id, 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, 'To,', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, $employee->full_name, 0, 1);
        $pdf->Cell(0, 5, 'Candidate ID: ' . $employee->employee_id, 0, 1);
        if ($employee->phone) {
            $pdf->Cell(0, 5, 'Phone: ' . $employee->phone, 0, 1);
        }
        $pdf->Cell(0, 5, 'Email: ' . $employee->email, 0, 1);
        $pdf->Ln(8);

        // Subject line
        $subject = ($type === 'internal')
            ? 'Appointment Letter for the position of ' . ($employee->designation ? $employee->designation->name : 'Staff')
            : 'Offer of Employment (Contractual Staff)';

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->Cell(0, 6, 'Subject: ' . $subject, 0, 1);
        $pdf->Ln(5);

        // 5. Hardcoded Template Content depending on Type
        if ($type === 'internal') {
            $content = "Dear {full_name},\n\nWe are pleased to offer you the position of {designation} in the {department} department at RMHRSolutions Plotters Private Limited.\n\nYour joining date will be {joining_date}. Your monthly salary will be Rs. {salary}.\n\nPlease sign and return a copy of this letter as confirmation of your acceptance.\n\nWelcome to our team!";
        } else {
            $content = "Dear {full_name},\n\nOn behalf of RMHRSolutions, we are pleased to extend you an offer of employment as a contract staff member for the position of {designation}.\n\nYou will be deployed to one of our client sites starting on {joining_date}. Your monthly gross salary will be Rs. {salary}.\n\nWe look forward to working with you.";
        }

        $placeholders = [
            '{first_name}' => $employee->first_name,
            '{last_name}' => $employee->last_name,
            '{full_name}' => $employee->full_name,
            '{employee_id}' => $employee->employee_id,
            '{department}' => $employee->department ? $employee->department->name : 'N/A',
            '{designation}' => $employee->designation ? $employee->designation->name : 'N/A',
            '{salary}' => number_format($customData['salary'] ?? $employee->salary ?? 0, 2),
            '{joining_date}' => $customData['joining_date'] ?? ($employee->joining_date ? $employee->joining_date->format('d-M-Y') : date('d-M-Y')),
        ];

        foreach ($placeholders as $key => $val) {
            $content = str_replace($key, $val, $content);
        }

        // Render content text paragraphs
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(55, 65, 81);
        
        $paragraphs = explode("\n", $content);
        foreach ($paragraphs as $para) {
            if (trim($para) !== '') {
                $pdf->MultiCell(0, 6, iconv('UTF-8', 'windows-1252//TRANSLIT', trim($para)));
                $pdf->Ln(4);
            }
        }

        // 6. Signatures block
        $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Yours sincerely,', 0, 1);
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, 'For RMHRSolutions', 0, 1);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->Cell(0, 5, 'Authorized Signatory', 0, 1);

        // 7. Output to file
        $pdf->Output('F', $filePath);

        return 'storage/offer_letters/' . $fileName;
    }

    /**
     * Generate a Payslip PDF using FPDF and return the relative path.
     */
    public function generatePayslipPdf($employee, $month, $basic, $allowances, $deductions, $net, $type)
    {
        // Ensure output directory exists
        $dir = public_path('storage/payslips');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileName = 'Payslip_' . $employee->employee_id . '_' . str_replace(' ', '_', $month) . '_' . time() . '.pdf';
        $filePath = $dir . '/' . $fileName;

        // Initialize FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);

        // 1. Header Styles based on Internal vs External
        if ($type === 'internal') {
            $pdf->SetFillColor(30, 58, 138); // Royal Navy Blue Header Block
            $pdf->Rect(0, 0, 210, 30, 'F');
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Text(15, 15, 'RMHRSOLUTIONS');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Text(15, 22, 'INTERNAL PAYROLL DIVISION | MONTHLY PAYSLIP');
        } else {
            $pdf->SetDrawColor(30, 58, 138);
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(5, 5, 200, 287);

            $pdf->SetTextColor(30, 58, 138);
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->Cell(0, 10, 'RMHRSOLUTIONS RECRUITMENT & STAFFING', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(107, 114, 128);
            $pdf->Cell(0, 4, 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503', 0, 1, 'C');
            $pdf->Cell(0, 4, 'Payslip Statement (External Staff)', 0, 1, 'C');
            $pdf->Ln(4);
            $pdf->Line(15, 28, 195, 28);
        }

        $pdf->SetY(35);
        $pdf->SetTextColor(17, 24, 39);

        // Title
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'PAYSLIP FOR THE MONTH OF ' . strtoupper($month), 0, 1, 'C');
        $pdf->Ln(3);

        // 2. Employee Info Table
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(243, 244, 246);
        $pdf->Cell(180, 6, 'EMPLOYEE DETAILS', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 6, 'Employee ID:', 1, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(45, 6, $employee->employee_id, 1, 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 6, 'Employee Name:', 1, 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(45, 6, $employee->full_name, 1, 1);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 6, 'Department:', 1, 0);
        $pdf->Cell(45, 6, $employee->department ? $employee->department->name : 'N/A', 1, 0);
        $pdf->Cell(45, 6, 'Designation:', 1, 0);
        $pdf->Cell(45, 6, $employee->designation ? $employee->designation->name : 'N/A', 1, 1);

        $pdf->Cell(45, 6, 'Joining Date:', 1, 0);
        $pdf->Cell(45, 6, $employee->joining_date ? $employee->joining_date->format('d-M-Y') : 'N/A', 1, 0);
        $pdf->Cell(45, 6, 'Mode:', 1, 0);
        $pdf->Cell(45, 6, strtoupper($type), 1, 1);

        $pdf->Ln(8);

        // 3. Earnings & Deductions Table
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(90, 6, 'EARNINGS / ALLOWANCES', 1, 0, 'L', true);
        $pdf->Cell(90, 6, 'DEDUCTIONS', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(55, 6, 'Basic Salary:', 1, 0);
        $pdf->Cell(35, 6, number_format($basic, 2), 1, 0, 'R');
        $pdf->Cell(55, 6, 'Tax / EPF Deductions:', 1, 0);
        $pdf->Cell(35, 6, number_format($deductions, 2), 1, 1, 'R');

        $pdf->Cell(55, 6, 'Allowances (HRA/DA):', 1, 0);
        $pdf->Cell(35, 6, number_format($allowances, 2), 1, 0, 'R');
        $pdf->Cell(55, 6, 'Other Deductions:', 1, 0);
        $pdf->Cell(35, 6, '0.00', 1, 1, 'R');

        // Total
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(55, 6, 'Total Earnings (A):', 1, 0);
        $pdf->Cell(35, 6, number_format($basic + $allowances, 2), 1, 0, 'R');
        $pdf->Cell(55, 6, 'Total Deductions (B):', 1, 0);
        $pdf->Cell(35, 6, number_format($deductions, 2), 1, 1, 'R');

        $pdf->Ln(8);

        // 4. Net Salary Block
        $pdf->SetFillColor(219, 234, 254); // Light Blue-100 Header Block
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(180, 8, 'NET TAKE-HOME SALARY: Rs. ' . number_format($net, 2), 1, 1, 'C', true);

        // Words representation (Simple static/basic representation or empty space for signatures)
        $pdf->Ln(25);

        // Signatures
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 5, 'Employee Signature', 0, 0, 'C');
        $pdf->Cell(90, 5, 'Manager Signature / Seal', 0, 1, 'C');

        // Output to file
        $pdf->Output('F', $filePath);

        return 'storage/payslips/' . $fileName;
    }
}
