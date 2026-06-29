<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';
    
    protected $fillable = [
        'email',
        'code',
        'user_data',
        'expires_at',
    ];
    
    protected $casts = [
        'user_data' => 'array',
        'expires_at' => 'datetime',
    ];
    
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}