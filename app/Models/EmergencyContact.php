<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContact extends Model
{
    protected $fillable = [
        'operator_id',
        'contact_person',
        'tel_no'
    ];

    // Get the operator associated with this emergency contact
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }
}