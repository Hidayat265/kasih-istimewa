<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    // Define the custom primary key
    protected $primaryKey = 'event_id';

    // Indicate that the ID is not an incrementing integer
    public $incrementing = false;

    // The data type of the ID
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'event_created_by_id',
        'event_company_name',
        'event_name',
        'event_description',
        'event_location_name',
        'event_location_latitude',
        'event_location_longitude',
        'event_location_address',
        'event_start_date',
        'event_end_date',
        'event_start_session',
        'event_end_session',
        'event_maximum_participant',
        'event_current_participant',
        'event_document',
        'event_picture',
        'event_approval_status',
        'event_status',
        'event_remarks',
        'event_publish',
        'event_post_review',
        'event_approver_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'event_publish' => 'boolean',
        'event_location_latitude' => 'decimal:8',
        'event_location_longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Auto-generate EVT-XXXX ID on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->event_id)) {
                $lastEvent = DB::table('events')
                    ->orderBy('event_id', 'desc')
                    ->first();

                if (!$lastEvent) {
                    $model->event_id = 'EVT-0001';
                } else {
                    // Extract the numeric part and increment it
                    $number = intval(substr($lastEvent->event_id, 4));
                    $model->event_id = 'EVT-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'event_created_by_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'event_approver_id', 'user_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'event_id', 'event_id');
    }

    // FIX: Use participant_status instead of status
    public function confirmedParticipants()
    {
        return $this->hasMany(Participant::class, 'event_id', 'event_id')
            ->where('participant_status', 'confirmed');
    }

    // Accessors
    public function getAvailableSpotsAttribute()
    {
        return max(0, $this->event_maximum_participant - $this->event_current_participant);
    }

    public function getIsFullAttribute()
    {
        return $this->event_current_participant >= $this->event_maximum_participant;
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->event_start_date ? $this->event_start_date->format('d M Y') : null;
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->event_end_date ? $this->event_end_date->format('d M Y') : null;
    }

    public function getShortDescriptionAttribute()
    {
        return $this->event_description ? substr($this->event_description, 0, 100) . (strlen($this->event_description) > 100 ? '...' : '') : null;
    }

    public function getStartSessionTimeAttribute()
    {
        return match($this->event_start_session) {
            'Morning' => '8:00 AM',
            'Afternoon' => '1:00 PM',
            'Evening' => '6:00 PM',
            default => '',
        };
    }

    public function getEndSessionTimeAttribute()
    {
        return match($this->event_end_session) {
            'Morning' => '12:00 PM',
            'Afternoon' => '5:00 PM',
            'Evening' => '10:00 PM',
            default => '',
        };
    }

    // Geolocation accessors
    public function getHasCoordinatesAttribute()
    {
        return !is_null($this->event_location_latitude) && !is_null($this->event_location_longitude);
    }

    public function getGoogleMapsUrlAttribute()
    {
        if ($this->has_coordinates) {
            return "https://www.google.com/maps?q={$this->event_location_latitude},{$this->event_location_longitude}";
        }
        return null;
    }

    public function getWazeUrlAttribute()
    {
        if ($this->has_coordinates) {
            return "https://www.waze.com/ul?ll={$this->event_location_latitude},{$this->event_location_longitude}&navigate=yes";
        }
        return null;
    }

    // Scope for nearby events
    public function scopeNearby($query, $lat, $lng, $distanceInKm = 10)
    {
        $haversine = "(6371 * acos(cos(radians($lat)) 
            * cos(radians(event_location_latitude)) 
            * cos(radians(event_location_longitude) - radians($lng)) 
            + sin(radians($lat)) 
            * sin(radians(event_location_latitude))))";
        
        return $query->select('*')
            ->selectRaw("{$haversine} AS distance")
            ->whereNotNull('event_location_latitude')
            ->whereNotNull('event_location_longitude')
            ->having('distance', '<', $distanceInKm)
            ->orderBy('distance');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('event_publish', true)
                     ->where('event_approval_status', 'Approved');
    }

    public function scopePending($query)
    {
        return $query->where('event_approval_status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('event_approval_status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('event_approval_status', 'Rejected');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_start_date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('event_end_date', '<', now());
    }

    // Helper methods
    public function isUpcoming()
    {
        return $this->event_start_date >= now();
    }

    public function isPast()
    {
        return $this->event_end_date < now();
    }

    public function isPending()
    {
        return $this->event_approval_status === 'Pending';
    }

    public function isApproved()
    {
        return $this->event_approval_status === 'Approved';
    }

    public function isPublished()
    {
        return $this->event_publish && $this->event_approval_status === 'Approved';
    }

    public function updateVolunteerCount($change)
    {
        $newCount = $this->event_current_participant + $change;
        
        if ($newCount < 0 || $newCount > $this->event_maximum_participant) {
            return false;
        }
        
        $this->event_current_participant = $newCount;
        return $this->save();
    }
    
    // FIX: Use participant_status instead of status
    public function isUserRegistered($userId)
    {
        return $this->participants()
            ->where('user_id', $userId)
            ->where('participant_status', 'confirmed')
            ->exists();
    }
    
    // Get user's registration status for this event
    public function getUserRegistrationStatus($userId)
    {
        $participant = $this->participants()
            ->where('user_id', $userId)
            ->first();
            
        return $participant ? $participant->participant_status : null;
    }
}