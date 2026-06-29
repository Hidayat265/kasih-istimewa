<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Participant;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users
        $users = User::where('is_admin', 0)->get();
        $adminUsers = User::where('is_admin', 1)->get();
        
        // Get events
        $events = Event::all();
        
        if ($events->isEmpty()) {
            $this->command->error('No events found. Please run EventSeeder first.');
            return;
        }
        
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }
        
        $this->command->info('Seeding participants for ' . $events->count() . ' events...');
        
        // Clear existing participants to avoid conflicts
        Participant::truncate();
        $this->command->info('Cleared existing participants.');
        
        $participants = [];
        $userEventCount = [];
        $eventParticipants = [];
        $counter = 0;
        
        // Track which users are assigned to which events to avoid duplicates
        $assignedUsers = [];
        
        foreach ($events as $event) {
            $eventParticipants[$event->event_id] = [];
        }
        
        // Get current date for timestamps
        $now = Carbon::now();
        $lastWeek = Carbon::now()->subDays(7);
        $twoWeeksAgo = Carbon::now()->subDays(14);
        $lastMonth = Carbon::now()->subDays(30);
        $twoMonthsAgo = Carbon::now()->subDays(60);
        
        // ============================================
        // SEED PARTICIPANTS FOR EACH EVENT
        // ============================================
        
        foreach ($events as $event) {
            // Skip events that are rejected or Needs Update
            if (in_array($event->event_approval_status, ['Rejected', 'NeedsUpdate'])) {
                continue;
            }
            
            // Determine how many participants to add (20-80% of capacity)
            $maxParticipants = $event->event_maximum_participant;
            $targetCount = min(
                $maxParticipants,
                rand(max(2, ceil($maxParticipants * 0.2)), ceil($maxParticipants * 0.8))
            );
            
            // For past events, make them fully attended
            if ($event->event_end_date < $now->format('Y-m-d')) {
                $targetCount = min($maxParticipants, rand(10, $maxParticipants));
            }
            
            // For upcoming events, seed based on current_participant count
            if ($event->event_start_date >= $now->format('Y-m-d') && $event->event_current_participant > 0) {
                $targetCount = min($maxParticipants, $event->event_current_participant);
            }
            
            $this->command->info("  Adding {$targetCount} participants to '{$event->event_name}'...");
            
            // Shuffle users to randomize
            $shuffledUsers = $users->shuffle();
            $addedCount = 0;
            $skipCount = 0;
            
            foreach ($shuffledUsers as $user) {
                // Stop if we've added enough participants
                if ($addedCount >= $targetCount) {
                    break;
                }
                
                // Skip event creator (can't register for own event)
                if ($user->user_id === $event->event_created_by_id) {
                    $skipCount++;
                    continue;
                }
                
                // Check if user already has a conflicting event
                if ($this->hasConflictingEvent($user->user_id, $event, $participants)) {
                    $skipCount++;
                    continue;
                }
                
                // Check if user is already assigned to this event
                if (isset($assignedUsers[$user->user_id]) && in_array($event->event_id, $assignedUsers[$user->user_id])) {
                    $skipCount++;
                    continue;
                }
                
                // Determine status based on event date
                $status = $this->getStatusForEvent($event, $now);
                
                // Determine registration and confirmation dates
                $registeredAt = $this->getRegisteredAt($event, $now, $lastWeek, $twoWeeksAgo, $lastMonth);
                
                // Create participant record
                $participantData = [
                    'event_id' => $event->event_id,
                    'user_id' => $user->user_id,
                    'participant_status' => $status,
                    'participant_registered_at' => $registeredAt,
                    'participant_confirmed_at' => in_array($status, ['confirmed', 'attended']) ? $registeredAt : null,
                    'participant_cancelled_at' => null,
                    'participant_cancellation_reason' => null,
                    'created_at' => $registeredAt,
                    'updated_at' => $registeredAt,
                ];
                
                // Some participants are cancelled
                if ($status === 'cancelled') {
                    $participantData['participant_cancelled_at'] = $lastWeek;
                    $participantData['participant_cancellation_reason'] = $this->getRandomCancellationReason();
                    $participantData['updated_at'] = $lastWeek;
                }
                
                // Some attended events are marked as attended
                if ($status === 'attended') {
                    $participantData['participant_confirmed_at'] = $registeredAt;
                }
                
                // Track assigned users
                if (!isset($assignedUsers[$user->user_id])) {
                    $assignedUsers[$user->user_id] = [];
                }
                $assignedUsers[$user->user_id][] = $event->event_id;
                
                $participants[] = $participantData;
                $addedCount++;
                $counter++;
            }
            
            $this->command->line("    ✓ Added {$addedCount} participants (skipped {$skipCount} users)");
        }
        
        // ============================================
        // ADD SOME CANCELLED REGISTRATIONS
        // ============================================
        $cancelledCount = 0;
        foreach ($events as $event) {
            if ($event->event_approval_status === 'Approved') {
                $existingParticipants = array_filter($participants, function($p) use ($event) {
                    return $p['event_id'] === $event->event_id;
                });
                
                if (count($existingParticipants) > 3) {
                    // Randomly cancel 1-2 participants
                    $toCancel = rand(1, 2);
                    $cancelledAdded = 0;
                    
                    foreach ($existingParticipants as $key => $p) {
                        if ($cancelledAdded >= $toCancel) break;
                        if ($p['participant_status'] === 'cancelled') continue;
                        
                        $participants[$key]['participant_status'] = 'cancelled';
                        $participants[$key]['participant_cancelled_at'] = Carbon::now()->subDays(rand(1, 10));
                        $participants[$key]['participant_cancellation_reason'] = $this->getRandomCancellationReason();
                        $participants[$key]['updated_at'] = Carbon::now();
                        $cancelledAdded++;
                        $cancelledCount++;
                    }
                }
            }
        }
        
        // ============================================
        // INSERT PARTICIPANTS
        // ============================================
        $this->command->info("\nInserting " . count($participants) . " participant records...");
        
        foreach ($participants as $data) {
            try {
                Participant::create($data);
            } catch (\Exception $e) {
                $this->command->error("Failed to insert participant for event {$data['event_id']} and user {$data['user_id']}: " . $e->getMessage());
            }
        }
        
        // ============================================
        // UPDATE EVENT CURRENT PARTICIPANT COUNTS
        // ============================================
        $this->command->info("\nUpdating event participant counts...");
        foreach ($events as $event) {
            $confirmedCount = Participant::where('event_id', $event->event_id)
                ->whereIn('participant_status', ['confirmed', 'attended'])
                ->count();
            
            $event->event_current_participant = $confirmedCount;
            $event->save();
            
            $this->command->line("  {$event->event_name}: {$confirmedCount} participants");
        }
        
        // ============================================
        // OUTPUT STATISTICS
        // ============================================
        $this->command->info("\n✅ Participant Seeding Complete!");
        $this->command->info("Total participant records created: " . count($participants));
        
        $totalConfirmed = Participant::whereIn('participant_status', ['confirmed', 'attended'])->count();
        $totalCancelled = Participant::where('participant_status', 'cancelled')->count();
        $totalPending = Participant::where('participant_status', 'pending')->count();
        
        $this->command->info("\n📊 Status Breakdown:");
        $this->command->info("  ✅ Confirmed/Attended: {$totalConfirmed}");
        $this->command->info("  ❌ Cancelled: {$totalCancelled}");
        $this->command->info("  ⏳ Pending: {$totalPending}");
        
        // Show sample of participants per event
        $this->command->info("\n📋 Sample Event Participation:");
        $sampleEvents = $events->take(5);
        foreach ($sampleEvents as $event) {
            $count = Participant::where('event_id', $event->event_id)->count();
            $this->command->line("  • {$event->event_name}: {$count} participants");
        }
    }
    
    /**
     * Check if user has a conflicting event
     */
    private function hasConflictingEvent($userId, $newEvent, $participants): bool
    {
        // Get existing participants for this user
        $userParticipants = array_filter($participants, function($p) use ($userId) {
            return $p['user_id'] === $userId && in_array($p['participant_status'], ['confirmed', 'attended']);
        });
        
        if (empty($userParticipants)) {
            return false;
        }
        
        $newStart = strtotime($newEvent->event_start_date);
        $newEnd = strtotime($newEvent->event_end_date);
        $sessionsOrder = ['Morning', 'Afternoon', 'Evening'];
        $newStartIndex = array_search($newEvent->event_start_session, $sessionsOrder);
        $newEndIndex = array_search($newEvent->event_end_session, $sessionsOrder);
        
        foreach ($userParticipants as $p) {
            $event = Event::where('event_id', $p['event_id'])->first();
            if (!$event) continue;
            
            // Check if dates overlap
            $registeredStart = strtotime($event->event_start_date);
            $registeredEnd = strtotime($event->event_end_date);
            
            if ($newStart <= $registeredEnd && $newEnd >= $registeredStart) {
                // Check if sessions overlap
                $registeredStartIndex = array_search($event->event_start_session, $sessionsOrder);
                $registeredEndIndex = array_search($event->event_end_session, $sessionsOrder);
                
                if ($newStartIndex <= $registeredEndIndex && $newEndIndex >= $registeredStartIndex) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Get status for event based on event date
     */
    private function getStatusForEvent($event, $now): string
    {
        $eventEnd = Carbon::parse($event->event_end_date);
        
        if ($eventEnd < $now) {
            // Past event - mark as attended
            return 'attended';
        }
        
        // Upcoming event - confirmed
        return 'confirmed';
    }
    
    /**
     * Get registration date based on event
     */
    private function getRegisteredAt($event, $now, $lastWeek, $twoWeeksAgo, $lastMonth)
    {
        $eventStart = Carbon::parse($event->event_start_date);
        $daysUntilEvent = $now->diffInDays($eventStart);
        
        if ($daysUntilEvent > 30) {
            return Carbon::parse($now->subDays(rand(10, 25)));
        } elseif ($daysUntilEvent > 15) {
            return Carbon::parse($now->subDays(rand(5, 15)));
        } elseif ($daysUntilEvent > 7) {
            return Carbon::parse($now->subDays(rand(2, 7)));
        } else {
            return Carbon::parse($now->subDays(rand(0, 3)));
        }
    }
    
    /**
     * Get random cancellation reason
     */
    private function getRandomCancellationReason(): string
    {
        $reasons = [
            'Personal emergency',
            'Work commitment',
            'Family obligation',
            'Health issues',
            'Transportation problem',
            'Schedule conflict',
            'Unable to attend',
            'Unexpected circumstances',
            'Change of plans',
            'Time conflict with other commitments'
        ];
        
        return $reasons[array_rand($reasons)];
    }
}