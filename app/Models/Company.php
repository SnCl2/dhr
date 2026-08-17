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
    ];

    /**
     * Get the employees assigned to this company.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
