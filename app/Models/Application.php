<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Storage;

class Application extends Model
{
    protected $fillable = [
        'operator_id',
        'application_date',
        'status',
        'tracking_code',
        'qr_code_path',
        'notes',
        'last_status_update',
        'status_history'
    ];

    protected $casts = [
        'application_date' => 'date',
        'last_status_update' => 'datetime',
        'status_history' => 'array'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';

    // Get the operator that owns the application
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    // Generate tracking code
    public static function generateTrackingCode(): string
    {
        do {
            $code = 'APP-' . strtoupper(Str::random(8));
        } while (static::where('tracking_code', $code)->exists());
        
        return $code;
    }

    // Generate QR code
    public function generateQRCode(): void
    {
        if (!$this->tracking_code) {
            $this->tracking_code = self::generateTrackingCode();
        }

        $qrContent = route('application.track', $this->tracking_code);
        $qrCodePath = 'qrcodes/' . $this->tracking_code . '.svg';
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($qrContent);
            
        // Store QR code
        Storage::disk('public')->put($qrCodePath, $qrCode);
        
        $this->qr_code_path = $qrCodePath;
        $this->save();
    }

    // Update application status
    public function updateStatus(string $newStatus, string $notes = null): void
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->last_status_update = now();
        
        // Update status history
        $statusHistory = $this->status_history ?? [];
        $statusHistory[] = [
            'status' => $newStatus,
            'timestamp' => now()->toDateTimeString(),
            'notes' => $notes
        ];
        
        $this->status_history = $statusHistory;
        $this->notes = $notes;
        $this->save();
    }

    // Get status history
    public function getStatusHistoryFormatted(): array
    {
        return collect($this->status_history ?? [])->map(function ($history) {
            return [
                'status' => ucfirst($history['status']),
                'timestamp' => \Carbon\Carbon::parse($history['timestamp'])->format('M d, Y h:i A'),
                'notes' => $history['notes'] ?? null
            ];
        })->toArray();
    }

    // Scope for pending applications
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Scope for approved applications
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // Scope for rejected applications
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Get QR code URL
    public function getQRCodeUrl(): ?string
    {
        return $this->qr_code_path ? Storage::disk('public')->url($this->qr_code_path) : null;
    }
}
