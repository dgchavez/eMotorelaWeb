<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toda extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'president',
        'registration_date',
        'description',
        'status'
    ];

    protected $casts = [
        'registration_date' => 'date'
    ];

    // Scopes for easier querying
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function operators(): HasMany
    {
        return $this->hasMany(Operator::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(TodaMembership::class, 'toda_name', 'name');
    }
}