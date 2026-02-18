<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'amount', 'month',
        'payment_method', 'payment_date',
        'receipt_number', 'status', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'paid' => 'green',
            'unpaid' => 'red',
            'partial' => 'yellow',
            default => 'gray',
        };
    }
}
