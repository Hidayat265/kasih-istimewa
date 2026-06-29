<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationAllocationCategory extends Model
{
    use HasFactory;

    protected $table = 'donation_allocation_categories';

    protected $primaryKey = 'alc_cat_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'alc_cat_id',
        'alc_cat_name',
        'alc_cat_icon',
        'alc_cat_color',
        'alc_cat_is_active',
    ];

    protected $casts = [
        'alc_cat_is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function allocations()
    {
        return $this->hasMany(
            DonationAllocation::class,
            'allocation_category_id',
            'alc_cat_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('alc_cat_is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('alc_cat_name');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getFormattedNameAttribute()
    {
        return ucwords($this->alc_cat_name);
    }

    public function getIconHtmlAttribute()
    {
        return $this->alc_cat_icon
            ? '<i class="' . e($this->alc_cat_icon) . '"></i>'
            : '';
    }

    public function getColorStyleAttribute()
    {
        return $this->alc_cat_color
            ? 'background-color:' . $this->alc_cat_color . ';'
            : '';
    }
}