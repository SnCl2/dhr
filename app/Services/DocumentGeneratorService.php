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
        $pdf->Cell(0, 5, $employee->designationRelation ? $employee->designationRelation->name : 'N/A', 0, 1);
        
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
        $designationName = $employee->designationRelation ? $employee->designationRelation->name : 'Senior Assistant';
        
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
        $pdf->Cell(0, 5, $employee->designationRelation ? $employee->designationRelation->name : 'N/A', 0, 1);
        
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
    public function generatePayslipPdf($employee, $month, $basic, $allowances, $deductions, $net, $type, $extra = null)
    {
        // Ensure output directory exists
        $dir = public_path('storage/payslips');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileName = 'Payslip_' . $employee->employee_id . '_' . str_replace(' ', '_', $month) . '_' . time() . '.pdf';
        $filePath = $dir . '/' . $fileName;

        // Parse extra parameters (with default fallbacks)
        $workingDays = data_get($extra, 'working_days', 31);
        $netPayableDays = data_get($extra, 'net_payable_days', 31);
        $otDays = data_get($extra, 'ot_days', 0);
        $payMode = data_get($extra, 'pay_mode', 'Bank Transfer');

        $hra = data_get($extra, 'hra', $allowances);
        $medical = data_get($extra, 'medical_allowance', 0.0);
        $special = data_get($extra, 'special_allowance', 0.0);
        $leave = data_get($extra, 'leave_encashment', 0.0);
        $otAllow = data_get($extra, 'ot_allowance', 0.0);

        $ptax = data_get($extra, 'professional_tax', 0.0);
        $pf = data_get($extra, 'provident_fund', $deductions);
        $esic = data_get($extra, 'esic', 0.0);

        $totalEarnings = $basic + $hra + $medical + $special + $leave + $otAllow;
        $totalDeductions = $ptax + $pf + $esic;
        $net = $totalEarnings - $totalDeductions;

        // Initialize FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(false);

        // --- 1. Header Section ---
        // Header Outer border box
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->Rect(15, 15, 180, 30);

        // Vertical divider at X = 50
        $pdf->Line(50, 15, 50, 45);

        // Render Company Logo
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 18, 17, 28);
        } else {
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(15, 25);
            $pdf->Cell(35, 10, 'RM HR Solutions', 0, 0, 'C');
        }

        // Title Block
        $pdf->SetFont('Arial', 'B', 22);
        $pdf->SetXY(50, 18);
        $pdf->Cell(145, 10, 'RM HR Solutions Pvt. Ltd.', 0, 0, 'C');

        // Address Subtitle
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->SetXY(50, 29);
        $pdf->Cell(145, 5, 'Panchla, Panchla, Howrah, 711322 West Bengal, India', 0, 0, 'C');

        // Parse month to Aug'2026 format matching the screenshot
        $formattedMonth = $month;
        try {
            $time = strtotime($month);
            if ($time) {
                $formattedMonth = date("M'Y", $time);
            }
        } catch (\Throwable $e) {}

        // Month Bar Row
        $pdf->Rect(15, 45, 180, 6);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY(15, 45);
        $pdf->Cell(180, 6, 'PAY SLIP For the Month of -' . $formattedMonth, 0, 0, 'C');

        // --- 2. Employee Details Section ---
        // Draw grid outline
        $pdf->Rect(15, 51, 180, 36);

        // Draw vertical division lines matching the colon columns
        $pdf->Line(55, 51, 55, 87);
        $pdf->Line(63, 51, 63, 87);
        $pdf->Line(105, 51, 105, 87);
        $pdf->Line(145, 51, 145, 87);
        $pdf->Line(153, 51, 153, 87);

        // Draw horizontal division lines
        for ($y = 57; $y <= 81; $y += 6) {
            $pdf->Line(15, $y, 195, $y);
        }

        $pdf->SetFont('Arial', '', 8.5);
        // Row 1
        $pdf->SetXY(15, 51); $pdf->Cell(40, 6, '  Working Days', 0, 0, 'L');
        $pdf->SetXY(55, 51); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(63, 51); $pdf->Cell(42, 6, ' ' . $workingDays, 0, 0, 'L');
        $pdf->SetXY(105, 51); $pdf->Cell(40, 6, '  Net Payable Days', 0, 0, 'L');
        $pdf->SetXY(145, 51); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(153, 51); $pdf->Cell(42, 6, ' ' . $netPayableDays, 0, 0, 'L');

        // Row 2
        $pdf->SetXY(15, 57); $pdf->Cell(40, 6, '  Employee ID', 0, 0, 'L');
        $pdf->SetXY(55, 57); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(63, 57); $pdf->Cell(42, 6, ' ' . $employee->employee_id, 0, 0, 'L');
        $pdf->SetXY(105, 57); $pdf->Cell(40, 6, '  OT Days', 0, 0, 'L');
        $pdf->SetXY(145, 57); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(153, 57); $pdf->Cell(42, 6, ' ' . $otDays, 0, 0, 'L');

        // Row 3
        $pdf->SetXY(15, 63); $pdf->Cell(40, 6, '  Employee Name', 0, 0, 'L');
        $pdf->SetXY(55, 63); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(63, 63); $pdf->Cell(42, 6, ' ' . $employee->full_name, 0, 0, 'L');
        $pdf->SetXY(105, 63); $pdf->Cell(40, 6, '  Pay Mode', 0, 0, 'L');
        $pdf->SetXY(145, 63); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(153, 63); $pdf->Cell(42, 6, ' ' . $payMode, 0, 0, 'L');

        // Row 4
        $pdf->SetXY(15, 69); $pdf->Cell(40, 6, '  Joining Date', 0, 0, 'L');
        $pdf->SetXY(55, 69); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(63, 69); $pdf->Cell(42, 6, ' ' . ($employee->joining_date ? $employee->joining_date->format('d-M-Y') : 'N/A'), 0, 0, 'L');
        $pdf->SetXY(105, 69); $pdf->Cell(40, 6, '  Bank Name', 0, 0, 'L');
        $pdf->SetXY(145, 69); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(153, 69); $pdf->Cell(42, 6, ' ' . ($employee->bank_name ?? 'N/A'), 0, 0, 'L');

        // Row 5
        $pdf->SetXY(15, 75); $pdf->Cell(40, 6, '  UAN', 0, 0, 'L');
        $pdf->SetXY(55, 75); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(63, 75); $pdf->Cell(42, 6, ' ' . ($employee->old_uan_number ?? 'N/A'), 0, 0, 'L');
        $pdf->SetXY(105, 75); $pdf->Cell(40, 6, '  Account No.', 0, 0, 'L');
        $pdf->SetXY(145, 75); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(153, 75); $pdf->Cell(42, 6, ' ' . ($employee->bank_account_number ?? 'N/A'), 0, 0, 'L');

        // Row 6
        $pdf->SetXY(15, 81); $pdf->Cell(40, 6, '  ESI No', 0, 0, 'L');
        $pdf->SetXY(55, 81); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(63, 81); $pdf->Cell(42, 6, ' ' . ($employee->old_esic_number ?? 'N/A'), 0, 0, 'L');
        $pdf->SetXY(105, 81); $pdf->Cell(40, 6, '  IFSC Code', 0, 0, 'L');
        $pdf->SetXY(145, 81); $pdf->Cell(8, 6, ':', 0, 0, 'C');
        $pdf->SetXY(153, 81); $pdf->Cell(42, 6, ' ' . ($employee->ifsc_code ?? 'N/A'), 0, 0, 'L');

        // --- 3. Earnings & Deductions Section ---
        // Draw outline enclosing header, details, and totals
        $pdf->Rect(15, 92, 180, 48);

        // Draw vertical division lines from Y=92 (header start) to Y=140 (totals end)
        $pdf->Line(75, 92, 75, 140);
        $pdf->Line(105, 92, 105, 140);
        $pdf->Line(165, 92, 165, 140);

        // Draw horizontal division lines
        for ($y = 98; $y <= 134; $y += 6) {
            $pdf->Line(15, $y, 195, $y);
        }

        // Header Row text
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY(15, 92); $pdf->Cell(60, 6, '  Earnings', 0, 0, 'L');
        $pdf->SetXY(75, 92); $pdf->Cell(30, 6, 'Amount', 0, 0, 'C');
        $pdf->SetXY(105, 92); $pdf->Cell(60, 6, '  Deductions', 0, 0, 'L');
        $pdf->SetXY(165, 92); $pdf->Cell(30, 6, 'Amount', 0, 0, 'C');

        $pdf->SetFont('Arial', '', 8.5);
        // Row 1
        $pdf->SetXY(15, 98); $pdf->Cell(60, 6, '  BASIC SALARY', 0, 0, 'L');
        $pdf->SetXY(75, 98); $pdf->Cell(30, 6, number_format($basic, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 98); $pdf->Cell(60, 6, '  PROFESSIONAL TAX', 0, 0, 'L');
        $pdf->SetXY(165, 98); $pdf->Cell(30, 6, number_format($ptax, 2) . '  ', 0, 0, 'R');

        // Row 2
        $pdf->SetXY(15, 104); $pdf->Cell(60, 6, '  H.R.A.', 0, 0, 'L');
        $pdf->SetXY(75, 104); $pdf->Cell(30, 6, number_format($hra, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 104); $pdf->Cell(60, 6, '  Provident Fund', 0, 0, 'L');
        $pdf->SetXY(165, 104); $pdf->Cell(30, 6, number_format($pf, 2) . '  ', 0, 0, 'R');

        // Row 3
        $pdf->SetXY(15, 110); $pdf->Cell(60, 6, '  MEDICAL ALLOWANCE', 0, 0, 'L');
        $pdf->SetXY(75, 110); $pdf->Cell(30, 6, number_format($medical, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 110); $pdf->Cell(60, 6, '  ESIC', 0, 0, 'L');
        $pdf->SetXY(165, 110); $pdf->Cell(30, 6, number_format($esic, 2) . '  ', 0, 0, 'R');

        // Row 4
        $pdf->SetXY(15, 116); $pdf->Cell(60, 6, '  SPECIAL ALLOWANCE', 0, 0, 'L');
        $pdf->SetXY(75, 116); $pdf->Cell(30, 6, number_format($special, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 116); $pdf->Cell(60, 6, '', 0, 0, 'L');
        $pdf->SetXY(165, 116); $pdf->Cell(30, 6, '', 0, 0, 'R');

        // Row 5
        $pdf->SetXY(15, 122); $pdf->Cell(60, 6, '  LEAVE ENCASHMENT', 0, 0, 'L');
        $pdf->SetXY(75, 122); $pdf->Cell(30, 6, number_format($leave, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 122); $pdf->Cell(60, 6, '', 0, 0, 'L');
        $pdf->SetXY(165, 122); $pdf->Cell(30, 6, '', 0, 0, 'R');

        // Row 6
        $pdf->SetXY(15, 128); $pdf->Cell(60, 6, '  OT ALLOWANCE', 0, 0, 'L');
        $pdf->SetXY(75, 128); $pdf->Cell(30, 6, number_format($otAllow, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 128); $pdf->Cell(60, 6, '', 0, 0, 'L');
        $pdf->SetXY(165, 128); $pdf->Cell(30, 6, '', 0, 0, 'R');

        // --- 4. Totals Row ---
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY(15, 134); $pdf->Cell(60, 6, '  Total', 0, 0, 'L');
        $pdf->SetXY(75, 134); $pdf->Cell(30, 6, number_format($totalEarnings, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY(105, 134); $pdf->Cell(60, 6, '  Total', 0, 0, 'L');
        $pdf->SetXY(165, 134); $pdf->Cell(30, 6, number_format($totalDeductions, 2) . '  ', 0, 0, 'R');

        // --- 5. Net Pay & Words Block ---
        // Net Pay Bar
        $pdf->Rect(15, 140, 180, 6);
        $pdf->Line(55, 140, 55, 146);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY(15, 140); $pdf->Cell(40, 6, '  *Net Pay', 0, 0, 'L');
        $pdf->SetXY(55, 140); $pdf->Cell(140, 6, ' Rs. ' . number_format($net, 2), 0, 0, 'L');

        // Rupees in Word Bar
        $netInWords = $this->convertNumberToWords($net);
        $pdf->Rect(15, 146, 180, 6);
        $pdf->SetXY(15, 146); $pdf->Cell(180, 6, '  Rupees in word: ' . $netInWords, 0, 0, 'L');

        // --- 6. Disclaimer Footer ---
        $pdf->Rect(15, 154, 180, 7);
        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(15, 154);
        $pdf->Cell(180, 7, '  * This is computer generated document and does not require any signature.', 0, 0, 'L');

        // Output to file
        $pdf->Output('F', $filePath);

        return 'storage/payslips/' . $fileName;
    }

    /**
     * Convert decimal number to Indian numbering words.
     */
    private function convertNumberToWords($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy',
            '80' => 'Eighty', '90' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "and " . $words[floor($point / 10) * 10] . " " . 
            $words[$point % 10] . " Paisa" : '';
        return $result . "Rupees " . $points . " Only";
    }
}
