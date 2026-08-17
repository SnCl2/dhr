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
        
        // ------------------ PAGE 1 ------------------
        $pdf->AddPage();
        $pdf->SetMargins(20, 20, 20);
        
        // Logo
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 20, 15, 30);
        } else {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetTextColor(30, 58, 138);
            $pdf->Text(20, 20, 'RM HR SOLUTIONS');
        }
        
        // Issued Date (right aligned)
        $pdf->SetY(20);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell(0, 10, 'Issued Date: ' . date('d-M-Y'), 0, 1, 'R');
        $pdf->Ln(5);

        // Candidate / Staff Info Block
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->Cell(30, 5, 'Emp Name:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->full_name, 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Emp Code:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->employee_id, 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Designation:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->designation ? $employee->designation->name : 'N/A', 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Mobile no.:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->phone ?? 'N/A', 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Address:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        
        $address = $employee->company ? $employee->company->address : 'NAIKURI, Tamluk, East Medinipur, West Bengal - 721630';
        $pdf->MultiCell(0, 5, $address);
        $pdf->Ln(8);

        // Document Title
        $pdf->SetFont('Arial', 'BU', 12);
        $pdf->Cell(0, 6, 'OFFER CUM APPOINTMENT LETTER', 0, 1, 'C');
        $pdf->Ln(6);

        // Letter Body Intro
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Dear ' . $employee->first_name . ',', 0, 1);
        $pdf->Ln(3);

        $startDate = $customData['joining_date'] ?? ($employee->joining_date ? $employee->joining_date->format('d-m-Y') : date('d-m-Y'));
        $endDate = date('d-m-Y', strtotime($startDate . ' + 1 year'));
        $designationName = $employee->designation ? $employee->designation->name : 'Senior Assistant';
        
        $introText = "Further to your application and subsequent discussion for employment with us, we are pleased to appoint you as {$designationName} effective from {$startDate} to {$endDate} on the following terms & conditions.";
        $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $introText));
        $pdf->Ln(4);

        // Helpers
        $writeSectionHeader = function($pdf, $num, $title) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(8, 5, $num . '.', 0, 0);
            $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $title), 0, 1);
            $pdf->Ln(1);
        };

        $writeBullet = function($pdf, $text) {
            $pdf->SetFont('Arial', '', 9.5);
            $pdf->SetX(28);
            $pdf->Cell(4, 5, chr(149), 0, 0);
            $pdf->SetX(32);
            $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $text));
            $pdf->Ln(2.5);
        };

        // Term 1: Posting
        $writeSectionHeader($pdf, '1', 'POSTING');
        $postingLocation = $employee->company ? $employee->company->name : 'ULUB/BTS';
        $writeBullet($pdf, "We would like you to join the services on immediate basis and your initial posting will be at {$postingLocation}.");

        // Term 2: Duties
        $writeSectionHeader($pdf, '2', 'DUTIES');
        $writeBullet($pdf, "You shall devote your time, attention and ability towards company and shall perform such duties and exercise assigned to you from time to time by the management. You shall also comply with orders, directions, and regulations as laid by the management.");
        $writeBullet($pdf, "Your Services are liable to be transferred/ deputed part or whole time to any company, section, subsidiary or associated concern.");
        $writeBullet($pdf, "You are required to be flexible and to undertake all duties associated with your role. You are also expected to undertake reasonable alternative duties in addition to, or instead of your normal duties. The Management decision in this regard would stand final and abiding.");

        // Term 3: Confidential Information
        $writeSectionHeader($pdf, '3', 'CONFIDENTIAL INFORMATION');
        $writeBullet($pdf, "Any information you obtain from time to time regarding processes, methods, client information, business practice, etc., should be treated as being of the utmost confidential.");

        // Term 4: Service Rules
        $writeSectionHeader($pdf, '4', 'SERVICE RULES, DISCIPLINE and GRIEVANCES');
        $writeBullet($pdf, "During your employment with us, you will not be associated yourself with such activities, as in the opinion of the Management will be harmful or detrimental to the interest of the company.");
        $writeBullet($pdf, "You will be abide the rules and regulations, which are in force and also by any additions and/or the amendments that may be bought into force thereto and rule governing business conduct and secrecy as decided from time to time by the Management.");
        $writeBullet($pdf, "It is understood that this employment is being offered to you on the basis of particulars submitted by you in Application of Employment. However, if any time it should emerge that the details provided by you are false/ incorrect, or if any material or relevant information has been suppressed or concealed, this appointment will be considered ineffective and irregular and would be liable to be terminated immediately without notice after giving you an opportunity, in accordance with the disciplinary action against you for the same.");
        $writeBullet($pdf, "Nothing contained herein constitutes a guarantee of employment. Your performance shall be subject to the appraisal by the company. Company reserve the right to terminate your employment on grounds of performance not being upto expected standards.");
        $writeBullet($pdf, "You will be paid pro rata daily wages only for the days that you report for work. You will not be entitled to any wages for the days that you have not worked, whatsoever the reason be including but not limited to Government restrictions/ civil / social disturbance.");
        $writeBullet($pdf, "You will comply with all the instructions, guidelines or policies, processes or practices of the client on health, safety and security which may be in force from time to time during the tenure of your employment.");

        // Term 5: Period of Services
        $writeSectionHeader($pdf, '5', 'PERIOD OF SERVICES and NOTICE PERIOD PAY');
        $writeBullet($pdf, "During the period of your engagement your services can be terminated by either side by giving 7 days' notice or 7 day pay in lieu thereof at company direction.");

        // ------------------ PAGE 2 ------------------
        $pdf->AddPage();
        
        // Term 5 bullet 2
        $writeBullet($pdf, "In case of notice pay take over, the same will be recovered if you leave the company before completion of the notice period.");
        $pdf->Ln(2);

        // General Policy Body
        $pdf->SetFont('Arial', '', 9.5);
        $bodyPage2_1 = "You are bound to abide by and adhere to the policies, rules, and regulations enforced by the Company from time to time including but not limited to Code of Conduct, Discipline, Business Ethics and Contract of employment. Such policies, rules and regulations may be subjected to alternation and amendment from time to time at the sole discretion of the Company and you shall be covered under them.";
        $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $bodyPage2_1));
        $pdf->Ln(4);

        $bodyPage2_2 = "Please note that upon your acceptance of this offer, this appointment letter shall supersede all prior, oral or written agreements, commitments, understanding or communications either formally or informally, in regards to the subject matter.";
        $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $bodyPage2_2));
        $pdf->Ln(4);

        $bodyPage2_3 = "Any variations of the above terms and conditions will not be valid until expressly made in writing by the company.";
        $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $bodyPage2_3));
        $pdf->Ln(12);

        // For RM HR Solutions Pvt. Ltd.
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, 'For RM HR Solutions Pvt. Ltd.', 0, 1);
        $pdf->Ln(15);
        $pdf->Cell(0, 5, 'Authorized Signatory', 0, 1);
        $pdf->Ln(15);

        // Declaration Block
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, 'DECLARATION', 0, 1);
        $pdf->Ln(2);
        
        $pdf->SetFont('Arial', 'I', 9.5);
        $declarationText = "I have been explained/ read/understood/ the above terms & conditions and agree to abide by them.";
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', $declarationText), 0, 1);
        $pdf->Ln(15);

        // Signatures and Candidate metadata
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(120, 5, '', 0, 0);
        $pdf->Cell(0, 5, 'Signature', 0, 1, 'R');
        $pdf->Ln(8);

        $pdf->Cell(30, 5, 'Emp Name:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->full_name, 0, 1);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Emp Code:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->employee_id, 0, 1);

        // ------------------ PAGE 3 (ANNEXURE) ------------------
        $pdf->AddPage();
        
        // Metadata on page 3
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Designation:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->designation ? $employee->designation->name : 'N/A', 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Mobile no.:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 5, $employee->phone ?? 'N/A', 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 5, 'Address-1:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, $address);
        $pdf->Ln(8);

        // Annexure Title
        $pdf->SetFont('Arial', 'BU', 12);
        $pdf->Cell(0, 6, 'Annexure', 0, 1, 'C');
        $pdf->Ln(6);

        // Table calculations
        $ctc = floatval($customData['salary'] ?? $employee->salary ?? 0);
        $bonus = ($ctc > 5000) ? 500.00 : 0.00;
        
        if ($ctc > 0) {
            $basic = round(($ctc - $bonus) / 1.215721);
            $hra = round($basic * 0.05);
            $gross = $basic + $hra;
            $employerPf = round($basic * 0.13);
            $employerEsic = round($gross * 0.03402);
            $lwf = 0;
            // recalculate ctc to match exactly
            $ctc = $gross + $bonus + $employerPf + $employerEsic;
            
            // Deductions
            $employeePf = round($basic * 0.12);
            $employeeEsic = round($gross * 0.00793);
            $pTax = ($gross > 15000) ? 150 : (($gross > 10000) ? 110 : 0);
            $totalDeductions = $employeePf + $employeeEsic + $lwf + $pTax;
            $netSalary = $gross - $totalDeductions + $bonus;
        } else {
            $basic = 0;
            $hra = 0;
            $gross = 0;
            $employerPf = 0;
            $employerEsic = 0;
            $lwf = 0;
            $employeePf = 0;
            $employeeEsic = 0;
            $pTax = 0;
            $totalDeductions = 0;
            $netSalary = 0;
        }

        // Output table
        $pdf->SetFont('Arial', 'B', 10);
        
        $w1 = 110;
        $w2 = 60;
        
        // Draw Header
        $pdf->Cell($w1, 7, 'EARNING', 1, 0, 'L');
        $pdf->Cell($w2, 7, '', 1, 1);
        
        // BASIC
        $pdf->SetFont('Arial', '', 9.5);
        $pdf->Cell($w1, 6, '  BASIC', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($basic, 2), 1, 1, 'R');
        
        // HRA
        $pdf->Cell($w1, 6, '  HRA', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($hra, 2), 1, 1, 'R');
        
        // CONVEYANCE
        $pdf->Cell($w1, 6, '  CONVEYANCE', 1, 0, 'L');
        $pdf->Cell($w2, 6, '0.00', 1, 1, 'R');
        
        // MEDICAL ALLOWANCE
        $pdf->Cell($w1, 6, '  MEDICAL ALLOWANCE', 1, 0, 'L');
        $pdf->Cell($w2, 6, '0.00', 1, 1, 'R');
        
        // SP ALLOWANCE
        $pdf->Cell($w1, 6, '  SP ALLOWANCE', 1, 0, 'L');
        $pdf->Cell($w2, 6, '0.00', 1, 1, 'R');
        
        // GROSS EARNING (A)
        $pdf->SetFont('Arial', 'B', 9.5);
        $pdf->Cell($w1, 6, '  GROSS EARNING (A)', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($gross, 2), 1, 1, 'R');
        
        // Empty row
        $pdf->Cell($w1, 4, '', 1, 0);
        $pdf->Cell($w2, 4, '', 1, 1);
        
        // BONUS (C)
        $pdf->Cell($w1, 6, '  BONUS (C)', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($bonus, 2), 1, 1, 'R');
        
        // EMPLOYER PF CONTRIBUTION
        $pdf->SetFont('Arial', '', 9.5);
        $pdf->Cell($w1, 6, '    EMPLOYER PF CONTRIBUTION', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($employerPf, 2), 1, 1, 'R');
        
        // EMPLOYER ESIC CONTRIBUTION
        $pdf->Cell($w1, 6, '    EMPLOYER ESIC CONTRIBUTION', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($employerEsic, 2), 1, 1, 'R');
        
        // LWF
        $pdf->Cell($w1, 6, '  LWF', 1, 0, 'L');
        $pdf->Cell($w2, 6, '0.00', 1, 1, 'R');
        
        // CTC (COST TO COMPANY)
        $pdf->SetFont('Arial', 'B', 9.5);
        $pdf->Cell($w1, 6, '  CTC (COST TO COMPANY)', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($ctc, 2), 1, 1, 'R');
        
        // Empty row
        $pdf->Cell($w1, 4, '', 1, 0);
        $pdf->Cell($w2, 4, '', 1, 1);
        
        // DEDUCTION Header
        $pdf->Cell($w1, 6, 'DEDUCTION', 1, 0, 'L');
        $pdf->Cell($w2, 6, '', 1, 1);
        
        // EMPLOYEE PF CONTRIBUTION
        $pdf->SetFont('Arial', '', 9.5);
        $pdf->Cell($w1, 6, '    EMPLOYEE PF CONTRIBUTION', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($employeePf, 2), 1, 1, 'R');
        
        // EMPLOYEE ESIC CONTRIBUTION
        $pdf->Cell($w1, 6, '    EMPLOYEE ESIC CONTRIBUTION', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($employeeEsic, 2), 1, 1, 'R');
        
        // LWF
        $pdf->Cell($w1, 6, '  LWF', 1, 0, 'L');
        $pdf->Cell($w2, 6, '0.00', 1, 1, 'R');
        
        // PROFESSIONAL TAX
        $pdf->Cell($w1, 6, '  PROFESSIONAL TAX', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($pTax, 2), 1, 1, 'R');
        
        // TOTAL DEDUCTIONS (B)
        $pdf->SetFont('Arial', 'B', 9.5);
        $pdf->Cell($w1, 6, '  TOTAL DEDUCTIONS (B)', 1, 0, 'L');
        $pdf->Cell($w2, 6, number_format($totalDeductions, 2), 1, 1, 'R');
        
        // Empty row
        $pdf->Cell($w1, 4, '', 1, 0);
        $pdf->Cell($w2, 4, '', 1, 1);
        
        // NET SALARY (A - B + C)
        $pdf->Cell($w1, 7, '  NET SALARY (A - B + C)', 1, 0, 'L');
        $pdf->Cell($w2, 7, number_format($netSalary, 2), 1, 1, 'R');

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
