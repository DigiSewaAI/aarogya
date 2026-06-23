<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'symptoms',
        'notes',
        'status',
        'cancelled_at',
        'cancelled_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'cancelled_at'     => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function isPending()    { return $this->status === self::STATUS_PENDING; }
    public function isApproved()   { return $this->status === self::STATUS_APPROVED; }
    public function isCompleted()  { return $this->status === self::STATUS_COMPLETED; }
    public function isCancelled()  { return $this->status === self::STATUS_CANCELLED; }

    public function scopePending($query) { return $query->where('status', self::STATUS_PENDING); }
    public function scopeToday($query)   { return $query->where('appointment_date', now()->toDateString()); }
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->where('status', self::STATUS_APPROVED);
    }

    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->format('M d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->format('h:i A');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED  => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED  => 'bg-red-100 text-red-800',
            self::STATUS_COMPLETED => 'bg-blue-100 text-blue-800',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            default                => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'Pending',
            self::STATUS_APPROVED  => 'Approved',
            self::STATUS_REJECTED  => 'Rejected',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default                => ucfirst($this->status),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | QR CODE GENERATION - Using endroid/qr-code (no imagick needed)
    |--------------------------------------------------------------------------
    */
    public function getQrCodeBase64Attribute()
    {
        try {
            $data = json_encode([
                'appointment_id' => $this->id,
                'date'           => $this->appointment_date->format('Y-m-d'),
                'time'           => $this->appointment_time,
                'status'         => $this->status,
            ]);

            // ✅ endroid/qr-code (uses GD, no imagick)
            $qrCode = new QrCode($data);
            $qrCode->setSize(200);
            
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            return base64_encode($result->getString());
            
        } catch (\Exception $e) {
            \Log::error('QR Generation Failed: ' . $e->getMessage());
            return null;
        }
    }
}