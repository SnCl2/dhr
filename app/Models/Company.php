<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'basic',
        'hra',
        'conveyance',
        'medical_allowance',
        'sp_allowance',
        'gross_earning',
        'bonus',
        'employer_pf',
        'employer_esic',
        'employer_lwf',
        'ctc',
        'employee_pf',
        'employee_esic',
        'employee_lwf',
        'professional_tax',
        'total_deductions',
        'net_salary',
    ];

    protected $casts = [
        'basic' => 'decimal:2',
        'hra' => 'decimal:2',
        'conveyance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'sp_allowance' => 'decimal:2',
        'gross_earning' => 'decimal:2',
        'bonus' => 'decimal:2',
        'employer_pf' => 'decimal:2',
        'employer_esic' => 'decimal:2',
        'employer_lwf' => 'decimal:2',
        'ctc' => 'decimal:2',
        'employee_pf' => 'decimal:2',
        'employee_esic' => 'decimal:2',
        'employee_lwf' => 'decimal:2',
        'professional_tax' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /**
     * Auto-calculate Gross, CTC, Deductions, and Net Salary before saving.
     */
    protected static function booted()
    {
        static::saving(function ($company) {
            $basic = (float) $company->basic;
            $hra = (float) $company->hra;
            $conveyance = (float) $company->conveyance;
            $medical = (float) $company->medical_allowance;
            $sp = (float) $company->sp_allowance;

            $gross = $basic + $hra + $conveyance + $medical + $sp;
            $company->gross_earning = $gross;

            $bonus = (float) $company->bonus;
            $emprPf = (float) $company->employer_pf;
            $emprEsic = (float) $company->employer_esic;
            $emprLwf = (float) $company->employer_lwf;

            $company->ctc = $gross + $bonus + $emprPf + $emprEsic + $emprLwf;

            $empePf = (float) $company->employee_pf;
            $empeEsic = (float) $company->employee_esic;
            $empeLwf = (float) $company->employee_lwf;
            $ptax = (float) $company->professional_tax;

            $totalDeductions = $empePf + $empeEsic + $empeLwf + $ptax;
            $company->total_deductions = $totalDeductions;

            $company->net_salary = $gross - $totalDeductions + $bonus;
        });
    }

    /**
     * Get the employees assigned to this company.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
