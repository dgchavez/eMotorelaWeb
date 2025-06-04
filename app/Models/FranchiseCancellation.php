<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FranchiseCancellation extends Model
{
    protected $fillable = [
        'operator_id',
        'or_number',
        'amount',
        'cancellation_date',
        'reason'
    ];

    protected $casts = [
        'cancellation_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }
} 