<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodaMembership extends Model
{
    protected $fillable = [
        'operator_id',
        'toda_name',
        'registration_date',
        'toda_president'
    ];

    protected $casts = [
        'registration_date' => 'date'
    ];

    // Get the operator that owns the TODA membership
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }
}
