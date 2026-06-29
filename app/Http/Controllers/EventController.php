<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Participant;
use App\Mail\EventCreated;
use App\Mail\NewEvent;
use App\Mail\EventApproved;
use App\Mail\EventRejected;
use App\Mail\EventNeedsUpdate;
use App\Mail\EventPublished;
use App\Mail\EventUnpublished;
use App\Mail\EventUpdated;
use App\Mail\EventUpdatedAdminNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    private function sessionOrder(): array
    {
        return ['Morning', 'Afternoon', 'Evening'];
    }

    private function sessionIndex(string $session): int
    {
        return array_search($session, $this->sessionOrder());
    }

    private function sessionsBetween(string $from, string $to): array
    {
        $order = $this->sessionOrder();
        $fromIndex = $this->sessionIndex($from);
        $toIndex = $this->sessionIndex($to);

        if ($fromIndex === false || $toIndex === false) {
            return [];
        }

        if ($fromIndex > $toIndex) {
            [$fromIndex, $toIndex] = [$toIndex, $fromIndex];
        }

        return array_slice($order, $fromIndex, $toIndex - $fromIndex + 1);
    }

    private function buildEventSlots(string $startDate, string $endDate, string $startSession, string $endSession): array
    {
        $slots = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $order = $this->sessionOrder();

        while ($current <= $end) {
            $date = $current->format('Y-m-d');
            if ($date === $startDate && $date === $endDate) {
                $sessions = $this->sessionsBetween($startSession, $endSession);
            } elseif ($date === $startDate) {
                $sessions = $this->sessionsBetween($startSession, 'Evening');
            } elseif ($date === $endDate) {
                $sessions = $this->sessionsBetween('Morning', $endSession);
            } else {
                $sessions = $order;
            }

            foreach ($sessions as $session) {
                $slots[] = "$date|$session";
            }

            $current->addDay();
        }

        return $slots;
    }

    private function getSessionsForDate(Event $event, string $date): array
    {
        $startDate = Carbon::parse($event->event_start_date)->format('Y-m-d');
        $endDate = Carbon::parse($event->event_end_date)->format('Y-m-d');
        $startSession = $event->event_start_session;
        $endSession = $event->event_end_session;

        if ($date < $startDate || $date > $endDate) {
            return [];
        }

        if ($date === $startDate && $date === $endDate) {
            return $this->sessionsBetween($startSession, $endSession);
        }

        if ($date === $startDate) {
            return $this->sessionsBetween($startSession, 'Evening');
        }

        if ($date === $endDate) {
            return $this->sessionsBetween('Morning', $endSession);
        }

        return $this->sessionOrder();
    }

    private function buildBookedSessionsMap(string $startDate, string $endDate): array
    {
        $map = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
            $map[$current->format('Y-m-d')] = [];
            $current->addDay();
        }

        $events = Event::where('event_approval_status', '!=', 'Rejected')
            ->where('event_start_date', '<=', $endDate)
            ->where('event_end_date', '>=', $startDate)
            ->get();

        foreach ($events as $event) {
            $current = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            while ($current <= $end) {
                $date = $current->format('Y-m-d');
                $sessions = $this->getSessionsForDate($event, $date);
                foreach ($sessions as $session) {
                    if (!in_array($session, $map[$date], true)) {
                        $map[$date][] = $session;
                    }
                }
                $current->addDay();
            }
        }

        return $map;
    }

    private function hasSlotConflict(array $requestedSlots, $events): bool
    {
        $existingSlots = [];

        foreach ($events as $event) {
            $slots = $this->buildEventSlots(
                Carbon::parse($event->event_start_date)->format('Y-m-d'),
                Carbon::parse($event->event_end_date)->format('Y-m-d'),
                $event->event_start_session,
                $event->event_end_session
            );

            foreach ($slots as $slot) {
                $existingSlots[$slot] = true;
            }
        }

        foreach ($requestedSlots as $slot) {
            if (isset($existingSlots[$slot])) {
                return true;
            }
        }

        return false;
    }

    // Get booked sessions for a specific date
    public function getBookedSessions(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');

        $events = Event::where('event_approval_status', '!=', 'Rejected')
            ->where('event_start_date', '<=', $date)
            ->where('event_end_date', '>=', $date)
            ->get();

        $bookedSessions = [];

        foreach ($events as $event) {
            $sessions = $this->getSessionsForDate($event, $date);
            foreach ($sessions as $session) {
                if (!in_array($session, $bookedSessions, true)) {
                    $bookedSessions[] = $session;
                }
            }
        }

        return response()->json([
            'booked_sessions' => $bookedSessions,
        ]);
    }

    // Get booked sessions for a date range
    public function getBookedSessionsForRange(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->end_date)->format('Y-m-d');

        $bookedMap = $this->buildBookedSessionsMap($startDate, $endDate);

        return response()->json([
            'booked_sessions' => $bookedMap,
        ]);
    }

    // Get upcoming events for the index page
    public function index()
    {
        $events = Event::where('event_approval_status', 'Approved')
            ->where('event_publish', true)
            ->where('event_start_date', '>=', Carbon::now()->subDays(1))
            ->orderBy('event_start_date', 'asc')
            ->get()
            ->map(function ($event) {
                $organizerName = $event->creator ? $event->creator->user_name : 'Unknown Organizer';
                
                return (object)[
                    'id' => $event->event_id,
                    'title' => $event->event_name,
                    'description' => $event->event_description,
                    'organizer' => $organizerName,
                    'event_created_by_id' => $event->event_created_by_id,
                    'location' => $event->event_location_name,
                    'event_date' => $event->event_start_date,
                    'end_date' => $event->event_end_date,
                    'start_session' => $event->event_start_session,
                    'end_session' => $event->event_end_session,
                    'image' => $event->event_picture,
                    'max_volunteers' => $event->event_maximum_participant,
                    'registered_volunteers' => $event->event_current_participant,
                ];
            });

        return view('user.events.upcoming.index', compact('events'));
    }

    // Show My Events page (just returns the view)
    public function myEvents()
    {
        return view('user.events.myevent.index');
    }

    // Get My Events data for AJAX (events created by the user)
    public function getMyEventsData()
    {
        $userId = auth()->user()->user_id;
        
        $events = Event::where('event_created_by_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }

    // Show single event details
    public function show($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        if ($event->event_created_by_id != auth()->user()->user_id && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user.events.myevent.show', compact('event'));
    }

    // View Edit event 
    public function edit($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        if ($event->event_created_by_id != auth()->user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        if (!in_array($event->event_approval_status, ['Pending', 'NeedsUpdate'])) {
            return redirect()->route('user.myEvents')->with('error', 'This event cannot be edited.');
        }
        
        return view('user.events.myevent.update', compact('event'));
    }

    // Update event (by organizer)
    public function update(Request $request, $id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        if ($event->event_created_by_id != auth()->user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'event_company_name' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string|max:5000',
            'event_location_name' => 'required|string|max:500',
            'event_start_date' => 'required|date|after_or_equal:' . now()->addDays(10)->format('Y-m-d'),
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'event_start_session' => 'required|in:Morning,Afternoon,Evening',
            'event_end_session' => 'required|in:Morning,Afternoon,Evening',
            'event_maximum_participant' => 'required|integer|min:0|max:1000',
            'event_document' => 'nullable|file|mimes:pdf|max:10240',
            'event_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $startDate = Carbon::parse($request->event_start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->event_end_date)->format('Y-m-d');
        $startSession = $request->event_start_session;
        $endSession = $request->event_end_session;
        
        $requestedSlots = $this->buildEventSlots($startDate, $endDate, $startSession, $endSession);
        
        $conflictingEvents = Event::where('event_approval_status', '!=', 'Rejected')
            ->where('event_id', '!=', $event->event_id)
            ->where('event_start_date', '<=', $endDate)
            ->where('event_end_date', '>=', $startDate)
            ->get();

        if ($this->hasSlotConflict($requestedSlots, $conflictingEvents)) {
            return back()->withErrors(['event_start_date' => 'The selected date and session range overlaps with an existing event.'])->withInput();
        }

        $event->event_company_name = $request->event_company_name;
        $event->event_name = $request->event_name;
        $event->event_description = $request->event_description;
        $event->event_location_name = $request->event_location_name;
        $event->event_location_latitude = $request->event_location_latitude;
        $event->event_location_longitude = $request->event_location_longitude;
        $event->event_location_address = $request->event_location_address;
        $event->event_start_date = $startDate;
        $event->event_end_date = $endDate;
        $event->event_start_session = $startSession;
        $event->event_end_session = $endSession;
        $event->event_maximum_participant = $request->event_maximum_participant;
        $event->event_approval_status = 'Pending';
        $event->event_remarks = null;

        if ($request->hasFile('event_document')) {
            $url = app('cloudinary.uploader')->uploadEventDocument($request->file('event_document'), $event->event_id);
            $event->event_document = $url;
        }

        if ($request->hasFile('event_picture')) {
            $url = app('cloudinary.uploader')->uploadEventPicture($request->file('event_picture'), $event->event_id);
            $event->event_picture = $url;
        }

        $event->save();

        // ─── SEND EMAIL NOTIFICATIONS ──────────────────────────────────────────────
        try {
            // 1. Send confirmation email to organizer
            $organizer = auth()->user();
            
            Mail::to($organizer->user_email)->send(new \App\Mail\EventUpdated(
                $event,
                $organizer->user_name,
                false // isAdminUpdate = false (organizer updated)
            ));
            \Log::info('Event update confirmation sent to organizer', [
                'email' => $organizer->user_email,
                'event_id' => $event->event_id
            ]);

            // 2. Send notification to ALL active admins
            $admins = User::where('is_admin', 1)
                ->where('user_status', 'active')
                ->get();
            
            $organizerName = $organizer->user_name;
            
            foreach ($admins as $admin) {
                Mail::to($admin->user_email)->send(new \App\Mail\EventUpdatedAdminNotification(
                    $event,
                    $organizerName,
                    $organizerName // The updater is the organizer
                ));
                \Log::info('Admin notification sent for organizer event update', [
                    'admin_email' => $admin->user_email,
                    'event_id' => $event->event_id
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to send event update emails: ' . $e->getMessage(), [
                'event_id' => $event->event_id
            ]);
        }

        return redirect()->route('user.myEvents')->with('success', 'Event updated successfully! Waiting for admin approval.');
    }

    private function getSessionTime($session): string
    {
        return match($session) {
            'Morning' => '08:00:00',
            'Afternoon' => '13:00:00',
            'Evening' => '18:00:00',
            default => '00:00:00',
        };
    }

    // Check for conflicts
    public function checkConflict(Request $request)
    {
        \Log::info('Check conflict request: ' . json_encode($request->all()));

        if ($request->has('single_date')) {
            $date = Carbon::parse($request->single_date)->format('Y-m-d');
            $session = $request->single_session;

            $events = Event::where('event_approval_status', '!=', 'Rejected')
                ->where('event_start_date', '<=', $date)
                ->where('event_end_date', '>=', $date)
                ->get();

            $requestedSlots = ["$date|$session"];
            $conflict = $this->hasSlotConflict($requestedSlots, $events);

            return response()->json(['exists' => $conflict]);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d');
            $startSession = $request->start_session;
            $endSession = $request->end_session;

            $requestedSlots = $this->buildEventSlots($startDate, $endDate, $startSession, $endSession);

            $events = Event::where('event_approval_status', '!=', 'Rejected')
                ->where('event_start_date', '<=', $endDate)
                ->where('event_end_date', '>=', $startDate)
                ->get();

            $conflict = $this->hasSlotConflict($requestedSlots, $events);

            return response()->json([
                'exists' => $conflict,
                'events' => $conflict ? $events : [],
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function create()
    {
        return view('user.events.createEvent');
    }

    public function store(Request $request)
    {
        \Log::info('Event store request', $request->all());

        $request->validate([
            'event_company_name' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string|max:5000',
            'event_location_name' => 'required|string|max:500',
            'event_location_address' => 'required|string|max:500', // Location is required
            'event_location_latitude' => 'required|numeric|between:-90,90', // Location is required
            'event_location_longitude' => 'required|numeric|between:-180,180', // Location is required
            'event_start_date' => 'required|date|after_or_equal:' . now()->addDays(10)->format('Y-m-d'),
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'event_start_session' => 'required|in:Morning,Afternoon,Evening',
            'event_end_session' => 'required|in:Morning,Afternoon,Evening',
            'event_maximum_participant' => 'required|integer|min:0|max:1000',
            'event_document' => 'nullable|file|mimes:pdf|max:10240',
            'event_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $startDate = Carbon::parse($request->event_start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->event_end_date)->format('Y-m-d');
        $startSession = $request->event_start_session;
        $endSession = $request->event_end_session;
        
        $requestedSlots = $this->buildEventSlots($startDate, $endDate, $startSession, $endSession);

        $conflictingEvents = Event::where('event_approval_status', '!=', 'Rejected')
            ->where('event_start_date', '<=', $endDate)
            ->where('event_end_date', '>=', $startDate)
            ->get();

        if ($this->hasSlotConflict($requestedSlots, $conflictingEvents)) {
            return back()->withErrors(['event_start_date' => 'The selected date and session range overlaps with an existing event. Please choose a different schedule.'])->withInput();
        }

        $event = new Event();
        $lastEvent = DB::table('events')->orderBy('event_id', 'desc')->first();
        if (!$lastEvent) {
            $event->event_id = 'EVT-0001';
        } else {
            $number = intval(substr($lastEvent->event_id, 4));
            $event->event_id = 'EVT-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        }
        
        $event->event_created_by_id = auth()->user()->user_id;
        $event->event_company_name = $request->event_company_name;
        $event->event_name = $request->event_name;
        $event->event_description = $request->event_description;
        $event->event_location_name = $request->event_location_name;
        $event->event_location_latitude = $request->event_location_latitude;
        $event->event_location_longitude = $request->event_location_longitude;
        $event->event_location_address = $request->event_location_address;
        $event->event_start_date = $startDate;
        $event->event_end_date = $endDate;
        $event->event_start_session = $startSession;
        $event->event_end_session = $endSession;
        $event->event_maximum_participant = $request->event_maximum_participant;
        $event->event_current_participant = 0;
        $event->event_approval_status = 'Pending';
        $event->event_publish = false;

        if ($request->hasFile('event_document')) {
            $url = app('cloudinary.uploader')->uploadEventDocument($request->file('event_document'), $event->event_id);
            $event->event_document = $url;
        }

        if ($request->hasFile('event_picture')) {
            $url = app('cloudinary.uploader')->uploadEventPicture($request->file('event_picture'), $event->event_id);
            $event->event_picture = $url;
        }

        try {
            $event->save();
            
            // Send confirmation email to event creator
            try {
                $creatorEmail = auth()->user()->user_email;
                $creatorName = auth()->user()->user_name;
                
                Mail::to($creatorEmail)->send(new EventCreated($event, $creatorName));
                \Log::info('Event confirmation email sent to creator', ['email' => $creatorEmail, 'event_id' => $event->event_id]);
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email to creator: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            // Send notification to all active admins
            try {
                $admins = User::where('is_admin', 1)
                    ->where('user_status', 'active')
                    ->get();
                
                $creatorName = auth()->user()->user_name;
                
                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        Mail::to($admin->user_email)->send(new NewEvent($event, $creatorName));
                        \Log::info('Admin notification email sent', ['admin_email' => $admin->user_email, 'event_id' => $event->event_id]);
                    }
                } else {
                    \Log::warning('No active admins found to notify', ['event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin notification emails: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to save event: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->withErrors(['general' => 'Failed to save event. Please try again or contact support.'])->withInput();
        }

        return redirect()->route('user.myEvents')->with('success', 'Event created successfully! A confirmation email has been sent. Waiting for admin approval.');
    }

    // Show public event details (for volunteers)
    public function publicShow($id)
    {
        $event = Event::where('event_id', $id)
            ->where('event_approval_status', 'Approved')
            ->where('event_publish', true)
            ->firstOrFail();
        
        return view('user.events.upcoming.show', compact('event'));
    }

    // ============================================
    // ADMIN EVENT MANAGEMENT METHODS
    // ============================================

    // Get pending events for admin (AJAX)
    public function getPendingEvents(Request $request)
    {
        $search = $request->get('search', '');
        $dateRange = $request->get('date_range', '');
        $status = $request->get('status', '');
        $organizer = $request->get('organizer', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Main query for events
        $query = Event::whereIn('event_approval_status', ['Pending', 'NeedsUpdate'])
            ->with('creator')
            ->orderBy($sort, $direction);

        // Search by name, ID, organizer, or location
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

        // Date Range
        if ($dateRange === 'custom' && $startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($dateRange === 'this_week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($dateRange === 'this_month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($dateRange === 'last_month') {
            $lastMonth = now()->subMonth();
            $query->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year);
        }

        if ($status) {
            $query->where('event_approval_status', $status);
        }

        if ($organizer) {
            $query->where('event_created_by_id', $organizer);
        }

        $events = $query->paginate(10);

        // Statistics should reflect the same filters
        $statsQuery = Event::whereIn('event_approval_status', ['Pending', 'NeedsUpdate']);

        // Apply same search filter to stats
        if ($search) {
            $statsQuery->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                ->orWhere('event_id', 'like', "%{$search}%")
                ->orWhere('event_company_name', 'like', "%{$search}%")
                ->orWhere('event_location_name', 'like', "%{$search}%")
                ->orWhereHas('creator', function($q2) use ($search) {
                    $q2->where('user_name', 'like', "%{$search}%");
                });
            });
        }

        // Apply same date range filter to stats
        if ($dateRange === 'custom' && $startDate && $endDate) {
            $statsQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($dateRange === 'this_week') {
            $statsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($dateRange === 'this_month') {
            $statsQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($dateRange === 'last_month') {
            $lastMonth = now()->subMonth();
            $statsQuery->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year);
        }

        // Apply same organizer filter to stats
        if ($organizer) {
            $statsQuery->where('event_created_by_id', $organizer);
        }

        // Calculate stats based on filtered results
        $totalPending = $statsQuery->count();
        $pendingCount = (clone $statsQuery)->where('event_approval_status', 'Pending')->count();
        $needUpdateCount = (clone $statsQuery)->where('event_approval_status', 'NeedsUpdate')->count();

        // Get organizers for filter dropdown
        $organizers = Event::whereIn('event_approval_status', ['Pending', 'NeedsUpdate'])
            ->whereHas('creator')
            ->with('creator')
            ->get()
            ->pluck('creator')
            ->filter()
            ->unique('user_id')
            ->values();

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.events.pending.partials.events-table', compact('events'))->render();
            $paginationHtml = view('admin.events.pending.partials.pagination', compact('events'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $paginationHtml,
                'from' => $events->firstItem(),
                'to' => $events->lastItem(),
                'total' => $events->total(),
                'stats' => [
                    'total' => $totalPending,
                    'pending' => $pendingCount,
                    'need_update' => $needUpdateCount
                ]
            ]);
        }

        return view('admin.events.pending.index', compact(
            'events',
            'organizers',
            'totalPending',
            'pendingCount',
            'needUpdateCount'
        ));
    }

    // Get upcoming events for admin (AJAX)
    public function getUpcomingEvents(Request $request)
    {
        $search = $request->get('search', '');
        $dateRange = $request->get('date_range', '');
        $status = $request->get('status', '');
        $organizer = $request->get('organizer', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');
        $sort = $request->get('sort', 'event_start_date');
        $direction = $request->get('direction', 'asc');

        // Main query for events
        $query = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->with('creator')
            ->orderBy($sort, $direction);

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                ->orWhere('event_id', 'like', "%{$search}%")
                ->orWhere('event_location_name', 'like', "%{$search}%")
                ->orWhere('event_company_name', 'like', "%{$search}%")
                ->orWhereHas('creator', function($q2) use ($search) {
                    $q2->where('user_name', 'like', "%{$search}%");
                });
            });
        }

        // Date Range
        if ($dateRange === 'custom' && $startDate && $endDate) {
            $query->whereBetween('event_start_date', [$startDate, $endDate]);
        } elseif ($dateRange === 'this_month') {
            $query->whereBetween('event_start_date', [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ]);
        } elseif ($dateRange === 'next_month') {
            $nextMonth = now()->addMonth();
            $query->whereBetween('event_start_date', [
                $nextMonth->startOfMonth()->format('Y-m-d'),
                $nextMonth->endOfMonth()->format('Y-m-d')
            ]);
        }

        // Status filter (published/unpublished)
        if ($status === 'published') {
            $query->where('event_publish', true);
        } elseif ($status === 'unpublished') {
            $query->where('event_publish', false);
        }

        if ($organizer) {
            $query->where('event_created_by_id', $organizer);
        }

        $events = $query->paginate(10);

        // Statistics should reflect the same filters
        $statsQuery = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now());

        // Apply same search filter to stats
        if ($search) {
            $statsQuery->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                ->orWhere('event_id', 'like', "%{$search}%")
                ->orWhere('event_location_name', 'like', "%{$search}%")
                ->orWhere('event_company_name', 'like', "%{$search}%")
                ->orWhereHas('creator', function($q2) use ($search) {
                    $q2->where('user_name', 'like', "%{$search}%");
                });
            });
        }

        // Apply same date range filter to stats
        if ($dateRange === 'custom' && $startDate && $endDate) {
            $statsQuery->whereBetween('event_start_date', [$startDate, $endDate]);
        } elseif ($dateRange === 'this_month') {
            $statsQuery->whereBetween('event_start_date', [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ]);
        } elseif ($dateRange === 'next_month') {
            $nextMonth = now()->addMonth();
            $statsQuery->whereBetween('event_start_date', [
                $nextMonth->startOfMonth()->format('Y-m-d'),
                $nextMonth->endOfMonth()->format('Y-m-d')
            ]);
        }

        // Apply same status filter to stats
        if ($status === 'published') {
            $statsQuery->where('event_publish', true);
        } elseif ($status === 'unpublished') {
            $statsQuery->where('event_publish', false);
        }

        // Apply same organizer filter to stats
        if ($organizer) {
            $statsQuery->where('event_created_by_id', $organizer);
        }

        // Calculate stats based on filtered results
        $totalEvents = $statsQuery->count();
        $thisMonthCount = (clone $statsQuery)->whereBetween('event_start_date', [
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        ])->count();
        $publishedCount = (clone $statsQuery)->where('event_publish', true)->count();
        $unpublishedCount = (clone $statsQuery)->where('event_publish', false)->count();

        // Get organizers for filter dropdown
        $organizers = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->whereHas('creator')
            ->with('creator')
            ->get()
            ->pluck('creator')
            ->filter()
            ->unique('user_id')
            ->values();

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.events.upcoming.partials.events-table', compact('events'))->render();
            $paginationHtml = view('admin.events.upcoming.partials.pagination', compact('events'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $paginationHtml,
                'from' => $events->firstItem(),
                'to' => $events->lastItem(),
                'total' => $events->total(),
                'stats' => [
                    'total' => $totalEvents,
                    'this_month' => $thisMonthCount,
                    'published' => $publishedCount,
                    'unpublished' => $unpublishedCount
                ]
            ]);
        }

        return view('admin.events.upcoming.index', compact(
            'events',
            'organizers',
            'totalEvents',
            'thisMonthCount',
            'publishedCount',
            'unpublishedCount'
        ));
    }

    // Get past events for admin (AJAX)
    public function getPastEvents(Request $request)
    {
        $search     = $request->get('search', '');
        $dateRange  = $request->get('date_range', '');
        $status     = $request->get('status', '');
        $organizer  = $request->get('organizer', '');
        $startDate  = $request->get('start_date', '');
        $endDate    = $request->get('end_date', '');
        $sort       = $request->get('sort', 'event_end_date');
        $direction  = $request->get('direction', 'desc');
    
        // ─── BASE SCOPE ───────────────────────────────────────────────────────────
        // Always wrap the "past" condition in its own closure so that every
        // subsequent filter is ANDed on top of the whole set, not just OR'd into it.
        $baseScope = function ($q) {
            $q->where(function ($inner) {
                $inner->where('event_approval_status', 'Approved')
                    ->where('event_end_date', '<', now());
            })->orWhere('event_approval_status', 'Rejected');
        };
    
        // ─── FILTER BUILDER ───────────────────────────────────────────────────────
        // Returns a fresh query with the base scope + all active filters applied.
        $buildQuery = function () use ($baseScope, $search, $dateRange, $startDate, $endDate, $status, $organizer) {
            $q = Event::where($baseScope)->with('creator');
    
            // Search
            if ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('event_name',         'like', "%{$search}%")
                    ->orWhere('event_id',          'like', "%{$search}%")
                    ->orWhere('event_company_name','like', "%{$search}%")
                    ->orWhere('event_location_name','like',"%{$search}%")
                    ->orWhereHas('creator', fn($cq) =>
                        $cq->where('user_name', 'like', "%{$search}%")
                    );
                });
            }
    
            // Date range (applied to event_end_date for past events)
            if ($dateRange === 'custom' && $startDate && $endDate) {
                $q->whereBetween('event_end_date', [$startDate, $endDate]);
            } elseif ($dateRange === 'this_year') {
                $q->whereYear('event_end_date', now()->year);
            } elseif ($dateRange === 'last_year') {
                $q->whereYear('event_end_date', now()->subYear()->year);
            }
    
            // Status
            if ($status) {
                if ($status === 'Unknown') {
                    $q->whereNull('event_status');
                } else {
                    $q->where('event_status', $status);
                }
            }
    
            // Organizer
            if ($organizer) {
                $q->where('event_created_by_id', $organizer);
            }
    
            return $q;
        };
    
        // ─── MAIN QUERY ───────────────────────────────────────────────────────────
        $events = $buildQuery()
            ->orderBy($sort, $direction)
            ->paginate(10);
    
        // ─── STATS (same filters, no pagination) ─────────────────────────────────
        $statsBase = $buildQuery(); // fresh clone with identical filters
    
        $totalPast        = (clone $statsBase)->count();
        $successCount     = (clone $statsBase)->where('event_status', 'Successful')->count();
        $unsuccessfulCount = (clone $statsBase)->where('event_status', 'Unsuccessful')->count();
        $rejectedCount    = (clone $statsBase)->where('event_status', 'Rejected')->count();
    
        // ─── ORGANIZERS dropdown ──────────────────────────────────────────────────
        $organizers = Event::where($baseScope)
            ->whereHas('creator')
            ->with('creator')
            ->get()
            ->pluck('creator')
            ->filter()
            ->unique('user_id')
            ->values();
    
        // ─── RESPONSE ────────────────────────────────────────────────────────────
        if ($request->ajax() || $request->wantsJson()) {
            $html           = view('admin.events.past.partials.events-table', compact('events'))->render();
            $paginationHtml = view('admin.events.past.partials.pagination',   compact('events'))->render();
    
            return response()->json([
                'success'    => true,
                'html'       => $html,
                'pagination' => $paginationHtml,
                'from'       => $events->firstItem(),
                'to'         => $events->lastItem(),
                'total'      => $events->total(),
                'stats'      => [
                    'total'        => $totalPast,
                    'success'      => $successCount,
                    'unsuccessful' => $unsuccessfulCount,
                    'rejected'     => $rejectedCount,
                ],
            ]);
        }
    
        return view('admin.events.past.index', compact(
            'events', 'organizers',
            'totalPast', 'successCount', 'unsuccessfulCount', 'rejectedCount'
        ));
    }


    // ============================================
    // ADMIN EVENT VIEWS
    // ============================================

    /**
     * Show the pending events view
     */
    public function getPendingEventsView()
    {
        // Fetch initial data for the view
        $events = Event::whereIn('event_approval_status', ['Pending', 'NeedsUpdate'])
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistics based on the same query (no filters applied initially)
        $totalPending = Event::whereIn('event_approval_status', ['Pending', 'NeedsUpdate'])->count();
        $pendingCount = Event::where('event_approval_status', 'Pending')->count();
        $needUpdateCount = Event::where('event_approval_status', 'NeedsUpdate')->count();

        // Get organizers for filter dropdown
        $organizers = Event::whereIn('event_approval_status', ['Pending', 'NeedsUpdate'])
            ->whereHas('creator')
            ->with('creator')
            ->get()
            ->pluck('creator')
            ->filter()
            ->unique('user_id')
            ->values();

        return view('admin.events.pending.index', compact(
            'events',
            'organizers',
            'totalPending',
            'pendingCount',
            'needUpdateCount'
        ));
    }

    /**
     * Show the upcoming events view
     */
    public function getUpcomingEventsView()
    {
        $events = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->with('creator')
            ->orderBy('event_start_date', 'asc')
            ->paginate(10);

        // Statistics based on the same query (no filters applied initially)
        $totalEvents = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->count();
        
        $thisMonthCount = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->whereBetween('event_start_date', [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ])
            ->count();
        
        $publishedCount = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->where('event_publish', true)
            ->count();
        
        $unpublishedCount = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->where('event_publish', false)
            ->count();

        // Get organizers for filter dropdown
        $organizers = Event::where('event_approval_status', 'Approved')
            ->where('event_start_date', '>=', now())
            ->whereHas('creator')
            ->with('creator')
            ->get()
            ->pluck('creator')
            ->filter()
            ->unique('user_id')
            ->values();

        return view('admin.events.upcoming.index', compact(
            'events',
            'organizers',
            'totalEvents',
            'thisMonthCount',
            'publishedCount',
            'unpublishedCount'
        ));
    }

    /**
     * Show the past events view
     */
    public function getPastEventsView()
    {
        // Show all events that are either:
        // 1. Approved AND past end date
        // 2. Rejected (regardless of date)
        $events = Event::where(function($q) {
            $q->where('event_approval_status', 'Approved')
            ->where('event_end_date', '<', now());
        })
        ->orWhere('event_approval_status', 'Rejected')
        ->with('creator')
        ->orderBy('event_end_date', 'desc')
        ->paginate(10);

        // Statistics based on event_status with proper capitalization
        $statsQuery = Event::where(function($q) {
            $q->where('event_approval_status', 'Approved')
            ->where('event_end_date', '<', now());
        })->orWhere('event_approval_status', 'Rejected');
        
        $totalPast = $statsQuery->count();
        
        $successCount = (clone $statsQuery)
            ->where('event_status', 'Successful')
            ->count();
        
        $unsuccessfulCount = (clone $statsQuery)
            ->where('event_status', 'Unsuccessful')
            ->count();
        
        $rejectedCount = (clone $statsQuery)
            ->where('event_status', 'Rejected')
            ->count();

        // Get organizers for filter dropdown
        $organizers = Event::where(function($q) {
            $q->where('event_approval_status', 'Approved')
            ->where('event_end_date', '<', now());
        })
        ->orWhere('event_approval_status', 'Rejected')
        ->whereHas('creator')
        ->with('creator')
        ->get()
        ->pluck('creator')
        ->filter()
        ->unique('user_id')
        ->values();

        return view('admin.events.past.index', compact(
            'events',
            'organizers',
            'totalPast',
            'successCount',
            'unsuccessfulCount',
            'rejectedCount'
        ));
    }

    // Admin view single event details
    public function adminShow($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        // Use the ParticipantController to get participants
        $participantController = new ParticipantController();
        $participants = $participantController->getEventParticipantsForView($id);
        
        return view('admin.events.show', compact('event', 'participants'));
    }

    // ============================================
    // ADMIN ACTIONS WITH EMAIL NOTIFICATIONS
    // ============================================


    //Show the admin create event form

    public function adminCreate()
    {
        // Get list of users who can be organizers (optional - for admin selection)
        $users = User::where('is_admin', 0)
            ->where('user_status', 'active')
            ->orderBy('user_name')
            ->get();
        
        // Pass users to the admin view
        return view('admin.events.create', compact('users'));
    }

    /**
     * Store a newly created event (Admin version - no date restrictions)
     */
    public function adminStore(Request $request)
    {
        \Log::info('Admin event store request', $request->all());

        // Validation without 10-day restriction for admins
        $request->validate([
        'event_company_name' => 'required|string|max:255',
        'event_name' => 'required|string|max:255',
        'event_description' => 'required|string|max:5000',
        'event_location_name' => 'required|string|max:500',
        'event_location_address' => 'required|string|max:500',
        'event_location_latitude' => 'required|numeric|between:-90,90', 
        'event_location_longitude' => 'required|numeric|between:-180,180', 
        'event_start_date' => 'required|date', // No restrictions for admin
        'event_end_date' => 'required|date|after_or_equal:event_start_date',
        'event_start_session' => 'required|in:Morning,Afternoon,Evening',
        'event_end_session' => 'required|in:Morning,Afternoon,Evening',
        'event_maximum_participant' => 'required|integer|min:0|max:1000',
        'event_document' => 'nullable|file|mimes:pdf|max:10240',
        'event_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $startDate = Carbon::parse($request->event_start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->event_end_date)->format('Y-m-d');
        $startSession = $request->event_start_session;
        $endSession = $request->event_end_session;
        
        $requestedSlots = $this->buildEventSlots($startDate, $endDate, $startSession, $endSession);

        // Check for conflicts (but allow admins to proceed)
        $conflictingEvents = Event::where('event_approval_status', '!=', 'Rejected')
            ->where('event_start_date', '<=', $endDate)
            ->where('event_end_date', '>=', $startDate)
            ->get();

        $hasConflict = $this->hasSlotConflict($requestedSlots, $conflictingEvents);

        // Create the event
        $event = new Event();
        $lastEvent = DB::table('events')->orderBy('event_id', 'desc')->first();
        if (!$lastEvent) {
            $event->event_id = 'EVT-0001';
        } else {
            $number = intval(substr($lastEvent->event_id, 4));
            $event->event_id = 'EVT-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        }
        
        $event->event_created_by_id = auth()->user()->user_id;
        $event->event_company_name = $request->event_company_name;
        $event->event_name = $request->event_name;
        $event->event_description = $request->event_description;
        $event->event_location_name = $request->event_location_name;
        $event->event_location_latitude = $request->event_location_latitude;
        $event->event_location_longitude = $request->event_location_longitude;
        $event->event_location_address = $request->event_location_address;
        $event->event_start_date = $startDate;
        $event->event_end_date = $endDate;
        $event->event_start_session = $startSession;
        $event->event_end_session = $endSession;
        $event->event_maximum_participant = $request->event_maximum_participant;
        $event->event_current_participant = 0;
        $event->event_approval_status = 'Pending';
        $event->event_publish = false;

        // Handle file uploads
        if ($request->hasFile('event_document')) {
            try {
                $url = app('cloudinary.uploader')->uploadEventDocument($request->file('event_document'), $event->event_id);
                $event->event_document = $url;
            } catch (\Exception $e) {
                \Log::error('Failed to upload document: ' . $e->getMessage());
                return back()->withErrors(['event_document' => 'Failed to upload document. Please try again.'])->withInput();
            }
        }

        if ($request->hasFile('event_picture')) {
            try {
                $url = app('cloudinary.uploader')->uploadEventPicture($request->file('event_picture'), $event->event_id);
                $event->event_picture = $url;
            } catch (\Exception $e) {
                \Log::error('Failed to upload picture: ' . $e->getMessage());
                return back()->withErrors(['event_picture' => 'Failed to upload picture. Please try again.'])->withInput();
            }
        }

        try {
            $event->save();
            
            // Send confirmation email to event creator (the admin)
            try {
                $creatorEmail = auth()->user()->user_email;
                $creatorName = auth()->user()->user_name;
                
                Mail::to($creatorEmail)->send(new EventCreated($event, $creatorName));
                \Log::info('Admin event confirmation email sent', ['email' => $creatorEmail, 'event_id' => $event->event_id]);
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            // Send notification to all other admins
            try {
                $admins = User::where('is_admin', 1)
                    ->where('user_status', 'active')
                    ->where('user_id', '!=', auth()->user()->user_id) // Exclude the creating admin
                    ->get();
                
                $creatorName = auth()->user()->user_name;
                
                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        Mail::to($admin->user_email)->send(new NewEvent($event, $creatorName));
                        \Log::info('Admin notification email sent', ['admin_email' => $admin->user_email, 'event_id' => $event->event_id]);
                    }
                } else {
                    \Log::warning('No other admins found to notify', ['event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin notification emails: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to save event: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->withErrors(['general' => 'Failed to save event. Please try again or contact support.'])->withInput();
        }

        $message = 'Event created successfully! Event ID: ' . $event->event_id . ' (Awaiting admin approval)';
        
        if ($hasConflict) {
            $message .= ' ⚠️ Warning: This event overlaps with existing events. Please review the schedule.';
        }

        return redirect()->route('admin.pendingevent')->with('success', $message);
    }


    // Admin approve event
    public function adminApprove($id)
    {
        try {
            $event = Event::where('event_id', $id)->firstOrFail();
            
            // Check if the admin is trying to approve their own event
            if ($event->event_created_by_id === auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot approve your own event. Please ask another admin to review this event.'
                ], 403);
            }
            
            $event->event_approval_status = 'Approved';
            $event->event_approver_id = auth()->user()->user_id;
            $event->save();
            
            // Send email to event creator
            try {
                $creator = User::where('user_id', $event->event_created_by_id)->first();
                if ($creator) {
                    Mail::to($creator->user_email)->send(new EventApproved($event, $creator->user_name));
                    \Log::info('Event approved email sent to creator', ['email' => $creator->user_email, 'event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send approval email: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event approved successfully! A notification email has been sent to the event creator.'
            ]);
        } catch (\Exception $e) {
            Log::error('Event approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve event.'
            ], 500);
        }
    }

    // Admin reject event
    public function adminReject(Request $request, $id)
    {
        try {
            $validator = validator($request->all(), [
                'reason' => 'required|string|min:5'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorMessage = implode(' ', $errors);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error: ' . $errorMessage,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $event = Event::where('event_id', $id)->firstOrFail();
            
            // Check if the admin is trying to reject their own event
            if ($event->event_created_by_id === auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot reject your own event. Please ask another admin to review this event.'
                ], 403);
            }
            
            $event->event_approval_status = 'Rejected';
            $event->event_status = 'Rejected';
            $event->event_remarks = $request->reason;
            $event->event_approver_id = auth()->user()->user_id;
            $event->save();
            
            // Send email to event creator
            try {
                $creator = User::where('user_id', $event->event_created_by_id)->first();
                if ($creator) {
                    Mail::to($creator->user_email)->send(new EventRejected($event, $creator->user_name, $request->reason));
                    \Log::info('Event rejected email sent to creator', ['email' => $creator->user_email, 'event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send rejection email: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event rejected successfully. A notification email has been sent to the event creator.'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Event not found: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Event not found.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Event rejection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject event. Please try again.'
            ], 500);
        }
    }

    // Admin request event update
    public function adminRequestUpdate(Request $request, $id)
    {
        try {
            $validator = validator($request->all(), [
                'feedback' => 'required|string|min:10'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorMessage = implode(' ', $errors);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error: ' . $errorMessage,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $event = Event::where('event_id', $id)->firstOrFail();
            
            // Check if the admin is trying to request update on their own event
            if ($event->event_created_by_id === auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot request updates on your own event. Please ask another admin to review this event.'
                ], 403);
            }
            
            $event->event_approval_status = 'NeedsUpdate';
            $event->event_remarks = $request->feedback;
            $event->event_approver_id = auth()->user()->user_id;
            $event->save();
            
            // Send email to event creator
            try {
                $creator = User::where('user_id', $event->event_created_by_id)->first();
                if ($creator) {
                    Mail::to($creator->user_email)->send(new EventNeedsUpdate($event, $creator->user_name, $request->feedback));
                    \Log::info('Event needs update email sent to creator', ['email' => $creator->user_email, 'event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send update request email: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Update requested. Event creator has been notified via email.'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Event not found: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Event not found.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Update request failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to request update. Please try again.'
            ], 500);
        }
    }

    /**
     * Check if the current admin is the creator of the event
     */
    private function isEventCreator($event)
    {
        return $event->event_created_by_id === auth()->user()->user_id;
    }

    // Admin publish event
    public function adminPublish($id)
    {
        try {
            $event = Event::where('event_id', $id)->firstOrFail();
            $event->event_publish = true;
            $event->save();
            
            // Send email to event creator
            try {
                $creator = User::where('user_id', $event->event_created_by_id)->first();
                if ($creator) {
                    Mail::to($creator->user_email)->send(new EventPublished($event, $creator->user_name));
                    \Log::info('Event published email sent to creator', ['email' => $creator->user_email, 'event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send publish email: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event published successfully! A notification email has been sent to the event creator.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish event.'
            ], 500);
        }
    }

    // Admin unpublish event
    public function adminUnpublish($id)
    {
        try {
            $event = Event::where('event_id', $id)->firstOrFail();
            $event->event_publish = false;
            $event->save();
            
            // Send email to event creator
            try {
                $creator = User::where('user_id', $event->event_created_by_id)->first();
                if ($creator) {
                    Mail::to($creator->user_email)->send(new EventUnpublished($event, $creator->user_name));
                    \Log::info('Event unpublished email sent to creator', ['email' => $creator->user_email, 'event_id' => $event->event_id]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send unpublish email: ' . $e->getMessage(), ['event_id' => $event->event_id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event unpublished successfully. A notification email has been sent to the event creator.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unpublish event.'
            ], 500);
        }
    }

    /**
     * Show event status update form for admin
     */
    public function adminEditStatus($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        // Only allow for past events or events that have ended
        if (Carbon::parse($event->event_end_date) >= now()) {
            return redirect()->route('admin.events.show', $event->event_id)
                ->with('error', 'Event status can only be updated for past events.');
        }
        
        return view('admin.events.edit-status', compact('event'));
    }

    /**
     * Update event status for past events
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        try {
            $event = Event::where('event_id', $id)->firstOrFail();
            
            // Only allow for past events
            if (Carbon::parse($event->event_end_date) >= now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event status can only be updated for past events.'
                ], 400);
            }
            
            $request->validate([
                'event_status' => 'required|in:Successful,Unsuccessful,Rejected',
                'event_post_review' => 'nullable|string|max:500'
            ]);
            
            $event->event_status = $request->event_status;
            $event->event_post_review = $request->event_post_review;
            $event->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Event status updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Event status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event status.'
            ], 500);
        }
    }


    // Admin edit event
    public function adminEdit($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        // Format dates properly for the date inputs - store as strings
        $event->event_start_date_formatted = Carbon::parse($event->event_start_date)->format('Y-m-d');
        $event->event_end_date_formatted = Carbon::parse($event->event_end_date)->format('Y-m-d');
        
        if (!in_array($event->event_approval_status, ['Pending', 'NeedsUpdate'])) {
            return redirect()->route('admin.events.show', $event->event_id)
                ->with('error', 'This event cannot be edited because it has already been approved or rejected.');
        }
        
        return view('admin.events.edit', compact('event'));
    }

    // Admin update event
    public function adminUpdate(Request $request, $id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        
        if (!in_array($event->event_approval_status, ['Pending', 'NeedsUpdate'])) {
            return redirect()->route('admin.events.show', $event->event_id)
                ->with('error', 'This event cannot be edited because it has already been approved or rejected.');
        }
        
        $request->validate([
            'event_company_name' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string|max:5000',
            'event_location_name' => 'required|string|max:500',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'event_start_session' => 'required|in:Morning,Afternoon,Evening',
            'event_end_session' => 'required|in:Morning,Afternoon,Evening',
            'event_maximum_participant' => 'required|integer|min:0|max:1000',
            'event_location_address' => 'nullable|string|max:500',
            'event_location_latitude' => 'nullable|numeric|between:-90,90',
            'event_location_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $event->event_company_name = $request->event_company_name;
        $event->event_name = $request->event_name;
        $event->event_description = $request->event_description;
        $event->event_location_name = $request->event_location_name;
        $event->event_location_address = $request->event_location_address;
        $event->event_location_latitude = $request->event_location_latitude;
        $event->event_location_longitude = $request->event_location_longitude;
        $event->event_start_date = $request->event_start_date;
        $event->event_end_date = $request->event_end_date;
        $event->event_start_session = $request->event_start_session;
        $event->event_end_session = $request->event_end_session;
        $event->event_maximum_participant = $request->event_maximum_participant;
        
        $event->save();

        // ─── SEND EMAIL NOTIFICATIONS ──────────────────────────────────────────────
        try {
            // 1. Send email to organizer
            $organizer = User::where('user_id', $event->event_created_by_id)->first();
            
            if ($organizer) {
                // Send to organizer - admin updated the event
                Mail::to($organizer->user_email)->send(new \App\Mail\EventUpdated(
                    $event, 
                    $organizer->user_name,
                    true, // isAdminUpdate
                    auth()->user()->user_name
                ));
                \Log::info('Event updated email sent to organizer', [
                    'email' => $organizer->user_email,
                    'event_id' => $event->event_id,
                    'updated_by' => auth()->user()->user_id
                ]);
            }

            // 2. Send notification to ALL active admins (including the one who updated)
            $admins = User::where('is_admin', 1)
                ->where('user_status', 'active')
                ->get();
            
            $organizerName = $organizer ? $organizer->user_name : 'Unknown Organizer';
            $adminName = auth()->user()->user_name;
            
            foreach ($admins as $admin) {
                Mail::to($admin->user_email)->send(new \App\Mail\EventUpdatedAdminNotification(
                    $event,
                    $organizerName,
                    $adminName
                ));
                \Log::info('Admin notification sent for event update', [
                    'admin_email' => $admin->user_email,
                    'event_id' => $event->event_id
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to send event update emails: ' . $e->getMessage(), [
                'event_id' => $event->event_id
            ]);
        }

        return redirect()->route('admin.events.show', $event->event_id)
            ->with('success', 'Event updated successfully. Notifications have been sent to the organizer and admins.');
    }

    public function generateEventReport(Request $request)
{
    $period = $request->get('period', 'all');
    $startDate = $request->get('start');
    $endDate = $request->get('end');

    // Only fetch past events
    $query = Event::where(function($q) {
        $q->where('event_approval_status', 'Approved')
          ->where('event_end_date', '<', now());
    })->orWhere('event_approval_status', 'Rejected')
    ->with(['creator', 'approver']);

    // Apply date range based on period
    switch ($period) {
        case 'today':
            $query->whereDate('created_at', today());
            break;
        case 'this_week':
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            break;
        case 'last_month':
            $lastMonth = now()->subMonth();
            $query->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year);
            break;
        case 'this_year':
            $query->whereYear('created_at', now()->year);
            break;
        case 'last_year':
            $query->whereYear('created_at', now()->subYear()->year);
            break;
        case 'custom':
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            break;
        // 'all' - no date filter
    }

    $events = $query->orderBy('created_at', 'desc')->get();

    // Calculate statistics
    $total = $events->count();
    $successful = $events->where('event_status', 'Successful')->count();
    $unsuccessful = $events->where('event_status', 'Unsuccessful')->count();
    $rejected = $events->where('event_status', 'Rejected')->count();
    $unknown = $events->whereNull('event_status')->count();

    // Calculate participant metrics
    $totalParticipants = $events->sum('event_current_participant');
    $totalCapacity = $events->sum('event_maximum_participant');
    $averageParticipants = $total > 0 ? $totalParticipants / $total : 0;
    $uniqueOrganizers = $events->pluck('event_created_by_id')->unique()->count();

    $report = [
        'total' => $total,
        'successful' => $successful,
        'unsuccessful' => $unsuccessful,
        'rejected' => $rejected,
        'unknown' => $unknown,
        'total_participants' => $totalParticipants,
        'total_capacity' => $totalCapacity,
        'average_participants' => $averageParticipants,
        'unique_organizers' => $uniqueOrganizers,
        'period' => $period,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ];

    return view('admin.events.report', compact('events', 'report', 'period', 'startDate', 'endDate'));
}

    
}