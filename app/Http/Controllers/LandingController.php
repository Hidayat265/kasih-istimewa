<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // ===== FUNDRAISING GOAL =====
        $fundraisingGoal = 500000; // RM 500,000 goal
        $totalDonations = Donation::where('donation_status', 'success')->sum('donation_amount');
        $goalProgress = $totalDonations > 0 ? ($totalDonations / $fundraisingGoal) * 100 : 0;
        $goalProgress = min($goalProgress, 100); // Cap at 100%

        // ===== FUNDS RAISED THIS YEAR =====
        $fundsRaisedThisYear = Donation::where('donation_status', 'success')
            ->whereYear('created_at', now()->year)
            ->sum('donation_amount');

        // ===== ACTIVE USERS (user_status = 'active') =====
        $activeUsers = User::where('user_status', 'active')->count();

        // ===== EVENTS HOSTED (approved events that have ended) =====
        $eventsHosted = Event::where('event_approval_status', 'Approved')
            ->where('event_end_date', '<', now())
            ->count();

        // ===== UPCOMING EVENTS =====
        $upcomingEvents = Event::where('event_approval_status', 'Approved')
            ->where('event_publish', true)
            ->where('event_start_date', '>=', now())
            ->orderBy('event_start_date', 'asc')
            ->limit(10)
            ->get();

        // ===== COMPILE STATS =====
        $stats = [
            'fundraising_goal' => $fundraisingGoal,
            'goal_progress' => round($goalProgress, 1),
            'total_donations' => $totalDonations,
            'funds_raised_this_year' => $fundsRaisedThisYear,
            'active_users' => $activeUsers,
            'events_hosted' => $eventsHosted,
        ];

        return view('index', compact('upcomingEvents', 'stats'));
    }
}