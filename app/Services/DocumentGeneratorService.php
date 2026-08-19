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

        $hra = (float) data_get($extra, 'hra', $allowances);
        $medical = (float) data_get($extra, 'medical_allowance', 0.0);
        $special = (float) data_get($extra, 'special_allowance', 0.0);
        $leave = (float) data_get($extra, 'leave_encashment', 0.0);
        $otAllow = (float) data_get($extra, 'ot_allowance', 0.0);

        $ptax = (float) data_get($extra, 'professional_tax', 0.0);
        $pf = (float) data_get($extra, 'provident_fund', $deductions);
        $esic = (float) data_get($extra, 'esic', 0.0);

        $basic = (float) $basic;
        $totalEarnings = $basic + $hra + $medical + $special + $leave + $otAllow;
        $totalDeductions = $ptax + $pf + $esic;
        $net = $totalEarnings - $totalDeductions;

        // Initialize FPDF: A4 Landscape (297mm x 210mm) matching Payslip Format.pdf
        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(false);

        // Coordinates & Dimensions based on original Payslip Format.pdf
        $x0 = 39.5;
        $y0 = 30.0;
        $w1 = 49.5;  // Col 1 width (Earnings / Field 1)
        $w2 = 59.2;  // Col 2 width (Amount / Val 1)
        $w3 = 36.7;  // Col 3 width (Deductions / Field 2)
        $w4 = 72.6;  // Col 4 width (Amount / Val 2)
        $wTotal = 218.0; // Total Width (49.5 + 59.2 + 36.7 + 72.6 = 218.0)

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.25);
        $pdf->SetTextColor(0, 0, 0);

        // --- 1. Header Box (Logo + Company Name) ---
        $pdf->Rect($x0, $y0, $wTotal, 21.0);
        // Vertical line separating logo from title
        $pdf->Line($x0 + $w1, $y0, $x0 + $w1, $y0 + 21.0);

        // Logo in Left Box
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, $x0 + 4, $y0 + 2.5, 41.5, 16);
        } else {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY($x0, $y0 + 6);
            $pdf->Cell($w1, 8, 'RM HR Solutions', 0, 0, 'C');
        }

        // Title in Right Box
        $pdf->SetFont('Arial', 'B', 22);
        $pdf->SetXY($x0 + $w1, $y0 + 3.5);
        $pdf->Cell($w2 + $w3 + $w4, 14, 'RM HR Solutions Pvt. Ltd.', 0, 0, 'C');

        // --- 2. Address Subtitle Bar ---
        $y = $y0 + 21.0;
        $pdf->Rect($x0, $y, $wTotal, 5.6);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY($x0, $y);
        $pdf->Cell($wTotal, 5.6, 'Panchla, Panchla, Howrah, 711322 West Bengal, India', 0, 0, 'C');

        // --- 3. PAY SLIP Month Bar ---
        $y += 5.6;
        $formattedMonth = $month;
        try {
            $time = strtotime($month);
            if ($time) {
                $formattedMonth = date("M'Y", $time);
            }
        } catch (\Throwable $e) {}

        $pdf->Rect($x0, $y, $wTotal, 5.6);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY($x0, $y);
        $pdf->Cell($wTotal, 5.6, 'PAY SLIP For the Month of -' . $formattedMonth, 0, 0, 'C');

        // --- 4. Employee Information Grid (6 rows) ---
        $y += 5.6;
        $empGridY = $y;
        $rowH = 5.63;
        $gridH = $rowH * 6;

        $pdf->Rect($x0, $empGridY, $wTotal, $gridH);
        // Vertical lines
        $pdf->Line($x0 + $w1, $empGridY, $x0 + $w1, $empGridY + $gridH);
        $pdf->Line($x0 + $w1 + $w2, $empGridY, $x0 + $w1 + $w2, $empGridY + $gridH);
        $pdf->Line($x0 + $w1 + $w2 + $w3, $empGridY, $x0 + $w1 + $w2 + $w3, $empGridY + $gridH);

        // Horizontal lines inside grid
        for ($i = 1; $i < 6; $i++) {
            $pdf->Line($x0, $empGridY + ($i * $rowH), $x0 + $wTotal, $empGridY + ($i * $rowH));
        }

        $empRows = [
            ['Working Days', $workingDays, 'Net Payable Days', $netPayableDays],
            ['Employee ID', $employee->employee_id, 'OT Days', $otDays],
            ['Employee Name', $employee->aadhaar_full_name ?? $employee->full_name, 'Pay Mode', $payMode],
            ['Joining Date', ($employee->joining_date ? $employee->joining_date->format('d-M-Y') : 'N/A'), 'Bank Name', ($employee->bank_name ?? 'N/A')],
            ['UAN', ($employee->old_uan_number ?? 'N/A'), 'Account No.', ($employee->bank_account_number ?? 'N/A')],
            ['ESI No', ($employee->old_esic_number ?? 'N/A'), 'IFSC Code', ($employee->ifsc_code ?? 'N/A')],
        ];

        $pdf->SetFont('Arial', '', 8);
        foreach ($empRows as $idx => $r) {
            $currY = $empGridY + ($idx * $rowH);
            // Col 1 Label (Centered)
            $pdf->SetXY($x0, $currY);
            $pdf->Cell($w1, $rowH, $r[0], 0, 0, 'C');

            // Col 2 Value with Colon
            $pdf->SetXY($x0 + $w1, $currY);
            $pdf->Cell(8, $rowH, ':', 0, 0, 'C');
            $pdf->SetXY($x0 + $w1 + 8, $currY);
            $pdf->Cell($w2 - 8, $rowH, ' ' . $r[1], 0, 0, 'L');

            // Col 3 Label (Centered)
            $pdf->SetXY($x0 + $w1 + $w2, $currY);
            $pdf->Cell($w3, $rowH, $r[2], 0, 0, 'C');

            // Col 4 Value with Colon
            $pdf->SetXY($x0 + $w1 + $w2 + $w3, $currY);
            $pdf->Cell(8, $rowH, ':', 0, 0, 'C');
            $pdf->SetXY($x0 + $w1 + $w2 + $w3 + 8, $currY);
            $pdf->Cell($w4 - 8, $rowH, ' ' . $r[3], 0, 0, 'L');
        }

        // --- 5. Earnings & Deductions Grid ---
        $y = $empGridY + $gridH;
        $earnGridY = $y;
        $earnRowsCount = 6; // 6 item rows
        $earnGridH = $rowH * (1 + $earnRowsCount + 1); // Header (1) + Items (6) + Total (1) = 8 rows

        $pdf->Rect($x0, $earnGridY, $wTotal, $earnGridH);
        // Vertical lines
        $pdf->Line($x0 + $w1, $earnGridY, $x0 + $w1, $earnGridY + $earnGridH);
        $pdf->Line($x0 + $w1 + $w2, $earnGridY, $x0 + $w1 + $w2, $earnGridY + $earnGridH);
        $pdf->Line($x0 + $w1 + $w2 + $w3, $earnGridY, $x0 + $w1 + $w2 + $w3, $earnGridY + $earnGridH);

        // Horizontal lines
        for ($i = 1; $i <= (1 + $earnRowsCount); $i++) {
            $pdf->Line($x0, $earnGridY + ($i * $rowH), $x0 + $wTotal, $earnGridY + ($i * $rowH));
        }

        // Header Row
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY($x0, $earnGridY);
        $pdf->Cell($w1, $rowH, 'Earnings', 0, 0, 'C');
        $pdf->SetXY($x0 + $w1, $earnGridY);
        $pdf->Cell($w2, $rowH, 'Amount', 0, 0, 'C');
        $pdf->SetXY($x0 + $w1 + $w2, $earnGridY);
        $pdf->Cell($w3, $rowH, 'Deductions', 0, 0, 'C');
        $pdf->SetXY($x0 + $w1 + $w2 + $w3, $earnGridY);
        $pdf->Cell($w4, $rowH, 'Amount', 0, 0, 'C');

        // Earnings & Deductions Items
        $earnItems = [
            ['BASIC SALARY', $basic > 0 ? number_format($basic, 2) : '', 'PROFESSIONAL TAX', $ptax > 0 ? number_format($ptax, 2) : ''],
            ['H.R.A.', $hra > 0 ? number_format($hra, 2) : '', 'Provident Fund', $pf > 0 ? number_format($pf, 2) : ''],
            ['MEDICAL ALLOWANCE', $medical > 0 ? number_format($medical, 2) : '', 'ESIC', $esic > 0 ? number_format($esic, 2) : ''],
            ['SPECIAL ALLOWANCE', $special > 0 ? number_format($special, 2) : '', '', ''],
            ['LEAVE ENCASHMENT', $leave > 0 ? number_format($leave, 2) : '', '', ''],
            ['OT ALLOWANCE', $otAllow > 0 ? number_format($otAllow, 2) : '', '', ''],
        ];

        $pdf->SetFont('Arial', '', 8);
        foreach ($earnItems as $idx => $item) {
            $currY = $earnGridY + (($idx + 1) * $rowH);
            $pdf->SetXY($x0, $currY);
            $pdf->Cell($w1, $rowH, $item[0], 0, 0, 'C');

            $pdf->SetXY($x0 + $w1, $currY);
            if ($item[1] !== '') {
                $pdf->Cell($w2, $rowH, $item[1] . '  ', 0, 0, 'R');
            }

            $pdf->SetXY($x0 + $w1 + $w2, $currY);
            $pdf->Cell($w3, $rowH, $item[2], 0, 0, 'C');

            $pdf->SetXY($x0 + $w1 + $w2 + $w3, $currY);
            if ($item[3] !== '') {
                $pdf->Cell($w4, $rowH, $item[3] . '  ', 0, 0, 'R');
            }
        }

        // Totals Row
        $totY = $earnGridY + ((1 + $earnRowsCount) * $rowH);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY($x0, $totY);
        $pdf->Cell($w1, $rowH, 'Total', 0, 0, 'C');
        $pdf->SetXY($x0 + $w1, $totY);
        $pdf->Cell($w2, $rowH, number_format($totalEarnings, 2) . '  ', 0, 0, 'R');
        $pdf->SetXY($x0 + $w1 + $w2, $totY);
        $pdf->Cell($w3, $rowH, 'Total', 0, 0, 'C');
        $pdf->SetXY($x0 + $w1 + $w2 + $w3, $totY);
        $pdf->Cell($w4, $rowH, number_format($totalDeductions, 2) . '  ', 0, 0, 'R');

        // --- 6. *Net Pay Row ---
        $y = $earnGridY + $earnGridH;
        $pdf->Rect($x0, $y, $wTotal, $rowH);
        $pdf->Line($x0 + $w1, $y, $x0 + $w1, $y + $rowH);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetXY($x0, $y);
        $pdf->Cell($w1, $rowH, '*Net Pay', 0, 0, 'C');
        $pdf->SetXY($x0 + $w1, $y);
        $pdf->Cell($w2 + $w3 + $w4, $rowH, '  Rs. ' . number_format($net, 2), 0, 0, 'L');

        // --- 7. Rupees in word: Row ---
        $y += $rowH;
        $netInWords = $this->convertNumberToWords($net);
        $pdf->Rect($x0, $y, $wTotal, $rowH);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY($x0, $y);
        $pdf->Cell($wTotal, $rowH, '  Rupees in word: ' . $netInWords, 0, 0, 'L');

        // --- 8. Computer Generated Disclaimer Footer ---
        $y += $rowH;
        $disclaimerH = 10.2;
        $pdf->Rect($x0, $y, $wTotal, $disclaimerH);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY($x0, $y + 1);
        $pdf->Cell($wTotal, 8, '  * This is computer generated document and does not require any signature.', 0, 0, 'L');

        // Save PDF to file
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
