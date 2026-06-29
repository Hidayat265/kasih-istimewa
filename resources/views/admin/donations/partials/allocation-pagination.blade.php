@if ($allocations->onFirstPage())
    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
@else
    <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $allocations->currentPage() - 1 }}">Previous</a>
@endif

@php
    $start = max(1, $allocations->currentPage() - 2);
    $end = min($allocations->lastPage(), $allocations->currentPage() + 2);
@endphp

@if ($start > 1)
    <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
    @if ($start > 2)
        <span class="px-3 py-1 text-gray-400">...</span>
    @endif
@endif

@for ($i = $start; $i <= $end; $i++)
    @if ($i == $allocations->currentPage())
        <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
    @else
        <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
    @endif
@endfor

@if ($end < $allocations->lastPage())
    @if ($end < $allocations->lastPage() - 1)
        <span class="px-3 py-1 text-gray-400">...</span>
    @endif
    <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $allocations->lastPage() }}">{{ $allocations->lastPage() }}</a>
@endif

@if ($allocations->hasMorePages())
    <a href="#" class="pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $allocations->currentPage() + 1 }}">Next</a>
@else
    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
@endif
