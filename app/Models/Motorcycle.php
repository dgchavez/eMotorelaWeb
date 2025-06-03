<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Motorcycle extends Model
{
    protected $fillable = [
        'operator_id',
        'mtop_no',
        'motor_no',
        'chassis_no',
        'make',
        'year_model',
        'mv_file_no',
        'plate_no',
        'color',
        'registration_date'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Get the operator that owns the motorcycle
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }
}
