<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'activity_type',
        'performed_by_type',
        'performed_by_id',
        'performed_by_name',
        'filename',
        'success_count',
        'failed_count',
        'failed_csv_path',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the owning performed_by model.
     */
    public function performedBy()
    {
        return $this->morphTo('performedBy', 'performed_by_type', 'performed_by_id');
    }
}
