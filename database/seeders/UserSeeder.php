<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
        $users = [
            // ========== EXISTING USERS (KEEP ALL) ==========
            [
                "user_name" => "User1",
                "user_email" => "User1@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0123456789",
                "user_dob" => "2003-05-26",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "User2",
                "user_email" => "user2@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0122222222",
                "user_dob" => "2006-07-07",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Muhammad Hidayat",
                "user_email" => "hidayat@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0138834062",
                "user_dob" => "2003-05-27",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762231944/Kasih_Istimewa/Profile_Picture/user_U0003_1762231940.jpg",
            ],
            [
                "user_name" => "Ahmad Naufal Bin Hamdan",
                "user_email" => "ahmadnaufalhamdan@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0136523141",
                "user_dob" => "2003-08-28",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762232467/Kasih_Istimewa/Profile_Picture/user_U0004_1762232465.png",
            ],
            [
                "user_name" => "Admin1",
                "user_email" => "admin1@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0136523141",
                "user_dob" => "2003-07-10",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762076077/profile_photos/user_11_1762076074.jpg",
            ],
            [
                "user_name" => "Taro",
                "user_email" => "taro@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0321654987",
                "user_dob" => "2006-06-15",
                "is_admin" => 0,
                "user_status" => "deactivated",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762231660/Kasih_Istimewa/Profile_Picture/user_U0008_1762231657.jpg",
            ],
            [
                "user_name" => "Adam Muqrish",
                "user_email" => "adam@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0111111111",
                "user_dob" => "2000-01-01",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762246291/Kasih_Istimewa/Profile_Picture/user_U0009_1762246289.png",
            ],
            [
                "user_name" => "Alif Aminudin",
                "user_email" => "alif@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0122222222",
                "user_dob" => "2001-02-02",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762232560/Kasih_Istimewa/Profile_Picture/user_U0006_1762232559.png",
            ],
            [
                "user_name" => "Ipan Arsyad",
                "user_email" => "ipan@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0133333333",
                "user_dob" => "2002-03-03",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762246251/Kasih_Istimewa/Profile_Picture/user_U0011_1762246247.png",
            ],
            [
                "user_name" => "Muhammad Icam",
                "user_email" => "icam@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0144444444",
                "user_dob" => "2003-04-04",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762246748/Kasih_Istimewa/Profile_Picture/user_U0012_1762246747.png",
            ],
            [
                "user_name" => "Muhammad Ilyas",
                "user_email" => "ilyas@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0155555555",
                "user_dob" => "1975-05-05",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762246270/Kasih_Istimewa/Profile_Picture/user_U0010_1762246268.png",
            ],
            [
                "user_name" => "Sarip",
                "user_email" => "sarip@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0166666666",
                "user_dob" => "2005-06-06",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762246763/Kasih_Istimewa/Profile_Picture/user_U0013_1762246761.png",
            ],
            [
                "user_name" => "Dum",
                "user_email" => "dum@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0177777777",
                "user_dob" => "2006-07-07",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762246782/Kasih_Istimewa/Profile_Picture/user_U0014_1762246780.png",
            ],
            [
                "user_name" => "Hidayat Jr",
                "user_email" => "hidayatjr@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0188888888",
                "user_dob" => "2007-08-08",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762232176/Kasih_Istimewa/Profile_Picture/user_U0007_1762232174.png",
            ],
            [
                "user_name" => "Ane Bin Qaiyum Akashah",
                "user_email" => "qaiyumakashah0929@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0123456789",
                "user_dob" => "2003-10-21",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Hidayat Bin Dieyard",
                "user_email" => "dieyard.dhr@gmail.com",
                "user_password" => Hash::make('Hidayat265'),
                "user_phone_number" => "0138834062",
                "user_dob" => "2003-01-28",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => "https://res.cloudinary.com/dvwfqplh3/image/upload/v1762231660/Kasih_Istimewa/Profile_Picture/user_U0008_1762231657.jpg",
            ],

            // ========== ADDITIONAL REALISTIC MALAYSIAN USERS ==========
            // More University Students
            [
                "user_name" => "Muhammad Daniel Bin Abdullah",
                "user_email" => "daniel@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0191234567",
                "user_dob" => "2001-03-15",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Nurul Afiqah Binti Zainal",
                "user_email" => "afiqah@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0112345678",
                "user_dob" => "2002-06-22",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Amirul Hakim Bin Mohamad",
                "user_email" => "amirul@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0129876543",
                "user_dob" => "2000-11-30",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],

            // Working Professionals
            [
                "user_name" => "Sharifah Nurul Aida",
                "user_email" => "sharifah@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0187654321",
                "user_dob" => "1990-08-10",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Mohamad Rafiq Bin Jamaludin",
                "user_email" => "rafiq@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0176543210",
                "user_dob" => "1995-12-05",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Farah Nabilah Binti Azman",
                "user_email" => "farah@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0165432109",
                "user_dob" => "1993-04-18",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],

            // Seniors/Experienced Volunteers
            [
                "user_name" => "Abu Bakar Bin Hassan",
                "user_email" => "abubakar@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0198765432",
                "user_dob" => "1965-02-28",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Rohaya Binti Ismail",
                "user_email" => "rohaya@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0123456780",
                "user_dob" => "1970-07-15",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],

            // International / Expat Volunteer
            [
                "user_name" => "John Smith",
                "user_email" => "johnsmith@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0101234567",
                "user_dob" => "1988-09-20",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],

            // ========== ADDITIONAL ADMIN USERS (without ADMIN prefix) ==========
            [
                "user_name" => "Suhaili",
                "user_email" => "suhaili@admin.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0198881111",
                "user_dob" => "1985-11-15",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Zulkifli",
                "user_email" => "zulkifli@admin.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0123451111",
                "user_dob" => "1990-03-20",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Mastura",
                "user_email" => "mastura@admin.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0176665555",
                "user_dob" => "1988-07-25",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Syafiq",
                "user_email" => "syafiq@admin.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0139994444",
                "user_dob" => "1992-09-12",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Rosli",
                "user_email" => "rosli@admin.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "0147773333",
                "user_dob" => "1979-05-08",
                "is_admin" => 1,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],

            // ========== MORE REGULAR USERS ==========
            [
                "user_name" => "Siti Nurhaliza Binti Abdullah",
                "user_email" => "siti.nurhaliza@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01111223344",
                "user_dob" => "1998-02-14",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Vinod A/L Kumar",
                "user_email" => "vinod.kumar@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01677889900",
                "user_dob" => "1995-06-30",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Tan Bee Choo",
                "user_email" => "tan.beechoo@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01255667788",
                "user_dob" => "1992-10-05",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Mohamed Shah Bin Othman",
                "user_email" => "mohamed.shah@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01345677889",
                "user_dob" => "1987-12-19",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Lydia Nicole David",
                "user_email" => "lydia.nicole@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01499887766",
                "user_dob" => "1999-04-23",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Prema A/P Sivanesan",
                "user_email" => "prema.siva@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01755443322",
                "user_dob" => "1996-08-17",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Wong Chun Keat",
                "user_email" => "wong.keat@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01098766554",
                "user_dob" => "1994-01-09",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Nor Asyikin Binti Mohamad",
                "user_email" => "asyikin.mohamad@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01923344556",
                "user_dob" => "1997-11-28",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Gerald Anak Joseph",
                "user_email" => "gerald.joseph@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01890099887",
                "user_dob" => "1993-03-11",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
            [
                "user_name" => "Chong Hui Min",
                "user_email" => "chong.huimin@gmail.com",
                "user_password" => Hash::make('11111111'),
                "user_phone_number" => "01678899556",
                "user_dob" => "2000-07-06",
                "is_admin" => 0,
                "user_status" => "active",
                "user_profile_picture" => null,
            ],
        ];

        foreach ($users as $data) {
            // Check if user already exists by email to avoid duplicates
            User::updateOrCreate(
                ['user_email' => $data['user_email']],
                $data
            );
        }
        
        $this->command->info('Users seeded successfully!');
        $this->command->info('Total users: ' . User::count());
        $this->command->info('Admin users: ' . User::where('is_admin', 1)->count());
        $this->command->info('Regular users: ' . User::where('is_admin', 0)->count());
        $this->command->info('Active users: ' . User::where('user_status', 'active')->count());
        $this->command->info('Deactivated users: ' . User::where('user_status', 'deactivated')->count());
        
        // List all admin users
        $admins = User::where('is_admin', 1)->get();
        $this->command->info("\n📋 Admin Users List:");
        foreach ($admins as $admin) {
            $this->command->info("  • {$admin->user_name} ({$admin->user_email}) - {$admin->user_id}");
        }
    }
}