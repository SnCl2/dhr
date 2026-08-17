<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferLetterTemplate extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'type',
        'content',
    ];

    public function offerLetters()
    {
        return $this->hasMany(OfferLetter::class);
    }
}
