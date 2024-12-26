<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'check_in',
        'is_late'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'is_late' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
} 