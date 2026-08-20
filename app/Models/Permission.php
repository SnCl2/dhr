<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'label',
    ];

    /**
     * The staff that belong to the permission.
     */
    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'permission_staff', 'permission_id', 'staff_id');
    }
}
