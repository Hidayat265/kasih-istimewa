<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Get users by email to get their user_ids
        $taroUser = User::where('user_email', 'taro@gmail.com')->first();
        $dieyardUser = User::where('user_email', 'dieyard.dhr@gmail.com')->first();
        $adminUser = User::where('is_admin', 1)->first();
        $aneUser = User::where('user_email', 'qaiyumakashah0929@gmail.com')->first();
        
        // Get other users for variety
        $adamUser = User::where('user_email', 'adam@gmail.com')->first();
        $alifUser = User::where('user_email', 'alif@gmail.com')->first();
        $hidayatUser = User::where('user_email', 'hidayat@gmail.com')->first();
        $danielUser = User::where('user_email', 'daniel@gmail.com')->first();
        $afiqahUser = User::where('user_email', 'afiqah@gmail.com')->first();
        $sharifahUser = User::where('user_email', 'sharifah@gmail.com')->first();
        
        // If no users exist, create some
        if (!$dieyardUser) {
            $this->command->error('DIEYARD user not found. Please run UserSeeder first.');
            return;
        }
        
        // Current date reference
        $today = Carbon::now();
        $futureDate1 = Carbon::now()->addDays(15);
        $futureDate2 = Carbon::now()->addDays(20);
        $futureDate3 = Carbon::now()->addDays(25);
        $futureDate4 = Carbon::now()->addDays(30);
        $futureDate5 = Carbon::now()->addDays(35);
        $futureDate6 = Carbon::now()->addDays(40);
        $futureDate7 = Carbon::now()->addDays(45);
        $futureDate8 = Carbon::now()->addDays(50);
        $pastDate1 = Carbon::now()->subDays(5);
        $pastDate2 = Carbon::now()->subDays(10);
        $pastDate3 = Carbon::now()->subDays(15);
        $pastDate4 = Carbon::now()->subDays(20);
        $pastDate5 = Carbon::now()->subDays(30);
        $pastDate6 = Carbon::now()->subDays(45);
        
        $events = [
            // ========== DIEYARD's Events ==========
            [
                'event_created_by_id' => $dieyardUser->user_id,
                'event_company_name' => 'Dieyard Foundation',
                'event_name' => 'Charity Run for Special Needs',
                'event_description' => 'Join us for a meaningful charity run to support individuals with special needs. The event includes a 5km fun run, wheelchair-friendly route, and family activities. All proceeds will go towards therapy programs and educational resources. Come dressed in your most vibrant colors and bring your family for a day of fun and giving back!',
                'event_location_name' => 'Taman Tasik Titiwangsa, Kuala Lumpur',
                'event_location_latitude' => 3.1732,
                'event_location_longitude' => 101.7072,
                'event_location_address' => 'Taman Tasik Titiwangsa, Kuala Lumpur, Malaysia',
                'event_start_date' => $futureDate1->format('Y-m-d'),
                'event_end_date' => $futureDate1->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Morning',
                'event_maximum_participant' => 100,
                'event_current_participant' => 45,
                'event_document' => 'https://res.cloudinary.com/dvwfqplh3/image/upload/v1780336944/EVT-0001_Dummy_Event_Document_orwvk0.pdf',
                'event_picture' => 'https://res.cloudinary.com/dvwfqplh3/image/upload/v1779788613/pexels-runffwpu-2461982_d6ksr5.jpg',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $dieyardUser->user_id,
                'event_company_name' => 'Dieyard Events',
                'event_name' => 'Art Exhibition Fundraiser',
                'event_description' => 'Experience a stunning collection of artworks created by local artists and individuals with special needs. This exhibition aims to raise funds for art therapy programs. The event will feature live painting sessions, art auctions, and meet-the-artist opportunities. All art pieces will be available for purchase, with proceeds supporting our art therapy initiatives.',
                'event_location_name' => 'National Art Gallery, Kuala Lumpur',
                'event_location_latitude' => 3.1741,
                'event_location_longitude' => 101.7090,
                'event_location_address' => 'National Art Gallery, Kuala Lumpur, Malaysia',
                'event_start_date' => $futureDate3->format('Y-m-d'),
                'event_end_date' => $futureDate4->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 50,
                'event_current_participant' => 20,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/a78bfa/4c1d95?text=Art+Exhibition',
                'event_approval_status' => 'Pending',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => false,
                'event_post_review' => null,
                'event_approver_id' => null,
            ],
            [
                'event_created_by_id' => $dieyardUser->user_id,
                'event_company_name' => 'Dieyard Community',
                'event_name' => 'Volunteer Training Workshop',
                'event_description' => 'A comprehensive training session for new volunteers. Learn about our organization, safety protocols, communication techniques, and how to effectively support individuals with special needs. This workshop is mandatory for all new volunteers. Participants will receive a certificate of completion and a volunteer handbook.',
                'event_location_name' => 'Community Hall, Shah Alam',
                'event_location_latitude' => 3.0738,
                'event_location_longitude' => 101.5183,
                'event_location_address' => 'Community Hall, Shah Alam, Malaysia',
                'event_start_date' => $futureDate2->format('Y-m-d'),
                'event_end_date' => $futureDate2->format('Y-m-d'),
                'event_start_session' => 'Afternoon',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 30,
                'event_current_participant' => 28,
                'event_document' => null,
                'event_picture' => 'https://res.cloudinary.com/dvwfqplh3/image/upload/v1779824516/704637032_18392779018081602_8775445061470289560_n_r18pyd.jpg',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            
            // ========== ANE's Events ==========
            [
                'event_created_by_id' => $aneUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'ANE Enterprise',
                'event_name' => 'Charity Food Drive',
                'event_description' => 'Help us collect and distribute food essentials to families in need. Volunteers will help sort, pack, and distribute food items. Donations of non-perishable food items are also welcome. This event is organized in collaboration with Food Bank Malaysia. Together, we can fight hunger in our community.',
                'event_location_name' => 'Food Bank Malaysia, PJ',
                'event_location_latitude' => 3.1076,
                'event_location_longitude' => 101.6103,
                'event_location_address' => 'Food Bank Malaysia, Petaling Jaya, Malaysia',
                'event_start_date' => $futureDate2->format('Y-m-d'),
                'event_end_date' => $futureDate2->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 80,
                'event_current_participant' => 40,
                'event_document' => null,
                'event_picture' => 'https://res.cloudinary.com/dvwfqplh3/image/upload/v1780388209/lots-volunteers-preparing-boxes-with-food-donations_pbaogp.jpg',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            
            // ========== Other Users' Events ==========
            [
                'event_created_by_id' => $adamUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Adam Enterprise',
                'event_name' => 'Food Donation Drive',
                'event_description' => 'Join Adam Enterprise in our annual food donation drive. We are collecting essential food items for underprivileged families. Volunteers needed for sorting, packing, and distribution. Your help will ensure that no family goes hungry during these challenging times.',
                'event_location_name' => 'Food Bank Malaysia, PJ',
                'event_location_latitude' => 3.1076,
                'event_location_longitude' => 101.6103,
                'event_location_address' => 'Food Bank Malaysia, Petaling Jaya, Malaysia',
                'event_start_date' => $futureDate2->format('Y-m-d'),
                'event_end_date' => $futureDate2->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 80,
                'event_current_participant' => 40,
                'event_document' => null,
                'event_picture' => 'https://res.cloudinary.com/dvwfqplh3/image/upload/v1780388501/hands-holding-donation-box-with-provisions_wd4ui8.jpg',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $alifUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Alif Care',
                'event_name' => 'Educational Support Program',
                'event_description' => 'An educational support program for children with special learning needs. Activities include tutoring sessions, educational games, and social skills development. Volunteers with teaching or tutoring experience are especially welcome. Join us in making education accessible for all.',
                'event_location_name' => 'Learning Center, Cyberjaya',
                'event_location_latitude' => 2.9276,
                'event_location_longitude' => 101.6554,
                'event_location_address' => 'Learning Center, Cyberjaya, Malaysia',
                'event_start_date' => $futureDate5->format('Y-m-d'),
                'event_end_date' => $futureDate6->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 60,
                'event_current_participant' => 25,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/fca5a5/7f1d1d?text=Education',
                'event_approval_status' => 'Pending',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => false,
                'event_post_review' => null,
                'event_approver_id' => null,
            ],
            [
                'event_created_by_id' => $hidayatUser->user_id ?? $adminUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Kasih Istimewa HQ',
                'event_name' => 'Annual Charity Gala',
                'event_description' => 'Our flagship fundraising event of the year! Join us for an elegant evening of fine dining, live entertainment, and a charity auction. All proceeds will go towards building a new therapy center for individuals with special needs. Dress code: Formal attire. Tables and sponsorship packages available.',
                'event_location_name' => 'Grand Ballroom, KLCC',
                'event_location_latitude' => 3.1578,
                'event_location_longitude' => 101.7146,
                'event_location_address' => 'Grand Ballroom, KLCC, Kuala Lumpur, Malaysia',
                'event_start_date' => $futureDate4->format('Y-m-d'),
                'event_end_date' => $futureDate4->format('Y-m-d'),
                'event_start_session' => 'Evening',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 150,
                'event_current_participant' => 89,
                'event_document' => null,
                'event_picture' => 'https://res.cloudinary.com/dvwfqplh3/image/upload/v1780388975/look-from-afar-dinner-table-served-with-rich-cutlery-crockery-golden-vases-candleholders_ufre5n.jpg',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            
            // ========== NEW EVENTS - Daniel's Events ==========
            [
                'event_created_by_id' => $danielUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Daniel Youth Movement',
                'event_name' => 'Beach Cleanup Drive',
                'event_description' => 'Join us for a beach cleanup at Port Dickson! Help protect our marine environment by collecting plastic waste and educating the public about ocean conservation. Gloves and trash bags will be provided. Let\'s work together to keep our beaches clean and beautiful for everyone to enjoy.',
                'event_location_name' => 'Port Dickson Beach',
                'event_location_latitude' => 2.5259,
                'event_location_longitude' => 101.8020,
                'event_location_address' => 'Port Dickson Beach, Negeri Sembilan, Malaysia',
                'event_start_date' => $pastDate1->format('Y-m-d'),
                'event_end_date' => $pastDate1->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 50,
                'event_current_participant' => 12,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/86efac/052e16?text=Beach+Cleanup',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $danielUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Daniel Youth Movement',
                'event_name' => 'Blood Donation Campaign',
                'event_description' => 'Donate blood and save lives! Join our blood donation campaign in collaboration with the National Blood Bank. Each donation can save up to three lives. Please ensure you are healthy and meet the requirements before coming. Refreshments will be provided for all donors.',
                'event_location_name' => 'Community Hall, Bangi',
                'event_location_latitude' => 2.9287,
                'event_location_longitude' => 101.7723,
                'event_location_address' => 'Bangi, Selangor, Malaysia',
                'event_start_date' => $futureDate7->format('Y-m-d'),
                'event_end_date' => $futureDate7->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 100,
                'event_current_participant' => 35,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/fca5a5/7f1d1d?text=Blood+Donation',
                'event_approval_status' => 'Pending',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => false,
                'event_post_review' => null,
                'event_approver_id' => null,
            ],
            
            // ========== NEW EVENTS - Afiqah's Events ==========
            [
                'event_created_by_id' => $afiqahUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Afiqah Women Empowerment',
                'event_name' => 'Women Entrepreneurship Workshop',
                'event_description' => 'A free workshop for women entrepreneurs! Learn about digital marketing, financial management, and business networking. Guest speakers include successful women entrepreneurs who will share their journeys and tips. Limited seats available. Pre-registration required.',
                'event_location_name' => 'Women Development Center, Shah Alam',
                'event_location_latitude' => 3.0738,
                'event_location_longitude' => 101.5183,
                'event_location_address' => 'Shah Alam, Selangor, Malaysia',
                'event_start_date' => $futureDate2->format('Y-m-d'),
                'event_end_date' => $futureDate2->format('Y-m-d'),
                'event_start_session' => 'Afternoon',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 40,
                'event_current_participant' => 32,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/fbbf24/78350f?text=Women+Workshop',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $afiqahUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Afiqah Women Empowerment',
                'event_name' => 'Mental Health Awareness Talk',
                'event_description' => 'Join us for an important conversation about mental health. Licensed psychologists will discuss common mental health issues, coping strategies, and where to seek help. This is a safe space for open discussion. Refreshments provided. Open to all adults.',
                'event_location_name' => 'Public Library, Petaling Jaya',
                'event_location_latitude' => 3.1076,
                'event_location_longitude' => 101.6103,
                'event_location_address' => 'Petaling Jaya, Selangor, Malaysia',
                'event_start_date' => $futureDate5->format('Y-m-d'),
                'event_end_date' => $futureDate5->format('Y-m-d'),
                'event_start_session' => 'Evening',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 60,
                'event_current_participant' => 45,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/93c5fd/1e40af?text=Mental+Health',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            
            // ========== NEW EVENTS - Sharifah's Events ==========
            [
                'event_created_by_id' => $sharifahUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Sharifah Foundation',
                'event_name' => 'Orphanage Visit Program',
                'event_description' => 'Spread joy to orphaned children! Join us for a day of fun activities, games, and giving back. We will be distributing school supplies, toys, and food. Volunteers needed to interact with children and help organize activities. Bring your smile and positive energy!',
                'event_location_name' => 'Rumah Anak Yatim, Kuala Lumpur',
                'event_location_latitude' => 3.1390,
                'event_location_longitude' => 101.6869,
                'event_location_address' => 'Kuala Lumpur, Malaysia',
                'event_start_date' => $futureDate6->format('Y-m-d'),
                'event_end_date' => $futureDate6->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 30,
                'event_current_participant' => 18,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/a78bfa/4c1d95?text=Orphanage',
                'event_approval_status' => 'Approved',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $sharifahUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Sharifah Foundation',
                'event_name' => 'Elderly Care Home Visit',
                'event_description' => 'Bring joy and companionship to the elderly at the care home. Activities include singing sessions, light exercises, and one-on-one conversations. Volunteers needed to assist with activities and spend quality time with the residents. Your time and attention mean the world to them.',
                'event_location_name' => 'Old Folks Home, Cheras',
                'event_location_latitude' => 3.0554,
                'event_location_longitude' => 101.7381,
                'event_location_address' => 'Cheras, Kuala Lumpur, Malaysia',
                'event_start_date' => $futureDate8->format('Y-m-d'),
                'event_end_date' => $futureDate8->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 25,
                'event_current_participant' => 10,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/86efac/052e16?text=Elderly+Care',
                'event_approval_status' => 'Pending',
                'event_status' => null,
                'event_remarks' => null,
                'event_publish' => false,
                'event_post_review' => null,
                'event_approver_id' => null,
            ],
            
            // ========== Events with Different Statuses ==========
            [
                'event_created_by_id' => $dieyardUser->user_id,
                'event_company_name' => 'Dieyard Foundation',
                'event_name' => 'Rejected Event Example',
                'event_description' => 'This event proposal was rejected due to incomplete documentation. Please ensure all required documents are submitted.',
                'event_location_name' => 'Some Location',
                'event_location_latitude' => 3.1390,
                'event_location_longitude' => 101.6869,
                'event_location_address' => 'Kuala Lumpur, Malaysia',
                'event_start_date' => $pastDate1->format('Y-m-d'),
                'event_end_date' => $pastDate1->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Morning',
                'event_maximum_participant' => 20,
                'event_current_participant' => 0,
                'event_document' => null,
                'event_picture' => null,
                'event_approval_status' => 'Rejected',
                'event_status' => 'Rejected',
                'event_remarks' => 'Incomplete documentation. Please submit proper event proposal with complete details.',
                'event_publish' => false,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $dieyardUser->user_id,
                'event_company_name' => 'Dieyard Events',
                'event_name' => 'Needs Update Event',
                'event_description' => 'This event needs to be updated with correct date and location information.',
                'event_location_name' => 'To Be Confirmed',
                'event_location_latitude' => null,
                'event_location_longitude' => null,
                'event_location_address' => null,
                'event_start_date' => $futureDate5->format('Y-m-d'),
                'event_end_date' => $futureDate5->format('Y-m-d'),
                'event_start_session' => 'Afternoon',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 15,
                'event_current_participant' => 0,
                'event_document' => null,
                'event_picture' => null,
                'event_approval_status' => 'NeedsUpdate',
                'event_status' => null,
                'event_remarks' => 'Please update the event date and location. The current date is less than 10 days away.',
                'event_publish' => false,
                'event_post_review' => null,
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            
            // ========== Past Events ==========
            [
                'event_created_by_id' => $adminUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Kasih Istimewa',
                'event_name' => 'Past Event - Successful',
                'event_description' => 'A highly successful community outreach program that brought together volunteers and residents for a day of activities and bonding.',
                'event_location_name' => 'Community Center, KL',
                'event_location_latitude' => 3.1480,
                'event_location_longitude' => 101.6950,
                'event_location_address' => 'Community Center, Kuala Lumpur, Malaysia',
                'event_start_date' => $pastDate2->format('Y-m-d'),
                'event_end_date' => $pastDate2->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 40,
                'event_current_participant' => 40,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/86efac/052e16?text=Successful',
                'event_approval_status' => 'Approved',
                'event_status' => 'Successful',
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => 'Great event! All volunteers participated actively. Raised RM10,000 for the cause.',
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $adminUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Kasih Istimewa',
                'event_name' => 'Past Event - Unsuccessful',
                'event_description' => 'An attempt to organize a large-scale fundraising concert that unfortunately faced low attendance due to weather conditions.',
                'event_location_name' => 'Shah Alam Stadium',
                'event_location_latitude' => 3.0838,
                'event_location_longitude' => 101.5367,
                'event_location_address' => 'Shah Alam Stadium, Shah Alam, Malaysia',
                'event_start_date' => $pastDate3->format('Y-m-d'),
                'event_end_date' => $pastDate3->format('Y-m-d'),
                'event_start_session' => 'Evening',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 60,
                'event_current_participant' => 25,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/fca5a5/7f1d1d?text=Unsuccessful',
                'event_approval_status' => 'Approved',
                'event_status' => 'Unsuccessful',
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => 'Low turnout due to bad weather. Only 25 out of 60 volunteers attended.',
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $dieyardUser->user_id,
                'event_company_name' => 'Dieyard Foundation',
                'event_name' => 'Dieyard Old Event',
                'event_description' => 'An older event organized by Dieyard Foundation that was successfully completed with good community participation.',
                'event_location_name' => 'Old Location, KL',
                'event_location_latitude' => 3.1502,
                'event_location_longitude' => 101.7123,
                'event_location_address' => 'Kuala Lumpur, Malaysia',
                'event_start_date' => $pastDate4->format('Y-m-d'),
                'event_end_date' => $pastDate4->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Afternoon',
                'event_maximum_participant' => 25,
                'event_current_participant' => 18,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/93c5fd/1e40af?text=Old+Event',
                'event_approval_status' => 'Approved',
                'event_status' => 'Successful',
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => 'Good participation from the community.',
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            
            // ========== Additional Past Event ==========
            [
                'event_created_by_id' => $adamUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Adam Enterprise',
                'event_name' => 'Ramadan Charity Drive',
                'event_description' => 'A successful Ramadan charity drive that distributed food baskets to 100 underprivileged families. Volunteers helped with packing and distribution.',
                'event_location_name' => 'Kuala Lumpur Mosque',
                'event_location_latitude' => 3.1400,
                'event_location_longitude' => 101.6900,
                'event_location_address' => 'Kuala Lumpur, Malaysia',
                'event_start_date' => $pastDate5->format('Y-m-d'),
                'event_end_date' => $pastDate5->format('Y-m-d'),
                'event_start_session' => 'Morning',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 50,
                'event_current_participant' => 50,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/fbbf24/78350f?text=Ramadan',
                'event_approval_status' => 'Approved',
                'event_status' => 'Successful',
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => 'Wonderful community spirit! All 50 volunteers participated actively.',
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
            [
                'event_created_by_id' => $alifUser->user_id ?? $dieyardUser->user_id,
                'event_company_name' => 'Alif Care',
                'event_name' => 'Online Fundraising Webinar',
                'event_description' => 'A successful online webinar about fundraising strategies for NGOs. Attended by 200 participants from across Malaysia.',
                'event_location_name' => 'Online (Zoom)',
                'event_location_latitude' => null,
                'event_location_longitude' => null,
                'event_location_address' => 'Virtual Event',
                'event_start_date' => $pastDate6->format('Y-m-d'),
                'event_end_date' => $pastDate6->format('Y-m-d'),
                'event_start_session' => 'Evening',
                'event_end_session' => 'Evening',
                'event_maximum_participant' => 200,
                'event_current_participant' => 200,
                'event_document' => null,
                'event_picture' => 'https://placehold.co/600x400/a78bfa/4c1d95?text=Webinar',
                'event_approval_status' => 'Approved',
                'event_status' => 'Successful',
                'event_remarks' => null,
                'event_publish' => true,
                'event_post_review' => 'Excellent engagement! Many participants signed up for future events.',
                'event_approver_id' => $adminUser->user_id ?? null,
            ],
        ];

        foreach ($events as $data) {
            Event::create($data);
        }
        
        // Output success message
        $this->command->info('Events seeded successfully!');
        $this->command->info('Total events created: ' . count($events));
        
        $dieyardEvents = Event::where('event_created_by_id', $dieyardUser->user_id)->count();
        $this->command->info("DIEYARD user has {$dieyardEvents} events.");
        
        if ($aneUser) {
            $aneEvents = Event::where('event_created_by_id', $aneUser->user_id)->count();
            $this->command->info("ANE user has {$aneEvents} events.");
        }
        
        if ($danielUser) {
            $danielEvents = Event::where('event_created_by_id', $danielUser->user_id)->count();
            $this->command->info("DANIEL user has {$danielEvents} events.");
        }
        
        if ($afiqahUser) {
            $afiqahEvents = Event::where('event_created_by_id', $afiqahUser->user_id)->count();
            $this->command->info("AFIQAH user has {$afiqahEvents} events.");
        }
        
        if ($sharifahUser) {
            $sharifahEvents = Event::where('event_created_by_id', $sharifahUser->user_id)->count();
            $this->command->info("SHARIFAH user has {$sharifahEvents} events.");
        }
    }
}