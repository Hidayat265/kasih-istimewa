<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationAllocationCategory;
use Carbon\Carbon;

class DonationAllocationCategorySeeder extends Seeder  // ← CHANGE THIS LINE
{
    public function run(): void
    {
        // Seed the categories first
        $categories = [
        [
            'alc_cat_id' => 'ALC-CAT-001',
            'alc_cat_name' => 'Community Outreach',
            'alc_cat_icon' => 'fas fa-hands-helping',
            'alc_cat_color' => '#4CAF50',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'alc_cat_id' => 'ALC-CAT-002',
            'alc_cat_name' => 'Special Needs Education',
            'alc_cat_icon' => 'fas fa-graduation-cap',
            'alc_cat_color' => '#2196F3',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'alc_cat_id' => 'ALC-CAT-003',
            'alc_cat_name' => 'Therapy & Rehabilitation',
            'alc_cat_icon' => 'fas fa-heartbeat',
            'alc_cat_color' => '#FF5722',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'alc_cat_id' => 'ALC-CAT-004',
            'alc_cat_name' => 'Administrative Operations',
            'alc_cat_icon' => 'fas fa-building',
            'alc_cat_color' => '#9E9E9E',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'alc_cat_id' => 'ALC-CAT-005',
            'alc_cat_name' => 'Emergency Assistance',
            'alc_cat_icon' => 'fas fa-ambulance',
            'alc_cat_color' => '#F44336',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'alc_cat_id' => 'ALC-CAT-006',
            'alc_cat_name' => 'Volunteer Training',
            'alc_cat_icon' => 'fas fa-chalkboard-teacher',
            'alc_cat_color' => '#9C27B0',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'alc_cat_id' => 'ALC-CAT-007',
            'alc_cat_name' => 'Facility Maintenance',
            'alc_cat_icon' => 'fas fa-tools',
            'alc_cat_color' => '#795548',
            'alc_cat_is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];

        foreach ($categories as $category) {
            DonationAllocationCategory::updateOrCreate(
                ['alc_cat_id' => $category['alc_cat_id']],
                $category
            );
        }

        $this->command->info('✅ Donation allocation categories seeded successfully!');
        $this->command->info('📊 ' . count($categories) . ' categories created/updated.');
    }
}