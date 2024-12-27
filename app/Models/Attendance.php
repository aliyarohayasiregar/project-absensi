<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'is_late',
        'is_early_leave'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'is_late' => 'boolean',
        'is_early_leave' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
} 