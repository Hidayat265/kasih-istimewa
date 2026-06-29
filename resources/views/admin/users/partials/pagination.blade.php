@if ($users->lastPage() > 1)
    @if ($users->onFirstPage())
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
    @else
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $users->currentPage() - 1 }}">Previous</a>
    @endif

    @php
        $start = max(1, $users->currentPage() - 2);
        $end = min($users->lastPage(), $users->currentPage() + 2);
    @endphp

    @if ($start > 1)
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
        @if ($start > 2)
            <span class="px-3 py-1 text-gray-400">...</span>
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $users->currentPage())
            <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
        @else
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $users->lastPage())
        @if ($end < $users->lastPage() - 1)
            <span class="px-3 py-1 text-gray-400">...</span>
        @endif
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $users->lastPage() }}">{{ $users->lastPage() }}</a>
    @endif

    @if ($users->hasMorePages())
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $users->currentPage() + 1 }}">Next</a>
    @else
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
    @endif
@endif