<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_email_verified_at',
        'user_password',
        'user_dob',
        'user_phone_number',
        'user_profile_picture',
        'is_admin',
        'user_status',
        'remember_token',
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    protected $casts = [
        'user_email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get events created by this user
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'event_created_by_id', 'user_id');
    }

    /**
     * Get participants (events user has joined)
     */
    public function participants()
    {
        return $this->hasMany(Participant::class, 'user_id', 'user_id');
    }

    /**
     * Get donations by this user (by email)
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'donor_email', 'user_email');
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $latestUser = static::orderBy('user_id', 'desc')->first();
            $number = $latestUser ? intval(substr($latestUser->user_id, 4)) + 1 : 1;
            $user->user_id = 'USR-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->user_profile_picture) {
            return $this->user_profile_picture;
        }

        $initial = strtoupper(substr($this->user_name ?? 'U', 0, 1));
        $cloudName = 'dvwfqplh3';

        return "https://res.cloudinary.com/{$cloudName}/image/upload/"
            . "w_200,h_200,c_fill,r_max,b_rgb:999999/"
            . "l_text:Arial_100_bold:" . rawurlencode($initial) . ",co_rgb:ffffff,g_center/"
            . "fl_layer_apply/f_auto,q_auto/blank.png";
    }
    
    public function getAgeAttribute()
    {
        if ($this->user_dob) {
            return Carbon::parse($this->user_dob)->age;
        }
        return null;
    }

    // Helper methods for user status
    public function isActive()
    {
        return $this->user_status === 'active';
    }

    public function isDeactivated()
    {
        return $this->user_status === 'deactivated';
    }

    public function activate()
    {
        $this->user_status = 'active';
        return $this->save();
    }

    public function deactivate()
    {
        $this->user_status = 'deactivated';
        return $this->save();
    }

    // Scope for active users only
    public function scopeActive($query)
    {
        return $query->where('user_status', 'active');
    }

    // Scope for deactivated users only
    public function scopeDeactivated($query)
    {
        return $query->where('user_status', 'deactivated');
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->user_email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'user_email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification()
    {
        // This will be handled by Laravel's built-in notification
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
}