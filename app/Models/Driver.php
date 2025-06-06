<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'address',
        'contact_no',
        'drivers_license_no',
        'license_expiry_date'
    ];

    protected $casts = [
        'license_expiry_date' => 'date'
    ];

    // Get all operators associated with this driver
    public function operators()
    {
        return $this->belongsToMany(Operator::class, 'driver_operator')
            ->withTimestamps();
    }

    // Get full name attribute
    public function getFullNameAttribute(): string
    {
        return trim(sprintf('%s %s %s %s', 
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix
        ));
    }

    // Add this relationship to the Driver model
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
