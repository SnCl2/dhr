<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'type',
        'pdf_path',
        'working_days',
        'net_payable_days',
        'ot_days',
        'pay_mode',
        'hra',
        'medical_allowance',
        'special_allowance',
        'leave_encashment',
        'ot_allowance',
        'provident_fund',
        'esic',
        'professional_tax',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'special_allowance' => 'decimal:2',
        'leave_encashment' => 'decimal:2',
        'ot_allowance' => 'decimal:2',
        'provident_fund' => 'decimal:2',
        'esic' => 'decimal:2',
        'professional_tax' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
