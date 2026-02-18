<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'request_date', 'start_date', 'end_date',
        'type', 'status', 'reason', 'admin_notes',
    ];

    protected $casts = [
        'request_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'annual' => 'blue',
            'sick' => 'red',
            'personal' => 'purple',
            default => 'gray',
        };
    }
}
