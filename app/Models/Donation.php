<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        // Backward compatibility aliases
        'amount',
        'payment_method',
        'received_by',
        'transaction_id',
    ];

    // Optional: cast donation amount to decimal
    protected $casts = [
        'donation_amount' => 'decimal:2',
    ];

    public function getAmountAttribute()
    {
        return $this->attributes['donation_amount'] ?? null;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['donation_amount'] = $value;
    }

    public function getPaymentMethodAttribute()
    {
        return $this->attributes['donation_payment_method'] ?? null;
    }

    public function setPaymentMethodAttribute($value)
    {
        $this->attributes['donation_payment_method'] = $value;
    }

    public function getReceivedByAttribute()
    {
        return $this->attributes['donation_received_by'] ?? null;
    }

    public function setReceivedByAttribute($value)
    {
        $this->attributes['donation_received_by'] = $value;
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'donation_received_by', 'user_id');
    }

    public function donorUser()
    {
        return $this->belongsTo(User::class, 'donor_email', 'user_email');
    }

    public function getReceivedByNameAttribute()
    {
        return $this->receivedByUser?->user_name ?? $this->received_by;
    }

    public function getTransactionIdAttribute()
    {
        return $this->attributes['donation_transaction_id'] ?? null;
    }

    public function setTransactionIdAttribute($value)
    {
        $this->attributes['donation_transaction_id'] = $value;
    }
}
