<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operator extends Model
{
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'address',
        'contact_no',
        'email'
    ];

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
        return trim(sprintf('%s %s %s %s', 
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix
        ));
    }
}
