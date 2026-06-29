<?php

namespace App\Traits;

use App\Models\DonationAllocation;

trait RecalculatesAllocations
{
    /**
     * Recalculate allocations when a donation is created, updated, or deleted
     */
    protected function recalculateAllocationsForDonation($donation)
    {
        // Only recalculate if donation is successful
        if ($donation->donation_status !== 'success') {
            return;
        }

        $month = $donation->created_at->format('Y-m');
        
        // Check if there are allocations for this month
        $hasAllocations = DonationAllocation::where('allocation_month', $month)->exists();
        
        if ($hasAllocations) {
            DonationAllocation::recalculateMonth($month);
        }
    }
}