<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donation;
use App\Models\User;

class DonationAllocation extends Model
{
    use HasFactory;

    protected $table = 'donation_allocations';

    protected $primaryKey = 'allocation_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'allocation_id',
        'allocation_category_id',
        'allocation_month',
        'allocation_percent',
        'allocation_amount',
        'allocation_changed_by',
        'allocation_notes',
    ];

    protected $casts = [
        'allocation_percent' => 'decimal:2',
        'allocation_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(
            DonationAllocationCategory::class,
            'allocation_category_id',
            'alc_cat_id'
        );
    }

    public function changedByUser()
    {
        return $this->belongsTo(
            User::class,
            'allocation_changed_by',
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getMonthFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->allocation_month . '-01')
            ->format('F Y');
    }

    public function getPercentFormattedAttribute()
    {
        return number_format($this->allocation_percent, 2) . '%';
    }

    public function getAmountFormattedAttribute()
    {
        return 'RM ' . number_format($this->allocation_amount, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function calculateAmount()
    {
        $monthlyTotal = self::getMonthlyTotal($this->allocation_month);

        $this->allocation_amount =
            ($monthlyTotal * $this->allocation_percent) / 100;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC METHODS
    |--------------------------------------------------------------------------
    */

    public static function getMonthlyTotal($month)
    {
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);

        return Donation::where('donation_status', 'success')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->sum('donation_amount');
    }

    public static function getMonthSummary($month)
    {
        $allocations = self::where('allocation_month', $month)
            ->with('category')
            ->get();

        $monthlyTotal = self::getMonthlyTotal($month);

        return [
            'month' => $month,

            'total_donations' => $monthlyTotal,

            'total_allocated' => $allocations->sum('allocation_amount'),

            'total_percent' => $allocations->sum('allocation_percent'),

            'allocations' => $allocations->map(function ($allocation) {

                return [
                    'category_name' =>
                        $allocation->category?->alc_cat_name ?? 'Unknown',

                    'percent' => $allocation->allocation_percent,

                    'amount' => $allocation->allocation_amount,

                    'notes' => $allocation->allocation_notes,
                ];
            }),
        ];
    }

    public static function recalculateMonth($month)
    {
        $allocations = self::where(
            'allocation_month',
            $month
        )->get();

        foreach ($allocations as $allocation) {

            $allocation->calculateAmount();

            $allocation->save();
        }
    }

    /**
     * Recalculate ALL allocations for ALL months
     */
    public static function recalculateAll()
    {
        $months = self::select('allocation_month')
            ->distinct()
            ->pluck('allocation_month');
        
        foreach ($months as $month) {
            self::recalculateMonth($month);
        }
        
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($allocation) {

            if (!$allocation->allocation_id) {

                $latest = self::orderByDesc('allocation_id')->first();

                if (!$latest) {

                    $nextNumber = 1;

                } else {

                    $number = (int) str_replace(
                        'ALC-',
                        '',
                        $latest->allocation_id
                    );

                    $nextNumber = $number + 1;
                }

                $allocation->allocation_id =
                    'ALC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }

            $allocation->calculateAmount();
        });

        static::updating(function ($allocation) {

            $allocation->calculateAmount();
        });
    }
}