<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'status',
        'department_id',
        'designation_id',
        'joining_date',
        'salary',
        'company_id',
        'is_password_changed',

        // Aadhaar and KYC Attributes
        'aadhaar_full_name',
        'aadhaar_number',
        'pan_number',
        'voter_id_number',
        'prefix',
        'father_name_aadhaar',
        'mother_name_aadhaar',
        'gender',
        'dob',
        'mother_tongue',
        'aadhaar_address',
        'landmark',
        'contact_number',
        'city',
        'emergency_contact_number',
        'pin_code',
        'state',
        'last_qualification',
        'pass_out_year',
        'marital_status',
        'email_id',
        'old_uan_number',
        'old_esic_number',
        'bank_account_number',
        'ifsc_code',
        'bank_name',
        'client_name',
        'work_location',
        'designation',
        'nth_salary',
        'employee_document',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_password_changed' => 'boolean',
        'salary' => 'decimal:2',
        'joining_date' => 'date',
        'dob' => 'date',
        'nth_salary' => 'decimal:2',
    ];

    public function getFullNameAttribute()
    {
        if (!empty($this->aadhaar_full_name)) {
            return $this->aadhaar_full_name;
        }
        $name = trim("{$this->first_name} {$this->last_name}");
        return $name ?: ($this->email ?? 'Employee');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designationRelation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function offerLetters()
    {
        return $this->hasMany(OfferLetter::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }
}
