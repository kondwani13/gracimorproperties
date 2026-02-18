<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id', 'type', 'description',
        'amount', 'due_date', 'paid_date',
        'status', 'reference_number', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'paid' => 'green',
            'pending' => 'yellow',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'electricity' => 'Electricity',
            'water' => 'Water',
            'internet' => 'Internet',
            'security' => 'Security',
            'garbage' => 'Garbage',
            'other' => 'Other',
            default => ucfirst($this->type),
        };
    }
}
