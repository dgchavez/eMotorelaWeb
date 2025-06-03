<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    protected $fillable = ['name'];

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function operators(): HasMany
    {
        return $this->hasMany(Operator::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }
} 