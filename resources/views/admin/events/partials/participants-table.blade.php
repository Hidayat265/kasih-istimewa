@php
    // Get the event ID from the passed $event variable
    // If $event is not passed, try to get it from the first participant
    $eventId = isset($event) && $event ? $event->event_id : '';
    
    // If still empty, try to get from participants
    if (empty($eventId) && isset($participants) && $participants->isNotEmpty()) {
        $firstParticipant = $participants->first();
        if ($firstParticipant && isset($firstParticipant->event_id)) {
            $eventId = $firstParticipant->event_id;
        }
    }
    
    // Debug: Log the event ID
    if (empty($eventId)) {
        \Log::warning('Event ID is empty in participants table - check if $event is being passed');
    } else {
        \Log::info('Participants table - Event ID found', ['eventId' => $eventId]);
    }
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($participants as $index => $participant)
            @php
                $age = '-';
                if ($participant->user && $participant->user->user_dob) {
                    $age = \Carbon\Carbon::parse($participant->user->user_dob)->age;
                }
            @endphp
            <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ ($participants->currentPage() - 1) * $participants->perPage() + $loop->iteration }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">{{ $participant->user->user_name ?? 'Unknown' }}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $participant->user->user_email ?? '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $participant->user->user_phone_number ?? '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $age }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ $participant->participant_registered_at ? \Carbon\Carbon::parse($participant->participant_registered_at)->format('d M Y, h:i A') : '-' }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">
                    @if(!empty($eventId))
                        <button onclick="showCancelParticipantModal('{{ $eventId }}', '{{ $participant->user_id }}', '{{ $participant->user->user_name ?? 'Unknown' }}')" 
                                class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition text-xs font-medium">
                            <i class="fas fa-user-slash mr-1"></i> Cancel
                        </button>
                    @else
                        <span class="text-xs text-gray-400">Event ID missing</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-users text-3xl text-gray-300 mb-2 block"></i>
                    No confirmed participants yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($participants->lastPage() > 1)
<div class="flex justify-center space-x-2 mt-4" id="participantPagination">
    @if ($participants->onFirstPage())
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
    @else
        <a href="javascript:void(0)" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->currentPage() - 1 }}">Previous</a>
    @endif

    @php
        $start = max(1, $participants->currentPage() - 2);
        $end = min($participants->lastPage(), $participants->currentPage() + 2);
    @endphp

    @if ($start > 1)
        <a href="javascript:void(0)" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
        @if ($start > 2)
            <span class="px-3 py-1 text-gray-400">...</span>
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $participants->currentPage())
            <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
        @else
            <a href="javascript:void(0)" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $participants->lastPage())
        @if ($end < $participants->lastPage() - 1)
            <span class="px-3 py-1 text-gray-400">...</span>
        @endif
        <a href="javascript:void(0)" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->lastPage() }}">{{ $participants->lastPage() }}</a>
    @endif

    @if ($participants->hasMorePages())
        <a href="javascript:void(0)" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->currentPage() + 1 }}">Next</a>
    @else
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
    @endif
</div>
@endif

{{-- Cancel Participant Modal --}}
<div id="cancelParticipantModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[100000]">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Cancel Participant Registration</h3>
        <p class="text-gray-600 mb-4">
            Are you sure you want to cancel <span id="cancelParticipantName" class="font-semibold text-gray-900"></span>'s registration?
        </p>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Cancellation (Optional)</label>
            <textarea id="cancelReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Enter reason for cancellation..."></textarea>
        </div>
        <div class="flex justify-end gap-3 mt-4">
            <button onclick="closeCancelParticipantModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">Cancel</button>
            <button onclick="confirmCancelParticipant()" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                <i class="fas fa-user-slash mr-1"></i> Cancel Registration
            </button>
        </div>
    </div>
</div>

<script>
    let cancelParticipantEventId = null;
    let cancelParticipantUserId = null;
    let cancelParticipantUserName = null;

    function showCancelParticipantModal(eventId, userId, userName) {
        console.log('showCancelParticipantModal called with:', { eventId, userId, userName });
        
        // Handle null/undefined values
        if (!eventId || eventId === 'null' || eventId === null || eventId === '') {
            console.error('Invalid Event ID:', eventId);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Event ID is missing. Please refresh the page and try again.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (!userId || userId === 'null' || userId === null || userId === '') {
            console.error('Invalid User ID:', userId);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'User ID is missing. Please refresh the page and try again.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        cancelParticipantEventId = eventId;
        cancelParticipantUserId = userId;
        cancelParticipantUserName = userName || 'Unknown';
        
        document.getElementById('cancelParticipantName').textContent = cancelParticipantUserName;
        document.getElementById('cancelReason').value = '';
        document.getElementById('cancelParticipantModal').classList.remove('hidden');
        document.getElementById('cancelParticipantModal').classList.add('flex');
        
        console.log('Modal opened with:', { 
            eventId: cancelParticipantEventId, 
            userId: cancelParticipantUserId,
            userName: cancelParticipantUserName 
        });
    }

    function closeCancelParticipantModal() {
        document.getElementById('cancelParticipantModal').classList.add('hidden');
        document.getElementById('cancelParticipantModal').classList.remove('flex');
        cancelParticipantEventId = null;
        cancelParticipantUserId = null;
        cancelParticipantUserName = null;
    }

    function confirmCancelParticipant() {
        console.log('confirmCancelParticipant called with:', { 
            eventId: cancelParticipantEventId, 
            userId: cancelParticipantUserId 
        });
        
        if (!cancelParticipantEventId || !cancelParticipantUserId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Missing participant information. Please refresh the page and try again.',
                confirmButtonColor: '#d33'
            });
            console.error('Missing data:', { 
                eventId: cancelParticipantEventId, 
                userId: cancelParticipantUserId 
            });
            return;
        }

        // ✅ Capture values into locals BEFORE closing the modal — closing the
        // modal nulls out the module-level vars, and the Swal callback below
        // runs asynchronously (after the user confirms), so by then the
        // module-level vars would already be null.
        const eventIdToCancel = cancelParticipantEventId;
        const userIdToCancel = cancelParticipantUserId;
        const reason = document.getElementById('cancelReason').value.trim();

        // Close the modal first
        closeCancelParticipantModal();

        Swal.fire({
            title: 'Cancel Registration?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData();
                formData.append('user_id', userIdToCancel);
                if (reason) {
                    formData.append('reason', reason);
                }

                const url = `/admin/events/${eventIdToCancel}/cancel-participant`;
                console.log('Sending request to:', url);
                console.log('Form data:', { user_id: userIdToCancel, reason: reason });

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            if (typeof fetchParticipants === 'function') {
                                fetchParticipants(1);
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to cancel participant.',
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                });
            }
        });
    }

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('cancelParticipantModal');
        if (e.target === modal) {
            closeCancelParticipantModal();
        }
    });

    // ─── PAGINATION HANDLERS ──────────────────────────────────────────────
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination-link');
        if (link) {
            e.preventDefault();
            const page = link.getAttribute('data-page');
            if (page && typeof fetchParticipants === 'function') {
                fetchParticipants(parseInt(page));
            }
        }
    });
</script>