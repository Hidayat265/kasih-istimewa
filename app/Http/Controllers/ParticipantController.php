<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantCancelled;

class ParticipantController extends Controller
{
    // Check if user has conflicting events
    private function hasTimeConflict($userId, $newEvent)
    {
        // Get all user's confirmed upcoming registrations
        $userRegistrations = Participant::with('event')
            ->where('user_id', $userId)
            ->where('participant_status', 'confirmed')
            ->whereHas('event', function($query) {
                $query->where('event_start_date', '>=', now());
            })
            ->get();
        
        foreach ($userRegistrations as $registration) {
            $registeredEvent = $registration->event;
            
            // Check if dates overlap
            $newStart = strtotime($newEvent->event_start_date);
            $newEnd = strtotime($newEvent->event_end_date);
            $registeredStart = strtotime($registeredEvent->event_start_date);
            $registeredEnd = strtotime($registeredEvent->event_end_date);
            
            // Check if date ranges overlap
            if ($newStart <= $registeredEnd && $newEnd >= $registeredStart) {
                // Dates overlap, now check session times
                $sessionsOrder = ['Morning', 'Afternoon', 'Evening'];
                $newStartIndex = array_search($newEvent->event_start_session, $sessionsOrder);
                $newEndIndex = array_search($newEvent->event_end_session, $sessionsOrder);
                $registeredStartIndex = array_search($registeredEvent->event_start_session, $sessionsOrder);
                $registeredEndIndex = array_search($registeredEvent->event_end_session, $sessionsOrder);
                
                // Check if sessions overlap
                if ($newStartIndex <= $registeredEndIndex && $newEndIndex >= $registeredStartIndex) {
                    return [
                        'conflict' => true,
                        'conflicting_event' => $registeredEvent
                    ];
                }
            }
        }
        
        return ['conflict' => false];
    }
    
    /**
     * Register for an event
     */
    public function register(Request $request, $eventId)
    {
        try {
            $request->validate([
                'accept_terms' => 'required|accepted'
            ], [
                'accept_terms.required' => 'You must accept the Terms and Conditions to register.',
                'accept_terms.accepted' => 'You must accept the Terms and Conditions to register.'
            ]);
            
            Log::info('Registration attempt started', ['event_id' => $eventId, 'user_id' => auth()->user()->user_id]);
            
            $event = Event::where('event_id', $eventId)
                ->where('event_approval_status', 'Approved')
                ->where('event_publish', true)
                ->firstOrFail();
            
            if (!$event->isUpcoming()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This event has already passed.'
                ], 400);
            }
            
            if ($event->is_full) {
                return response()->json([
                    'success' => false,
                    'message' => 'This event is already full.'
                ], 400);
            }
            
            if ($event->event_created_by_id == auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot register for your own event.'
                ], 400);
            }
            
            $existingRegistration = Participant::where('event_id', $eventId)
                ->where('user_id', auth()->user()->user_id)
                ->where('participant_status', '!=', 'cancelled')
                ->first();
            
            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already registered for this event.'
                ], 400);
            }
            
            $conflictCheck = $this->hasTimeConflict(auth()->user()->user_id, $event);
            
            if ($conflictCheck['conflict']) {
                $conflictingEvent = $conflictCheck['conflicting_event'];
                $conflictMessage = sprintf(
                    'You cannot register for this event because it conflicts with "%s" which runs from %s to %s.',
                    $conflictingEvent->event_name,
                    $conflictingEvent->event_start_date . ' (' . $conflictingEvent->event_start_session . ')',
                    $conflictingEvent->event_end_date . ' (' . $conflictingEvent->event_end_session . ')'
                );
                
                return response()->json([
                    'success' => false,
                    'message' => $conflictMessage
                ], 409);
            }
            
            DB::beginTransaction();
            
            $participant = Participant::create([
                'event_id' => $eventId,
                'user_id' => auth()->user()->user_id,
                'participant_status' => 'confirmed',
                'participant_registered_at' => now(),
                'participant_confirmed_at' => now(),
            ]);
            
            $event->updateVolunteerCount(1);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully registered for the event!',
                'data' => $participant
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['accept_terms'][0] ?? 'Please accept the terms and conditions.'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Participant registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to register. Please try again later.'
            ], 500);
        }
    }
    
    
    
   /**
     * Admin cancel participant registration (via admin panel)
     */
    public function adminCancelParticipant(Request $request, $eventId)
    {
        try {
            // ─── DEBUG: Log everything ──────────────────────────────────────────
            \Log::info('=== ADMIN CANCEL PARTICIPANT DEBUG ===');
            \Log::info('eventId parameter:', ['eventId' => $eventId]);
            \Log::info('Request all data:', $request->all());
            \Log::info('Request user_id:', ['user_id' => $request->user_id]);
            
            // Check if event exists with this ID
            $eventCheck = Event::where('event_id', $eventId)->first();
            \Log::info('Event found?', ['exists' => $eventCheck ? true : false]);
            if ($eventCheck) {
                \Log::info('Event data:', $eventCheck->toArray());
            }
            
            // Check all events in database
            $allEvents = Event::all();
            \Log::info('All event IDs in database:', $allEvents->pluck('event_id')->toArray());
            // Check if user is admin
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only administrators can cancel registrations.'
                ], 403);
            }
            
            $request->validate([
                'user_id' => 'required|string',
                'reason' => 'nullable|string|max:500'
            ]);
            
            \Log::info('Admin cancel participant - Request received', [
                'event_id' => $eventId,
                'user_id' => $request->user_id,
                'reason' => $request->reason
            ]);
            
            DB::beginTransaction();
            
            // ─── FIX: Check if event exists using event_id column ──────────────
            $event = Event::where('event_id', $eventId)->first();
            if (!$event) {
                DB::rollBack();
                \Log::warning('Event not found', ['event_id' => $eventId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.'
                ], 404);
            }
            
            // Check if the user exists
            $participantUser = User::where('user_id', $request->user_id)->first();
            if (!$participantUser) {
                DB::rollBack();
                \Log::warning('User not found', ['user_id' => $request->user_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            
            // Find the participant
            $participant = Participant::where('event_id', $eventId)
                ->where('user_id', $request->user_id)
                ->first();
            
            \Log::info('Participant search result', [
                'found' => $participant ? true : false,
                'event_id' => $eventId,
                'user_id' => $request->user_id,
            ]);
            
            if (!$participant) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Participant not found. The user may not be registered for this event.'
                ], 404);
            }
            
            // Check if already cancelled
            if ($participant->participant_status === 'cancelled') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'This participant is already cancelled.'
                ], 400);
            }
            
            // Update the participant
            Participant::where('event_id', $eventId)
            ->where('user_id', $request->user_id)
            ->update([
                'participant_status' => 'cancelled',
                'participant_cancelled_at' => now(),
                'participant_cancellation_reason' => $request->reason ?? 'Cancelled by administrator',
            ]);
            
            // Update event volunteer count
            $event->updateVolunteerCount(-1);
            
            DB::commit();
            
            // ─── SEND EMAIL TO PARTICIPANT ──────────────────────────────────────
            try {
                $adminName = auth()->user()->user_name;
                
                Mail::to($participantUser->user_email)->send(new \App\Mail\ParticipantCancelled(
                    $event,
                    $participantUser,
                    $request->reason,
                    $adminName
                ));
                
                \Log::info('Participant cancellation email sent', [
                    'email' => $participantUser->user_email,
                    'event_id' => $eventId,
                    'user_id' => $request->user_id
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send cancellation email: ' . $e->getMessage(), [
                    'event_id' => $eventId,
                    'user_id' => $request->user_id
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Participant registration cancelled successfully. A notification email has been sent to the participant.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin cancel participant failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel participant registration: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * User cancels their own registration
     */
    public function cancelOwnRegistration(Request $request, $eventId)
    {
        try {
            $userId = auth()->user()->user_id;
            $reason = $request->input('reason', 'Cancelled by user');
            
            DB::beginTransaction();
            
            // Find the participant using the composite key
            $participant = Participant::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->where('participant_status', 'confirmed')
                ->first();
            
            if (!$participant) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Registration not found or already cancelled.'
                ], 404);
            }
            
            // Update using direct where clause with composite key
            $updated = Participant::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->where('participant_status', 'confirmed')
                ->update([
                    'participant_status' => 'cancelled',
                    'participant_cancelled_at' => now(),
                    'participant_cancellation_reason' => $reason
                ]);
            
            if (!$updated) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update registration status.'
                ], 500);
            }
            
            // Update event volunteer count
            $event = Event::where('event_id', $eventId)->first();
            if ($event) {
                $event->updateVolunteerCount(-1);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Your registration has been cancelled successfully.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User cancellation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel registration: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user's registered events
     */
    public function myRegistrations()
    {
        $registrations = Participant::with(['event' => function($query) {
                $query->where('event_publish', true);
            }])
            ->where('user_id', auth()->user()->user_id)
            ->where('participant_status', 'confirmed')
            ->orderBy('participant_registered_at', 'desc')
            ->get();
        
        return view('user.events.my-registrations', compact('registrations'));
    }
    
    /**
     * Check registration status
     */
    public function checkStatus($eventId)
    {
        $participant = Participant::where('event_id', $eventId)
            ->where('user_id', auth()->user()->user_id)
            ->first();
        
        return response()->json([
            'registered' => $participant && $participant->participant_status === 'confirmed',
            'status' => $participant ? $participant->participant_status : null
        ]);
    }
    
    /**
     * Get all participants for an event with AJAX pagination and search
     */
    public function getEventParticipants(Request $request, $eventId)
    {
        try {
            $event = Event::where('event_id', $eventId)->firstOrFail();
            
            if ($event->event_created_by_id != auth()->user()->user_id && !auth()->user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            
            $search = $request->get('search', '');
            $page = $request->get('page', 1);
            
            $query = Participant::with('user')
                ->where('event_id', $eventId)
                ->where('participant_status', 'confirmed');
            
            if ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%");
                });
            }
            
            $participants = $query->orderBy('participant_registered_at', 'desc')->paginate(10, ['*'], 'page', $page);
            
            if ($request->ajax() || $request->wantsJson()) {
                // ✅ Pass the event to the partial
                $html = view('admin.events.partials.participants-table', [
                    'participants' => $participants,
                    'event' => $event
                ])->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'from' => $participants->firstItem(),
                    'to' => $participants->lastItem(),
                    'total' => $participants->total(),
                    'current_page' => $participants->currentPage(),
                    'last_page' => $participants->lastPage()
                ]);
            }
            
            return redirect()->route('admin.events.show', $eventId);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch participants: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch participants.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to fetch participants.');
        }
    }
    
   /**
     * Get event participants for the admin show page (initial load)
     */
    public function getEventParticipantsForView($eventId)
    {
        try {
            $event = Event::where('event_id', $eventId)->firstOrFail();
            
            if ($event->event_created_by_id != auth()->user()->user_id && !auth()->user()->is_admin) {
                abort(403, 'Unauthorized action.');
            }
            
            // Only fetch confirmed participants
            $participants = Participant::with('user')
                ->where('event_id', $eventId)
                ->where('participant_status', 'confirmed')
                ->orderBy('participant_registered_at', 'desc')
                ->paginate(10);
            
            return $participants;
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch participants for view: ' . $e->getMessage());
            return collect();
        }
    }
    
    /**
     * Check if user has any time conflicts for a specific event (API endpoint)
     */
    public function checkTimeConflict($eventId)
    {
        try {
            $event = Event::where('event_id', $eventId)->firstOrFail();
            $conflictCheck = $this->hasTimeConflict(auth()->user()->user_id, $event);
            
            return response()->json([
                'has_conflict' => $conflictCheck['conflict'],
                'conflicting_event' => $conflictCheck['conflict'] ? [
                    'id' => $conflictCheck['conflicting_event']->event_id,
                    'name' => $conflictCheck['conflicting_event']->event_name,
                    'date' => $conflictCheck['conflicting_event']->event_start_date,
                    'time' => $conflictCheck['conflicting_event']->event_start_session
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'has_conflict' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get my registrations data for AJAX
     */
    public function getMyRegistrationsData()
    {
        $userId = auth()->user()->user_id;
        
        $registrations = Participant::with('event')
            ->where('user_id', $userId)
            ->where('participant_status', 'confirmed')
            ->orderBy('participant_registered_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'registrations' => $registrations
        ]);
    }
}