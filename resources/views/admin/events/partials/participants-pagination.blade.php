@if ($participants->lastPage() > 1)
    <div class="flex justify-center space-x-2 mt-4">
        @if ($participants->onFirstPage())
            <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
        @else
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->currentPage() - 1 }}">Previous</a>
        @endif

        @php
            $start = max(1, $participants->currentPage() - 2);
            $end = min($participants->lastPage(), $participants->currentPage() + 2);
        @endphp

        @if ($start > 1)
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
            @if ($start > 2)
                <span class="px-3 py-1 text-gray-400">...</span>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $participants->currentPage())
                <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
            @else
                <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
            @endif
        @endfor

        @if ($end < $participants->lastPage())
            @if ($end < $participants->lastPage() - 1)
                <span class="px-3 py-1 text-gray-400">...</span>
            @endif
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->lastPage() }}">{{ $participants->lastPage() }}</a>
        @endif

        @if ($participants->hasMorePages())
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $participants->currentPage() + 1 }}">Next</a>
        @else
            <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
        @endif
    </div>
@endif