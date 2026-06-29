@forelse($events as $index => $event)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <span class="text-sm font-medium text-gray-900">{{ $event->event_id }}</span>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $event->event_name }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $event->event_location_name ?? 'No location' }}</div>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <a href="{{ route('admin.users.profile', $event->event_created_by_id) }}" 
               class="text-sm text-gray-900 hover:text-primary transition-colors">
                {{ $event->creator->user_name ?? 'Unknown' }}
            </a>
            <div class="text-xs text-gray-500">{{ $event->event_company_name }}</div>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-600">{{ $event->created_at ? $event->created_at->format('d M Y') : 'N/A' }}</div>
            <div class="text-xs text-gray-500">{{ $event->created_at ? $event->created_at->format('h:i A') : '' }}</div>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_start_date)->format('d M Y') }}</div>
            <div class="text-xs text-primary">{{ $event->start_session_time ?? '' }}</div>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_end_date)->format('d M Y') }}</div>
            <div class="text-xs text-primary">{{ $event->end_session_time ?? '' }}</div>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                {{ $event->event_approval_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800' }}">
                {{ $event->event_approval_status === 'Pending' ? 'Awaiting Review' : 'Needs Update' }}
            </span>
            @if($event->event_remarks)
                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate" title="{{ $event->event_remarks }}">
                    <i class="fas fa-comment mr-1"></i> {{ $event->event_remarks }}
                </div>
            @endif
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.events.show', $event->event_id) }}" 
               class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition-colors text-xs font-medium">
                View Details
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
            No pending events found.
        </td>
    </tr>
@endforelse