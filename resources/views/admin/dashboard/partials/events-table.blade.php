@forelse($events as $index => $event)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <span class="text-sm font-medium text-gray-900">{{ $event->event_id }}</span>
        </td>
        <td class="px-4 py-4">
            <div class="text-sm font-medium text-gray-900">{{ $event->event_name }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $event->event_location_name ?? 'No location' }}</div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $event->creator->user_name ?? 'Unknown' }}</div>
            <div class="text-xs text-gray-500">{{ $event->event_company_name }}</div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_start_date)->format('d M Y') }}</div>
            <div class="text-xs text-primary">{{ $event->start_session_time ?? '' }}</div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_end_date)->format('d M Y') }}</div>
            <div class="text-xs text-primary">{{ $event->end_session_time ?? '' }}</div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            @php
                $statusClasses = [
                    'Approved' => 'bg-green-100 text-green-800',
                    'Pending' => 'bg-yellow-100 text-yellow-800',
                    'Rejected' => 'bg-red-100 text-red-800',
                    'NeedUpdate' => 'bg-orange-100 text-orange-800',
                ];
                $statusLabels = [
                    'Approved' => 'Approved',
                    'Pending' => 'Pending',
                    'Rejected' => 'Rejected',
                    'NeedUpdate' => 'Needs Update',
                ];
                $status = $event->event_approval_status ?? 'Unknown';
                $class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
                $label = $statusLabels[$status] ?? ucfirst($status);
            @endphp
            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $class }}">
                {{ $label }}
            </span>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.events.show', $event->event_id) }}" 
               class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition-colors text-xs font-medium inline-block">
                <i class="fas fa-eye mr-1"></i> View
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
            No events found.
        </td>
    </tr>
@endforelse