<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'booking_id', 'client_name', 'client_email',
        'apartment_id', 'check_in', 'check_out',
        'policies_text', 'is_signed', 'signed_at',
        'signature_ip', 'pdf_path',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'is_signed' => 'boolean',
        'signed_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function scopeSigned($query)
    {
        return $query->where('is_signed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_signed', false);
    }
}
