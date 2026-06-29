@if ($donations->lastPage() > 1)
    {{-- Previous --}}
    @if ($donations->onFirstPage())
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
    @else
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $donations->currentPage() - 1 }}">Previous</a>
    @endif

    {{-- Page numbers --}}
    @for ($i = 1; $i <= $donations->lastPage(); $i++)
        @if ($i == $donations->currentPage())
            <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
        @else
            <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
        @endif
    @endfor

    {{-- Next --}}
    @if ($donations->hasMorePages())
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $donations->currentPage() + 1 }}">Next</a>
    @else
        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
    @endif
@endif 