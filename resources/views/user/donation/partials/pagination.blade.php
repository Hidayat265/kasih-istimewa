@if ($donations->lastPage() > 1)
<div class="flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ $donations->firstItem() ?? 0 }}</span> 
        to <span class="font-medium">{{ $donations->lastItem() ?? 0 }}</span> 
        of <span class="font-medium">{{ $donations->total() ?? 0 }}</span> results
    </div>
    <div class="flex flex-wrap justify-center gap-1">
        @if ($donations->onFirstPage())
            <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed text-sm">Previous</span>
        @else
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition text-sm" data-page="{{ $donations->currentPage() - 1 }}">Previous</a>
        @endif

        @php
            $start = max(1, $donations->currentPage() - 2);
            $end = min($donations->lastPage(), $donations->currentPage() + 2);
        @endphp

        @if ($start > 1)
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition text-sm" data-page="1">1</a>
            @if ($start > 2)
                <span class="px-3 py-1 text-gray-400 text-sm">...</span>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $donations->currentPage())
                <span class="px-3 py-1 bg-primary text-white rounded-md text-sm">{{ $i }}</span>
            @else
                <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition text-sm" data-page="{{ $i }}">{{ $i }}</a>
            @endif
        @endfor

        @if ($end < $donations->lastPage())
            @if ($end < $donations->lastPage() - 1)
                <span class="px-3 py-1 text-gray-400 text-sm">...</span>
            @endif
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition text-sm" data-page="{{ $donations->lastPage() }}">{{ $donations->lastPage() }}</a>
        @endif

        @if ($donations->hasMorePages())
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition text-sm" data-page="{{ $donations->currentPage() + 1 }}">Next</a>
        @else
            <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed text-sm">Next</span>
        @endif
    </div>
</div>
@endif