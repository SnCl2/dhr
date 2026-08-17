<?php

namespace App\Services;

class DocumentGeneratorService
{
    /**
     * Generate an Offer Letter PDF using FPDF and return the relative path.
     */
    public function generateOfferLetterPdf($employee, $template, $customData)
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
        if ($template->type === 'internal') {
            // Internal layout: Clean, corporate, compact style
            $pdf->SetFillColor(243, 244, 246); // Light gray header strip
            $pdf->Rect(0, 0, 210, 40, 'F');

            $pdf->SetTextColor(76, 29, 149); // Dark purple title
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->Text(20, 22, 'PROPSZY INFOTECH');
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(107, 114, 128);
            $pdf->Text(20, 28, 'Internal Appointment Letter');

            // Draw line
            $pdf->SetDrawColor(139, 92, 246);
            $pdf->SetLineWidth(0.8);
            $pdf->Line(20, 39, 190, 39);

            $pdf->SetY(48);
        } else {
            // External layout: Formal, elegant client-facing style with border frame
            $pdf->SetDrawColor(76, 29, 149); // Purple frame border
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(8, 8, 194, 281);

            // Double header lines
            $pdf->SetY(15);
            $pdf->SetFont('Arial', 'B', 22);
            $pdf->SetTextColor(76, 29, 149);
            $pdf->Cell(0, 10, 'PROPSZY RECRUITMENT SERVICES', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(107, 114, 128);
            $pdf->Cell(0, 4, 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503', 0, 1, 'C');
            $pdf->Cell(0, 4, 'Email: info@propszy.com | Phone: +91 94323 13430', 0, 1, 'C');

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
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->Cell(0, 6, 'Subject: ' . $template->subject, 0, 1);
        $pdf->Ln(5);

        // 5. Replace Placeholders in template content
        $content = $template->content;
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
        $pdf->Cell(0, 5, 'For Propszy Recruitment Agency', 0, 1);
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
            $pdf->SetFillColor(76, 29, 149); // Purple Header Block
            $pdf->Rect(0, 0, 210, 30, 'F');
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Text(15, 15, 'PROPSZY INFOTECH');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Text(15, 22, 'INTERNAL PAYROLL DIVISION | MONTHLY PAYSLIP');
        } else {
            $pdf->SetDrawColor(76, 29, 149);
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(5, 5, 200, 287);

            $pdf->SetTextColor(76, 29, 149);
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->Cell(0, 10, 'PROPSZY RECRUITMENT & STAFFING', 0, 1, 'C');
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
        $pdf->SetFillColor(237, 233, 254);
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
