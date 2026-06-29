<div class="overflow-x-auto">
    @if($events->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($events as $event)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $event->event_name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $event->event_start_date ? \Carbon\Carbon::parse($event->event_start_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $event->event_approval_status === 'Approved' ? 'bg-green-100 text-green-800' : 
                                   ($event->event_approval_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $event->event_approval_status ?? 'Draft' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            <div class="flex justify-center space-x-2">
                @if ($events->onFirstPage())
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
                @else
                    <a href="#" class="events-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $events->currentPage() - 1 }}">Previous</a>
                @endif

                @php
                    $start = max(1, $events->currentPage() - 2);
                    $end = min($events->lastPage(), $events->currentPage() + 2);
                @endphp

                @if ($start > 1)
                    <a href="#" class="events-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-1 text-gray-400">...</span>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $events->currentPage())
                        <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
                    @else
                        <a href="#" class="events-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($end < $events->lastPage())
                    @if ($end < $events->lastPage() - 1)
                        <span class="px-3 py-1 text-gray-400">...</span>
                    @endif
                    <a href="#" class="events-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $events->lastPage() }}">{{ $events->lastPage() }}</a>
                @endif

                @if ($events->hasMorePages())
                    <a href="#" class="events-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $events->currentPage() + 1 }}">Next</a>
                @else
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    @else
        <p class="text-gray-500 text-sm">No event or activity records available yet.</p>
    @endif
</div>