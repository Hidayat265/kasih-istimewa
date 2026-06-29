@if($participants->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($participants as $index => $participant)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ ($participants->currentPage() - 1) * $participants->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $participant->user->user_name ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $participant->user->user_email ?? 'No email' }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($participant->participant_registered_at ?? $participant->created_at)->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $participant->participant_status === 'confirmed' || $participant->participant_status === 'attended' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($participant->participant_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            @if($participant->participant_status === 'confirmed')
                                <button onclick="cancelParticipant('{{ $participant->user_id }}', '{{ $participant->event_id }}')" 
                                        class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition text-xs font-medium">
                                    <i class="fas fa-user-minus mr-1"></i> Cancel
                                </button>
                            @else
                                <span class="text-xs text-gray-400">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($participants->lastPage() > 1)
        <div class="mt-4 flex justify-center space-x-2">
            @if($participants->onFirstPage())
                <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
            @else
                <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->currentPage() - 1 }}">Previous</a>
            @endif

            @php
                $start = max(1, $participants->currentPage() - 2);
                $end = min($participants->lastPage(), $participants->currentPage() + 2);
            @endphp

            @if($start > 1)
                <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
                @if($start > 2)
                    <span class="px-3 py-1 text-gray-400">...</span>
                @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                @if($i == $participants->currentPage())
                    <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
                @else
                    <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
                @endif
            @endfor

            @if($end < $participants->lastPage())
                @if($end < $participants->lastPage() - 1)
                    <span class="px-3 py-1 text-gray-400">...</span>
                @endif
                <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->lastPage() }}">{{ $participants->lastPage() }}</a>
            @endif

            @if($participants->hasMorePages())
                <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->currentPage() + 1 }}">Next</a>
            @else
                <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
            @endif
        </div>
    @endif

    <div class="mt-2 text-xs text-gray-500 text-center">
        Showing {{ $participants->firstItem() ?? 0 }} to {{ $participants->lastItem() ?? 0 }} of {{ $participants->total() ?? 0 }} participants
    </div>
@else
    <div class="text-center py-8">
        <i class="fas fa-users text-4xl text-gray-300 mb-2 block"></i>
        <p class="text-gray-500">No participants registered yet.</p>
    </div>
@endif

<script>
    function cancelParticipant(userId, eventId) {
        Swal.fire({
            title: 'Cancel Registration?',
            text: 'This will remove the participant from the event.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Route now points to ParticipantController
                fetch(`/admin/events/${eventId}/cancel-participant`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: data.message,
                            confirmButtonColor: '#554994'
                        }).then(() => {
                            fetchParticipants({{ $participants->currentPage() ?? 1 }});
                        });
                    } else {
                        throw new Error(data.message || 'Failed to cancel registration');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message,
                        confirmButtonColor: '#d33'
                    });
                });
            }
        });
    }
</script>