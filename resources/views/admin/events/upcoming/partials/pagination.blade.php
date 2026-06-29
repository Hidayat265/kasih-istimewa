@if ($events->lastPage() > 1)
    @if ($events->onFirstPage())
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
    @else
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $events->currentPage() - 1 }}">Previous</a>
    @endif

    @php
        $start = max(1, $events->currentPage() - 2);
        $end = min($events->lastPage(), $events->currentPage() + 2);
    @endphp

    @if ($start > 1)
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
        @if ($start > 2)
            <span class="px-3 py-1 text-gray-400">...</span>
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $events->currentPage())
            <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
        @else
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $events->lastPage())
        @if ($end < $events->lastPage() - 1)
            <span class="px-3 py-1 text-gray-400">...</span>
        @endif
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $events->lastPage() }}">{{ $events->lastPage() }}</a>
    @endif

    @if ($events->hasMorePages())
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $events->currentPage() + 1 }}">Next</a>
    @else
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
    @endif
@endif