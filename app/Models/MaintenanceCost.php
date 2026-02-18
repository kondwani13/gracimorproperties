<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id', 'description', 'category',
        'amount', 'date', 'status',
        'vendor', 'vendor_phone', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            default => 'gray',
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match ($this->category) {
            'plumbing' => 'Plumbing',
            'electrical' => 'Electrical',
            'cleaning' => 'Cleaning',
            'general' => 'General',
            'painting' => 'Painting',
            'appliance' => 'Appliance',
            default => ucfirst($this->category),
        };
    }
}
