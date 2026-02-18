<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'id_number',
        'emergency_contact_name', 'emergency_contact_phone',
        'apartment_id', 'lease_start', 'lease_end',
        'rent_amount', 'status', 'notes',
    ];

    protected $casts = [
        'lease_start' => 'date',
        'lease_end' => 'date',
        'rent_amount' => 'decimal:2',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function consentForms()
    {
        return $this->hasMany(ConsentForm::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'evicted' => 'red',
            default => 'gray',
        };
    }
}
