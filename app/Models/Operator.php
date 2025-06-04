<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operator extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact_no',
        'email',
        'toda_id',
        'status',
        'deactivation_reason',
        'deactivation_date'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deactivation_date' => 'datetime'
    ];

    // Define the relationship with Toda
    public function toda(): BelongsTo
    {
        return $this->belongsTo(Toda::class);
    }

    // Get all motorcycles owned by the operator
    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class);
    }

    // Get all drivers associated with the operator
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    // Get the TODA membership of the operator
    public function todaMembership(): HasOne
    {
        return $this->hasOne(TodaMembership::class);
    }

    // Get the emergency contact of the operator
    public function emergencyContact(): HasOne
    {
        return $this->hasOne(EmergencyContact::class);
    }

    // Get all applications of the operator
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // Get full name attribute
    public function getFullNameAttribute(): string
    {
        return trim(sprintf('%s %s %s', 
            $this->first_name,
            $this->middle_name ?? '',
            $this->last_name
        ));
    }

    // Add this relationship to the Operator model
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    // Add a scope for active operators
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Add a scope for inactive operators
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
