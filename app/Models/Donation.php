<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;


class Donation extends Model
{
    use HasFactory;

    protected $primaryKey = 'donation_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'donation_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donation_amount',
        'donation_payment_method',
        'donation_received_by',
        'donation_transaction_id',
        'donation_status',
    ];

    protected $casts = [
        'donation_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    // Relations
    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'donation_received_by', 'user_id');
    }

    public function donorUser()
    {
        return $this->belongsTo(User::class, 'donor_email', 'user_email');
    }

    // Accessors
    public function getReceivedByNameAttribute()
    {
        return $this->receivedByUser?->user_name ?? $this->donation_received_by;
    }

    /**
     * Return one gateway-native transaction ID, including for legacy JSON rows.
     */
    public function getGatewayTransactionIdAttribute(): ?string
    {
        $storedValue = $this->donation_transaction_id;

        if (!$storedValue) {
            return null;
        }

        $legacyData = json_decode($storedValue, true);

        if (is_array($legacyData)) {
            return $legacyData['transaction_id']
                ?? $legacyData['payment_intent']
                ?? null;
        }

        return $storedValue;
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('donation_status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('donation_status', self::STATUS_PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where('donation_status', self::STATUS_FAILED);
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->donation_status === self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->donation_status === self::STATUS_PENDING;
    }

    public function isFailed()
    {
        return $this->donation_status === self::STATUS_FAILED;
    }

    public function getStatusBadgeClass()
    {
        return match($this->donation_status) {
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_FAILED => 'bg-red-100 text-red-800',
            self::STATUS_REFUNDED => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getFormattedAmount()
    {
        return 'RM ' . number_format($this->donation_amount, 2);
    }
}