<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationAllocation;
use App\Models\DonationAllocationCategory;
use App\Models\Donation;
use Carbon\Carbon;

class DonationAllocationSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user_id for changed_by
        $adminUser = \App\Models\User::where('is_admin', 1)->first();
        $adminId = $adminUser ? $adminUser->user_id : null;

        // Get category IDs
        $categories = DonationAllocationCategory::pluck('alc_cat_id', 'alc_cat_id')->toArray();

        // Get all months that have successful donations
        $monthsWithDonations = Donation::where('donation_status', 'success')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        if (empty($monthsWithDonations)) {
            $this->command->warn('⚠️ No successful donations found. No allocations created.');
            return;
        }

        $this->command->info('📅 Found ' . count($monthsWithDonations) . ' months with donations:');
        foreach ($monthsWithDonations as $month) {
            $this->command->info('  • ' . Carbon::parse($month)->format('F Y'));
        }

        // Define allocation percentages by category
        $allocationData = [
            'ALC-CAT-001' => ['percent' => 26, 'notes' => 'Monthly allocation for community outreach initiatives'],
            'ALC-CAT-002' => ['percent' => 20, 'notes' => 'Supporting education for children with special needs'],
            'ALC-CAT-003' => ['percent' => 18, 'notes' => 'Funding for therapy sessions and rehabilitation programs'],
            'ALC-CAT-004' => ['percent' => 12, 'notes' => 'Administrative and operational expenses'],
            'ALC-CAT-005' => ['percent' => 10, 'notes' => 'Emergency assistance for families in crisis'],
            'ALC-CAT-006' => ['percent' => 8, 'notes' => 'Training and development for volunteers'],
            'ALC-CAT-007' => ['percent' => 6, 'notes' => 'Maintenance of facilities and equipment'],
        ];

        $allocationIdCounter = 1;

        foreach ($monthsWithDonations as $month) {
            $this->command->info("\n📊 Creating allocations for " . Carbon::parse($month)->format('F Y'));

            $totalPercent = 0;
            
            foreach ($allocationData as $alcCatId => $data) {
                // Check if category exists
                if (!in_array($alcCatId, $categories)) {
                    $this->command->warn("  ⚠️ Category {$alcCatId} not found, skipping...");
                    continue;
                }

                $allocationId = 'ALC-' . str_pad($allocationIdCounter, 4, '0', STR_PAD_LEFT);
                $allocationIdCounter++;

                // Create or update allocation - FIXED COLUMN NAMES
                DonationAllocation::updateOrCreate(
                    [
                        'allocation_month' => $month,  // ← Fixed: was 'month'
                        'allocation_category_id' => $alcCatId,  // ← Fixed: was 'category_id'
                    ],
                    [
                        'allocation_id' => $allocationId,
                        'allocation_percent' => $data['percent'],  // ← Fixed: was 'percent'
                        'allocation_changed_by' => $adminId,  // ← Fixed: was 'changed_by'
                        'allocation_notes' => $data['notes'],  // ← Fixed: was 'notes'
                    ]
                );

                $totalPercent += $data['percent'];
                $this->command->info("  ✅ {$data['percent']}% - {$data['notes']}");
            }

            if ($totalPercent == 100) {
                $this->command->info("  ✅ Total: {$totalPercent}% - Balanced!");
            } else {
                $this->command->warn("  ⚠️ Total: {$totalPercent}% - Needs adjustment!");
            }
        }

        // Output summary
        $this->command->info("\n📊 ALLOCATION SUMMARY:");
        $this->command->info("═══════════════════════════════════════");
        
        $allocationCount = DonationAllocation::count();
        $monthsCount = DonationAllocation::select('allocation_month')->distinct()->count();
        
        $this->command->info("📝 Total allocations created: {$allocationCount}");
        $this->command->info("📅 Months with allocations: {$monthsCount}");
        
        // Show monthly breakdown
        $months = DonationAllocation::select('allocation_month')
            ->distinct()
            ->orderBy('allocation_month', 'desc')
            ->get();
        
        $this->command->info("\n📊 ALLOCATION PERCENTAGES BY MONTH:");
        $this->command->info("───────────────────────────────────────");
        
        foreach ($months as $monthData) {
            $allocationsForMonth = DonationAllocation::where('allocation_month', $monthData->allocation_month)
                ->with('category')
                ->orderBy('allocation_percent', 'desc')
                ->get();
            
            $monthLabel = Carbon::parse($monthData->allocation_month)->format('F Y');
            $totalPercent = $allocationsForMonth->sum('allocation_percent');
            $totalAmount = $allocationsForMonth->sum('allocation_amount');
            
            $this->command->info("\n📅 {$monthLabel}:");
            $this->command->info("  Total Percent: {$totalPercent}%");
            $this->command->info("  Total Amount: RM " . number_format($totalAmount, 2));
            
            foreach ($allocationsForMonth as $allocation) {
                $categoryName = $allocation->category?->alc_cat_name ?? 'Unknown';
                $this->command->info("    • {$categoryName}: {$allocation->allocation_percent}% (RM " . number_format($allocation->allocation_amount, 2) . ")");
            }
        }
        
        $this->command->info("\n✨ Seeding completed successfully! ✨");
    }
}