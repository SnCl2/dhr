<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    protected $fillable = [
        'employee_id',
        'pdf_path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
