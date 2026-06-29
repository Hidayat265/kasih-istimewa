<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // ============================================
    // PROFILE MANAGEMENT
    // ============================================
    
    /**
     * Helper function to capitalize each word in a name
     */
    private function capitalizeName($name)
    {
        // Handle empty or null
        if (empty($name)) {
            return $name;
        }
        
        // Split by spaces and capitalize each part
        $parts = explode(' ', $name);
        $capitalizedParts = array_map(function($part) {
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

    /**
     * Show user profile page
     */
    public function showProfile()
    {
        return view('user.profile.index');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            \Log::info('Profile update request received', [
                'user_id' => Auth::id(),
                'has_file' => $request->hasFile('user_profile_picture'),
                'all_inputs' => $request->all()
            ]);
            
            $user = Auth::user();
            
            $rules = [
                'user_name' => 'required|string|max:255',
                'user_phone_number' => 'required|string|min:10|max:11',
                'user_profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];
            
            $rules['user_email'] = 'required|email|max:255|unique:users,user_email,' . Auth::id() . ',user_id';
            
            if ($request->user_dob != $user->user_dob) {
                $rules['user_dob'] = 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d');
                $validateDob = true;
            } else {
                $rules['user_dob'] = 'nullable|date';
                $validateDob = false;
            }
            
            $request->validate($rules, [
                'user_dob.before_or_equal' => 'You must be at least 18 years old.',
                'user_phone_number.min' => 'Phone number must be at least 10 digits.',
                'user_phone_number.max' => 'Phone number cannot exceed 11 digits.',
                'user_phone_number.required' => 'Phone number is required.',
            ]);

            $age = null;
            if ($request->user_dob) {
                $dob = Carbon::parse($request->user_dob);
                $age = $dob->age;
            }
            
            // Handle profile picture upload
            if ($request->hasFile('user_profile_picture')) {
                try {
                    $file = $request->file('user_profile_picture');
                    \Log::info('Profile picture file detected', [
                        'user_id' => $user->user_id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_mime' => $file->getMimeType()
                    ]);
                    
                    // Try Cloudinary upload
                    try {
                        $uploader = app('cloudinary.uploader');
                        $user->user_profile_picture = $uploader->uploadProfilePicture($file, $user->user_id);
                        \Log::info('Profile picture uploaded to Cloudinary', [
                            'user_id' => $user->user_id,
                            'url' => $user->user_profile_picture
                        ]);
                    } catch (\Exception $cloudinaryError) {
                        \Log::error('Cloudinary upload failed, trying local storage: ' . $cloudinaryError->getMessage());
                        
                        // Fallback to local storage
                        $filename = 'profile_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('profile_pictures', $filename, 'public');
                        $user->user_profile_picture = '/storage/' . $path;
                        
                        \Log::info('Profile picture uploaded locally', [
                            'user_id' => $user->user_id,
                            'path' => $path
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage(), [
                        'user_id' => $user->user_id,
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Image upload failed: ' . $e->getMessage()
                        ], 500);
                    }
                    return back()->with('error', 'Image upload failed: ' . $e->getMessage());
                }
            }

            // Capitalize the name before saving
            $capitalizedName = $this->capitalizeName($request->user_name);

            $user->update([
                'user_name' => $capitalizedName,
                'user_email' => $request->user_email,
                'user_phone_number' => $request->user_phone_number,
                'user_dob' => $request->user_dob,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'profile_picture' => $user->user_profile_picture
                ]);
            }

            if ($validateDob && $age !== null && $age >= 65) {
                return back()->with('status', 'Profile updated successfully.')->with('warning', 'Note: Volunteers above 65 are welcome, but some roles may be limited for safety reasons.');
            }

            return back()->with('status', 'Profile updated successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . json_encode($e->errors()));
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove profile picture
     */
    public function removeProfilePicture(Request $request)
    {
        $user = Auth::user();
        $user->user_profile_picture = null;
        $user->save();
        return back()->with('success', 'Profile picture removed successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->user_password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->user_password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    // ============================================
    // USER LIST
    // ============================================
    
    public function listUsersAdmin(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $sort = $request->query('sort', 'user_id');
        $direction = $request->query('direction', 'desc');

        $query = User::where('is_admin', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_id', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('user_email', 'LIKE', "%{$search}%")
                  ->orWhere('user_phone_number', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('user_status', $status);
        }

        $sortableColumns = ['user_id', 'user_name', 'user_email', 'user_phone_number', 'user_dob', 'user_status'];
        if (in_array($sort, $sortableColumns)) {
            if ($sort === 'user_dob') {
                $query->orderByRaw("TIMESTAMPDIFF(YEAR, user_dob, CURDATE()) " . ($direction === 'asc' ? 'ASC' : 'DESC'));
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('user_id', 'desc');
        }

        $users = $query->paginate(10)->appends($request->query());

        $totalUsers = User::where('is_admin', 0)->count();
        $newUsersCount = User::where('is_admin', 0)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $activeUsers = User::where('is_admin', 0)->where('user_status', 'active')->count();
        $deactivatedUsers = User::where('is_admin', 0)->where('user_status', 'deactivated')->count();

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.users.partials.users-table', compact('users'))->render();
            $paginationHtml = view('admin.users.partials.pagination', compact('users'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $paginationHtml,
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'total' => $users->total(),
                'stats' => [
                    'totalUsers' => $totalUsers,
                    'newUsersCount' => $newUsersCount,
                    'activeUsers' => $activeUsers,
                    'deactivatedUsers' => $deactivatedUsers
                ]
            ]);
        }

        return view('admin.users.index', compact('users', 'search', 'totalUsers', 'newUsersCount', 'activeUsers', 'deactivatedUsers'));
    }

    // ============================================
    // ADMIN LIST
    // ============================================
    
    public function listAdminsAdmin(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $sort = $request->query('sort', 'user_id');
        $direction = $request->query('direction', 'desc');

        $query = User::where('is_admin', 1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_id', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('user_email', 'LIKE', "%{$search}%")
                  ->orWhere('user_phone_number', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('user_status', $status);
        }

        $sortableColumns = ['user_id', 'user_name', 'user_email', 'user_phone_number', 'user_dob', 'user_status'];
        if (in_array($sort, $sortableColumns)) {
            if ($sort === 'user_dob') {
                $query->orderByRaw("TIMESTAMPDIFF(YEAR, user_dob, CURDATE()) " . ($direction === 'asc' ? 'ASC' : 'DESC'));
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('user_id', 'desc');
        }

        $users = $query->paginate(10)->appends($request->query());

        $totalAdmins = User::where('is_admin', 1)->count();
        $newAdminsCount = User::where('is_admin', 1)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $activeAdmins = User::where('is_admin', 1)->where('user_status', 'active')->count();
        $deactivatedAdmins = User::where('is_admin', 1)->where('user_status', 'deactivated')->count();

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.admins.partials.admins-table', compact('users'))->render();
            $paginationHtml = view('admin.admins.partials.pagination', compact('users'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $paginationHtml,
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'total' => $users->total(),
                'stats' => [
                    'totalAdmins' => $totalAdmins,
                    'newAdminsCount' => $newAdminsCount,
                    'activeAdmins' => $activeAdmins,
                    'deactivatedAdmins' => $deactivatedAdmins
                ]
            ]);
        }

        return view('admin.admins.index', compact('users', 'search', 'totalAdmins', 'newAdminsCount', 'activeAdmins', 'deactivatedAdmins'));
    }

    // ============================================
    // TOGGLE STATUS
    // ============================================
    
    public function toggleAdminStatus($id)
    {
        try {
            $admin = User::findOrFail($id);
            
            if ($admin->user_id === auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot change your own status.'
                ], 403);
            }
            
            if ($admin->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This admin is already active.'
                ], 400);
            }
            
            $admin->user_status = 'active';
            $admin->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Admin activated successfully!',
                'new_status' => 'active'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate admin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleUserStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->is_admin == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot change admin status from this page.'
                ], 403);
            }
            
            // If deactivating, verify admin password
            $status = $request->status;
            if ($status === 'deactivated') {
                $adminPassword = $request->admin_password;
                if (!$adminPassword) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Admin password is required to deactivate user.'
                    ], 400);
                }
                
                $admin = auth()->user();
                if (!Hash::check($adminPassword, $admin->user_password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Incorrect admin password. Please try again.'
                    ], 403);
                }
            }
            
            $user->user_status = $status;
            $user->save();
            
            $action = $status === 'active' ? 'activated' : 'deactivated';
            
            return response()->json([
                'success' => true,
                'message' => "User {$action} successfully!",
                'new_status' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle user status: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // USER DETAILS
    // ============================================

    public function userDetailsAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Get event data with pagination (5 per page)
        $eventsPage = $request->get('events_page', 1);
        $donationsPage = $request->get('donations_page', 1);
        
        $events = $user->events()->orderBy('created_at', 'desc')->paginate(5, ['*'], 'events_page', $eventsPage);
        $eventCount = $user->events()->count();
        
        // Fix: Use participant_status instead of status
        $participantCount = Participant::where('user_id', $user->user_id)
            ->where('participant_status', 'confirmed')
            ->count();
        
        // Get donation data with pagination (5 per page)
        $donations = Donation::where('donor_email', $user->user_email)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'donations_page', $donationsPage);
        $donationCount = Donation::where('donor_email', $user->user_email)->count();
        $donationAmount = Donation::where('donor_email', $user->user_email)
            ->where('donation_status', 'success')
            ->sum('donation_amount');

        // Handle AJAX requests for pagination
        if ($request->ajax()) {
            if ($request->has('events_page')) {
                return view('admin.users.partials.events-table', compact('events'))->render();
            }
            if ($request->has('donations_page')) {
                return view('admin.users.partials.donations-table', compact('donations'))->render();
            }
        }

        if ($user->is_admin == 1) {
            return view('admin.admins.profile', compact(
                'user', 
                'events', 
                'eventCount', 
                'participantCount',
                'donations', 
                'donationCount', 
                'donationAmount'
            ));
        }

        return view('admin.users.profile', compact(
            'user', 
            'events', 
            'eventCount', 
            'participantCount',
            'donations', 
            'donationCount', 
            'donationAmount'
        ));
    }

    // ============================================
    // ADMIN USER UPDATE
    // ============================================
    
    public function adminUpdateUserPage($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_admin == 1) {
            abort(403, 'You are not authorized to view another admin\'s profile.');
        }

        return view('admin.users.update', compact('user'));
    }

    public function adminUpdateDetailsUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->is_admin == 1) {
            abort(403, 'You are not authorized to view another admin\'s profile.');
        }

        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,user_email,' . $user->user_id . ',user_id',
            'user_phone_number' => 'nullable|string|max:15',
            'user_dob' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'user_profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'user_dob.before_or_equal' => 'User must be at least 18 years old.'
        ]);

        $dob = Carbon::parse($request->user_dob);
        $age = $dob->age;

        if ($request->hasFile('user_profile_picture')) {
            try {
                $uploader = app('cloudinary.uploader');
                $user->user_profile_picture = $uploader->uploadProfilePicture(
                    $request->file('user_profile_picture'),
                    $user->user_id
                );
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        // Capitalize the name before saving
        $capitalizedName = $this->capitalizeName($request->user_name);

        $user->update([
            'user_name' => $capitalizedName,
            'user_email' => $request->user_email,
            'user_phone_number' => $request->user_phone_number,
            'user_dob' => $request->user_dob,
        ]);

        if ($age >= 65) {
            return redirect()
                ->route('admin.users.profile', $user->user_id)
                ->with('status', 'User details updated successfully.')
                ->with('warning', 'Note: Volunteers above 65 are welcome, but some roles may be limited for safety reasons.');
        }

        return redirect()
            ->route('admin.users.profile', $user->user_id)
            ->with('status', 'User details updated successfully.')
            ->with('success', 'User details updated successfully.');
    }

    public function adminRemoveUserProfilePicture($id)
    {
        $user = User::findOrFail($id);
        $user->user_profile_picture = null;
        $user->save();
        return back()->with('success', 'User profile picture removed successfully.');
    }

    // ============================================
    // ADMIN PASSWORD UPDATE
    // ============================================
    
    public function adminUpdateUserPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'admin_password' => ['required', 'string'],
        ]);

        $admin = auth()->user();
        $user = User::findOrFail($id);

        if ($user->is_admin == 1) {
            abort(403, 'You are not authorized to view another admin\'s profile.');
        }

        if (!Hash::check($request->admin_password, $admin->user_password)) {
            return back()->with('error', 'Incorrect admin password. Password update failed.');
        }

        if ($user->user_id === $admin->user_id) {
            return back()->with('error', 'Please change your own password in your profile settings.');
        }

        $user->user_password = Hash::make($request->new_password);
        $user->save();

        return redirect()
            ->route('admin.users.profile', $user->user_id)
            ->with('status', 'Password updated successfully for ' . $user->user_name . '!');
    }

    // ============================================
    // ADMIN DEACTIVATE USER
    // ============================================
    
    public function adminDeactivateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->is_admin == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot deactivate an admin from this page.'
                ], 403);
            }
            
            if ($user->user_id === auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot deactivate your own account.'
                ], 403);
            }
            
            // Verify admin password
            $adminPassword = $request->admin_password;
            if (!$adminPassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin password is required to deactivate user.'
                ], 400);
            }
            
            $admin = auth()->user();
            if (!Hash::check($adminPassword, $admin->user_password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect admin password. Please try again.'
                ], 403);
            }
            
            $user->user_status = 'deactivated';
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'User deactivated successfully!',
                'new_status' => 'deactivated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate user: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // STORE ADMIN
    // ============================================
    
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', 'unique:users,user_email'],
            'user_phone_number' => ['nullable', 'string', 'min:10', 'max:11'],
            'user_dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'user_password' => ['required', 'confirmed', Rules\Password::min(8)],
        ], [
            'user_dob.before_or_equal' => 'Admin must be at least 18 years old.'
        ]);

        $lastUser = User::orderBy('user_id', 'desc')->first();
        if (!$lastUser) {
            $userId = 'USR-0001';
        } else {
            $number = intval(substr($lastUser->user_id, 4));
            $userId = 'USR-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        }

        // Capitalize the name
        $capitalizedName = $this->capitalizeName($request->user_name);

        $user = User::create([
            'user_id' => $userId,
            'user_name' => $capitalizedName,
            'user_email' => $request->user_email,
            'user_phone_number' => $request->user_phone_number,
            'user_dob' => $request->user_dob,
            'user_password' => Hash::make($request->user_password),
            'is_admin' => 1,
            'user_email_verified_at' => now(),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'New admin created successfully.');
    }

    // ============================================
    // DASHBOARDS
    // ============================================
    
    public function showDashboard()
    {
        $userId = auth()->user()->user_id;
        $userEmail = auth()->user()->user_email;
        
        // Fix: Use participant_status instead of status
        $registeredEventsCount = Participant::where('user_id', $userId)
            ->where('participant_status', 'confirmed')
            ->count();
        
        $upcomingRegisteredEvents = Participant::with('event')
            ->where('user_id', $userId)
            ->where('participant_status', 'confirmed')
            ->whereHas('event', function($query) {
                $query->where('event_start_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $myEventsCount = Event::where('event_created_by_id', $userId)->count();
        
        $totalDonations = Donation::where('donor_email', $userEmail)->count();
        $totalDonatedAmount = Donation::where('donor_email', $userEmail)
            ->where('donation_status', 'success')
            ->sum('donation_amount');
        
        $recentDonations = Donation::where('donor_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('user.dashboard', compact(
            'registeredEventsCount',
            'upcomingRegisteredEvents',
            'myEventsCount',
            'totalDonations',
            'totalDonatedAmount',
            'recentDonations'
        ));
    }

    public function showAdminDashboard()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $newUsersCount = User::where('is_admin', 0)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();
        
        $pendingEventsCount = Event::where('event_approval_status', 'Pending')->count();
        
        $donationsThisMonth = Donation::where('donation_status', 'success')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('donation_amount');
        
        $totalEvents = Event::count();
        
        // Fix: Use participant_status instead of status
        $totalParticipants = Participant::where('participant_status', 'confirmed')
            ->distinct('user_id')
            ->count('user_id');

        // Chart Data: Event Status Distribution
        $eventStatusData = Event::selectRaw('event_approval_status, count(*) as count')
            ->groupBy('event_approval_status')
            ->pluck('count', 'event_approval_status')
            ->toArray();

        // Chart Data: Monthly Events (Last 6 Months)
        $monthlyEventsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Event::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyEventsData[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Recent Events for table
        $events = Event::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dashboard.index', compact(
            'newUsersCount',
            'pendingEventsCount',
            'donationsThisMonth',
            'totalEvents',
            'totalParticipants',
            'eventStatusData',
            'monthlyEventsData',
            'events'
        ));
    }

    /**
     * Get dashboard events data for AJAX
     */
    public function getDashboardEventsData(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $query = Event::with('creator')
            ->orderBy($sort, $direction);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                ->orWhere('event_id', 'like', "%{$search}%")
                ->orWhere('event_company_name', 'like', "%{$search}%")
                ->orWhere('event_location_name', 'like', "%{$search}%")
                ->orWhereHas('creator', function($q2) use ($search) {
                    $q2->where('user_name', 'like', "%{$search}%");
                });
            });
        }

        if ($status) {
            $query->where('event_approval_status', $status);
        }

        $events = $query->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.dashboard.partials.events-table', compact('events'))->render();
            $pagination = view('admin.dashboard.partials.pagination', compact('events'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
                'from' => $events->firstItem(),
                'to' => $events->lastItem(),
                'total' => $events->total(),
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show Admin Report (PDF/HTML)
     */
    public function adminReport(Request $request)
    {
        $format = $request->get('format', 'html');

        $admins = User::where('is_admin', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAdmins = $admins->count();
        $activeAdmins = $admins->where('user_status', 'active')->count();
        $inactiveAdmins = $admins->where('user_status', '!=', 'active')->count();
        $newAdminsCount = User::where('is_admin', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate admin actions
        $approvedEvents = Event::where('event_approval_status', 'Approved')
            ->whereNotNull('event_approver_id')
            ->count();
        
        $rejectedEvents = Event::where('event_approval_status', 'Rejected')
            ->whereNotNull('event_approver_id')
            ->count();
        
        $updateRequests = Event::where('event_approval_status', 'NeedsUpdate')
            ->whereNotNull('event_approver_id')
            ->count();

        $totalActions = $approvedEvents + $rejectedEvents + $updateRequests;

        // If PDF format requested, generate PDF
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.admins.report', compact(
                'admins',
                'totalAdmins',
                'activeAdmins',
                'inactiveAdmins',
                'newAdminsCount',
                'totalActions',
                'approvedEvents',
                'rejectedEvents',
                'updateRequests'
            ));
            return $pdf->download('admin-report.pdf');
        }

        return view('admin.admins.report', compact(
            'admins',
            'totalAdmins',
            'activeAdmins',
            'inactiveAdmins',
            'newAdminsCount',
            'totalActions',
            'approvedEvents',
            'rejectedEvents',
            'updateRequests'
        ));
    }

    /**
     * Show User Report (PDF/HTML)
     */
    public function userReport(Request $request)
    {
        $format = $request->get('format', 'html');

        $users = User::where('is_admin', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsers = $users->count();
        $activeUsers = $users->where('user_status', 'active')->count();
        $inactiveUsers = $users->where('user_status', '!=', 'active')->count();
        $newUsersCount = User::where('is_admin', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate user activity
        $totalDonations = \App\Models\Donation::where('donation_status', 'success')->count();
        $totalEvents = \App\Models\Event::count();
        $totalParticipants = \App\Models\Participant::count();

        // If PDF format requested, generate PDF
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.users.report', compact(
                'users',
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'newUsersCount',
                'totalDonations',
                'totalEvents',
                'totalParticipants'
            ));
            return $pdf->download('user-report.pdf');
        }

        return view('admin.users.report', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'newUsersCount',
            'totalDonations',
            'totalEvents',
            'totalParticipants'
        ));
    }
}