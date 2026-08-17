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
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
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
