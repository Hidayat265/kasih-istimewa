<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    /**
     * Helper function to capitalize each word in a name
     */
    private function capitalizeName($name)
    {
        if (empty($name)) {
            return $name;
        }
        
        // Split by spaces and capitalize each part
        $parts = explode(' ', $name);
        $capitalizedParts = array_map(function($part) {
            if (empty($part)) {
                return $part;
            }
            
            // Handle hyphenated names (e.g., "Jane-Doe")
            if (strpos($part, '-') !== false) {
                $hyphenParts = explode('-', $part);
                $capitalizedHyphenParts = array_map(function($p) {
                    return ucfirst(strtolower($p));
                }, $hyphenParts);
                return implode('-', $capitalizedHyphenParts);
            }
            
            // Handle apostrophe names (e.g., "O'Brien")
            if (strpos($part, "'") !== false) {
                $apostropheParts = explode("'", $part);
                $capitalizedApostropheParts = array_map(function($p) {
                    return ucfirst(strtolower($p));
                }, $apostropheParts);
                return implode("'", $capitalizedApostropheParts);
            }
            
            return ucfirst(strtolower($part));
        }, $parts);
        
        return implode(' ', $capitalizedParts);
    }

    public function run(): void
    {
        // Get admin user_ids for cash donations
        $adminIds = [];
        $admins = User::where('is_admin', 1)->get();
        foreach ($admins as $admin) {
            $adminIds[] = $admin->user_id;
        }
        
        // Default admin IDs if no admins found
        if (empty($adminIds)) {
            $adminIds = ['USR-0001', 'USR-0003', 'USR-0005'];
        }
        
        // Get Dieyard user
        $dieyardUser = User::where('user_email', 'dieyard.dhr@gmail.com')->first();
        $dieyardId = $dieyardUser ? $dieyardUser->user_id : 'USR-0016';
        $dieyardEmail = $dieyardUser ? $dieyardUser->user_email : 'dieyard.dhr@gmail.com';
        $dieyardName = $dieyardUser ? $this->capitalizeName($dieyardUser->user_name) : 'Dieyard Danial';
        
        // Date references
        $today = Carbon::now();
        $yesterday = Carbon::now()->subDays(1);
        $lastWeek = Carbon::now()->subDays(7);
        $twoWeeksAgo = Carbon::now()->subDays(14);
        $lastMonth = Carbon::now()->subDays(30);
        $twoMonthsAgo = Carbon::now()->subDays(60);
        $threeMonthsAgo = Carbon::now()->subDays(90);
        $fourMonthsAgo = Carbon::now()->subDays(120);
        
        $donations = [];
        
        // ============================================
        // DIEYARD'S BIG DONATIONS (11 transactions)
        // ============================================
        $dieyardDonations = [
            [
                'donation_id' => 'DON-0006',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 10000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-DIEYARD-001',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'donation_id' => 'DON-0020',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 5000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-DIEYARD-002',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $lastWeek,
                'updated_at' => $lastWeek,
            ],
            [
                'donation_id' => 'DON-0021',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 7500.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-DIEYARD-001',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $twoWeeksAgo,
                'updated_at' => $twoWeeksAgo,
            ],
            [
                'donation_id' => 'DON-0022',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 3000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-DIEYARD-002',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $lastMonth,
                'updated_at' => $lastMonth,
            ],
            [
                'donation_id' => 'DON-0023',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 1500.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-DIEYARD-003',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $twoMonthsAgo,
                'updated_at' => $twoMonthsAgo,
            ],
            [
                'donation_id' => 'DON-0024',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 8000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-DIEYARD-004',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $threeMonthsAgo,
                'updated_at' => $threeMonthsAgo,
            ],
            [
                'donation_id' => 'DON-0025',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 2000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-DIEYARD-003',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $fourMonthsAgo,
                'updated_at' => $fourMonthsAgo,
            ],
            [
                'donation_id' => 'DON-0026',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 12000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-DIEYARD-005',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $twoWeeksAgo->subDays(5),
                'updated_at' => $twoWeeksAgo->subDays(5),
            ],
            [
                'donation_id' => 'DON-0027',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 4500.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-DIEYARD-004',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $lastWeek->subDays(3),
                'updated_at' => $lastWeek->subDays(3),
            ],
            [
                'donation_id' => 'DON-0028',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 6000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-DIEYARD-006',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $lastMonth->subDays(10),
                'updated_at' => $lastMonth->subDays(10),
            ],
            [
                'donation_id' => 'DON-0029',
                'donor_name' => $dieyardName,
                'donor_email' => $dieyardEmail,
                'donor_phone' => '0138834062',
                'donation_amount' => 2500.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-DIEYARD-005',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'pending',
                'created_at' => $yesterday,
                'updated_at' => $yesterday,
            ],
        ];
        
        // Merge Dieyard donations
        $donations = array_merge($donations, $dieyardDonations);
        
        // ============================================
        // OTHER DONATIONS
        // ============================================
        $otherDonations = [
            // ========== CASH DONATIONS ==========
            [
                'donation_id' => 'DON-0001',
                'donor_name' => 'Qaiyum Akashah',
                'donor_email' => 'qaiyumakashah0929@gmail.com',
                'donor_phone' => '0123456789',
                'donation_amount' => 100.00,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[0] ?? 'USR-0001',
                'donation_status' => 'success',
                'created_at' => $lastMonth,
                'updated_at' => $lastMonth,
            ],
            [
                'donation_id' => 'DON-0002',
                'donor_name' => 'Adam Abdullah',
                'donor_email' => 'adam@gmail.com',
                'donor_phone' => '01112345678',
                'donation_amount' => 250.00,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[1] ?? 'USR-0002',
                'donation_status' => 'success',
                'created_at' => $twoWeeksAgo,
                'updated_at' => $twoWeeksAgo,
            ],
            [
                'donation_id' => 'DON-0003',
                'donor_name' => 'Alif Rosli',
                'donor_email' => 'alif@gmail.com',
                'donor_phone' => '01334567890',
                'donation_amount' => 75.50,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[0] ?? 'USR-0001',
                'donation_status' => 'success',
                'created_at' => $lastWeek,
                'updated_at' => $lastWeek,
            ],
            [
                'donation_id' => 'DON-0004',
                'donor_name' => 'Muhammad Daniel Bin Abdullah',
                'donor_email' => 'daniel@gmail.com',
                'donor_phone' => '0191234567',
                'donation_amount' => 500.00,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[2] ?? 'USR-0003',
                'donation_status' => 'success',
                'created_at' => $twoMonthsAgo,
                'updated_at' => $twoMonthsAgo,
            ],
            [
                'donation_id' => 'DON-0005',
                'donor_name' => 'Nurul Afiqah Binti Zainal',
                'donor_email' => 'afiqah@gmail.com',
                'donor_phone' => '0112345678',
                'donation_amount' => 150.00,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[1] ?? 'USR-0002',
                'donation_status' => 'success',
                'created_at' => $lastWeek,
                'updated_at' => $lastWeek,
            ],
            [
                'donation_id' => 'DON-0013',
                'donor_name' => 'Sharifah Nurul Aida',
                'donor_email' => 'sharifah@gmail.com',
                'donor_phone' => '0187654321',
                'donation_amount' => 300.00,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[0] ?? 'USR-0001',
                'donation_status' => 'success',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'donation_id' => 'DON-0014',
                'donor_name' => 'Anonymous Walker',
                'donor_email' => 'anonymous.walker@gmail.com',
                'donor_phone' => '0129876543',
                'donation_amount' => 50.00,
                'donation_payment_method' => 'cash',
                'donation_transaction_id' => null,
                'donation_received_by' => $adminIds[2] ?? 'USR-0003',
                'donation_status' => 'success',
                'created_at' => $yesterday,
                'updated_at' => $yesterday,
            ],
            
            // ========== ONLINE DONATIONS (ToyyibPay) ==========
            [
                'donation_id' => 'DON-0007',
                'donor_name' => 'Sharifah Nurul Aida',
                'donor_email' => 'sharifah@gmail.com',
                'donor_phone' => '0187654321',
                'donation_amount' => 300.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-20231202-002',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $lastWeek,
                'updated_at' => $lastWeek,
            ],
            [
                'donation_id' => 'DON-0008',
                'donor_name' => 'Anonymous Donor',
                'donor_email' => 'anonymous.donor@example.com',
                'donor_phone' => '0198765432',
                'donation_amount' => 50.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-20231203-003',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $twoWeeksAgo,
                'updated_at' => $twoWeeksAgo,
            ],
            [
                'donation_id' => 'DON-0009',
                'donor_name' => 'Adam Abdullah',
                'donor_email' => 'adam@gmail.com',
                'donor_phone' => '01112345678',
                'donation_amount' => 75.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-20231204-004',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $lastMonth,
                'updated_at' => $lastMonth,
            ],
            [
                'donation_id' => 'DON-0011',
                'donor_name' => 'Qaiyum Akashah',
                'donor_email' => 'qaiyumakashah0929@gmail.com',
                'donor_phone' => '0123456789',
                'donation_amount' => 450.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-20231206-002',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'pending',
                'created_at' => $twoWeeksAgo,
                'updated_at' => $twoWeeksAgo,
            ],
            
            // ========== ONLINE DONATIONS (Stripe) ==========
            [
                'donation_id' => 'DON-0010',
                'donor_name' => 'Alif Rosli',
                'donor_email' => 'alif@gmail.com',
                'donor_phone' => '01334567890',
                'donation_amount' => 200.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-20231205-001',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'donation_id' => 'DON-0012',
                'donor_name' => 'John Smith',
                'donor_email' => 'john.smith@example.com',
                'donor_phone' => '0101234567',
                'donation_amount' => 120.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-20231207-003',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $threeMonthsAgo,
                'updated_at' => $threeMonthsAgo,
            ],
            [
                'donation_id' => 'DON-0015',
                'donor_name' => 'Muhammad Daniel Bin Abdullah',
                'donor_email' => 'daniel@gmail.com',
                'donor_phone' => '0191234567',
                'donation_amount' => 85.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-20231208-004',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $lastWeek,
                'updated_at' => $lastWeek,
            ],
            [
                'donation_id' => 'DON-0016',
                'donor_name' => 'Nurul Afiqah Binti Zainal',
                'donor_email' => 'afiqah@gmail.com',
                'donor_phone' => '0112345678',
                'donation_amount' => 60.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-20231209-005',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'pending',
                'created_at' => $yesterday,
                'updated_at' => $yesterday,
            ],
            [
                'donation_id' => 'DON-0017',
                'donor_name' => 'Hidayat',
                'donor_email' => 'hidayat@gmail.com',
                'donor_phone' => '0138834062',
                'donation_amount' => 2000.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-20231210-006',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $twoMonthsAgo,
                'updated_at' => $twoMonthsAgo,
            ],
            [
                'donation_id' => 'DON-0018',
                'donor_name' => 'Sarah Tan',
                'donor_email' => 'sarah.tan@example.com',
                'donor_phone' => '0161234567',
                'donation_amount' => 35.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'STR-20231211-007',
                'donation_received_by' => 'Stripe',
                'donation_status' => 'success',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'donation_id' => 'DON-0019',
                'donor_name' => 'Ahmad Faiz',
                'donor_email' => 'ahmad.faiz@example.com',
                'donor_phone' => '0177654321',
                'donation_amount' => 25.00,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'TP-20231212-008',
                'donation_received_by' => 'ToyyibPay',
                'donation_status' => 'success',
                'created_at' => $lastWeek,
                'updated_at' => $lastWeek,
            ],
        ];
        
        // Merge all donations
        $donations = array_merge($donations, $otherDonations);

        foreach ($donations as $data) {
            // Capitalize donor name if not already done
            if (isset($data['donor_name']) && !in_array($data['donor_name'], [$dieyardName])) {
                $data['donor_name'] = $this->capitalizeName($data['donor_name']);
            }
            Donation::create($data);
        }
        
        // Output success message
        $this->command->info('✅ Donations seeded successfully!');
        $this->command->info('📝 Total donations created: ' . count($donations));
        
        // Calculate totals
        $totalCash = Donation::where('donation_payment_method', 'cash')->sum('donation_amount');
        $totalOnline = Donation::where('donation_payment_method', 'online')->sum('donation_amount');
        $totalSuccess = Donation::where('donation_status', 'success')->sum('donation_amount');
        $totalPending = Donation::where('donation_status', 'pending')->sum('donation_amount');
        
        // Dieyard's total donations
        $dieyardTotal = Donation::where('donor_email', $dieyardEmail)->sum('donation_amount');
        $dieyardCount = Donation::where('donor_email', $dieyardEmail)->count();
        
        // Count donations by registered vs non-registered
        $registeredEmails = User::pluck('user_email')->toArray();
        $registeredDonations = Donation::whereIn('donor_email', $registeredEmails)->count();
        $nonRegisteredDonations = Donation::whereNotIn('donor_email', $registeredEmails)->count();
        
        // Get admin stats
        $adminCashDonations = Donation::where('donation_payment_method', 'cash')
            ->where('donation_received_by', 'like', 'USR-%')
            ->count();
        $gatewayDonations = Donation::where('donation_payment_method', 'online')->count();
        
        $this->command->info("\n📊 DONATION SUMMARY:");
        $this->command->info("═══════════════════════════════════════");
        $this->command->info("💰 Total Cash Donations: RM " . number_format($totalCash, 2));
        $this->command->info("💻 Total Online Donations: RM " . number_format($totalOnline, 2));
        $this->command->info("───────────────────────────────────────");
        $this->command->info("✅ Total Successful: RM " . number_format($totalSuccess, 2));
        $this->command->info("⏳ Total Pending: RM " . number_format($totalPending, 2));
        $this->command->info("───────────────────────────────────────");
        $this->command->info("👥 Donations from registered users: {$registeredDonations}");
        $this->command->info("🌐 Donations from non-registered users: {$nonRegisteredDonations}");
        $this->command->info("───────────────────────────────────────");
        $this->command->info("💵 Cash donations recorded by admins: {$adminCashDonations}");
        $this->command->info("💳 Online donations via payment gateways: {$gatewayDonations}");
        $this->command->info("───────────────────────────────────────");
        $this->command->info("⭐ DIEYARD'S DONATIONS:");
        $this->command->info("   📊 Total Donations: {$dieyardCount}");
        $this->command->info("   💰 Total Amount: RM " . number_format($dieyardTotal, 2));
        $this->command->info("   🏆 Average Donation: RM " . number_format($dieyardTotal / $dieyardCount, 2));
        
        // Show admin breakdown
        if ($adminCashDonations > 0) {
            $this->command->info("\n👤 ADMIN CASH DONATION RECORDS:");
            $this->command->info("───────────────────────────────────────");
            $adminRecords = Donation::where('donation_payment_method', 'cash')
                ->where('donation_received_by', 'like', 'USR-%')
                ->selectRaw('donation_received_by as admin_id, COUNT(*) as count, SUM(donation_amount) as total')
                ->groupBy('donation_received_by')
                ->get();
            
            foreach ($adminRecords as $record) {
                $admin = User::where('user_id', $record->admin_id)->first();
                $adminName = $admin ? $admin->user_name : 'Unknown Admin';
                $this->command->info("  👨‍💼 {$record->admin_id} ({$adminName}):");
                $this->command->info("     📊 {$record->count} donations | RM " . number_format($record->total, 2));
            }
        }
        
        // Show payment gateway breakdown
        $gatewayRecords = Donation::where('donation_payment_method', 'online')
            ->selectRaw('donation_received_by as gateway, COUNT(*) as count, SUM(donation_amount) as total')
            ->groupBy('donation_received_by')
            ->get();
        
        if ($gatewayRecords->count() > 0) {
            $this->command->info("\n🏦 PAYMENT GATEWAY BREAKDOWN:");
            $this->command->info("───────────────────────────────────────");
            foreach ($gatewayRecords as $record) {
                $this->command->info("  💳 " . ucfirst($record->gateway) . ":");
                $this->command->info("     📊 {$record->count} donations | RM " . number_format($record->total, 2));
            }
        }
        
        $this->command->info("\n✨ Seeding completed successfully! ✨");
    }
}