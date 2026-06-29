<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    
    protected $fillable = [
        'event_id',
        'user_id',
        'participant_status',
        'participant_registered_at',
        'participant_confirmed_at',
        'participant_cancelled_at',
        'participant_cancellation_reason',
    ];
    
    protected $casts = [
        'participant_registered_at' => 'datetime',
        'participant_confirmed_at' => 'datetime',
        'participant_cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    // Scopes for easy filtering
    public function scopePending($query)
    {
        return $query->where('participant_status', 'pending');
    }
    
    public function scopeConfirmed($query)
    {
        return $query->where('participant_status', 'confirmed');
    }
    
    public function scopeAttended($query)
    {
        return $query->where('participant_status', 'attended');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('participant_status', 'cancelled');
    }
    
    // Helper methods
    public function isPending(): bool
    {
        return $this->participant_status === 'pending';
    }
    
    public function isConfirmed(): bool
    {
        return $this->participant_status === 'confirmed';
    }
    
    public function isCancelled(): bool
    {
        return $this->participant_status === 'cancelled';
    }
    
    public function isAttended(): bool
    {
        return $this->participant_status === 'attended';
    }
    
    public function confirm(): void
    {
        $this->update([
            'participant_status' => 'confirmed',
            'participant_confirmed_at' => now(),
        ]);
        
        // Update event participant count
        if ($this->event) {
            $this->event->updateVolunteerCount(1);
        }
    }
    
    public function cancel(?string $reason = null): void
    {
        $this->update([
            'participant_status' => 'cancelled',
            'participant_cancelled_at' => now(),
            'participant_cancellation_reason' => $reason,
        ]);
        
        // Update event participant count
        if ($this->event) {
            $this->event->updateVolunteerCount(-1);
        }
    }
    
    public function markAttended(): void
    {
        $this->update([
            'participant_status' => 'attended',
        ]);
    }
}