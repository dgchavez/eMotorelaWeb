<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toda extends Model
{
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

    public function memberships(): HasMany
    {
        return $this->hasMany(TodaMembership::class, 'toda_name', 'name');
    }
}