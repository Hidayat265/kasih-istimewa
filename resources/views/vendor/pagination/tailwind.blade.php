@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex items-center justify-between flex-1 sm:hidden 
            px-4 py-2 backdrop-blur-md bg-primary/10 rounded-3xl shadow-md">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="px-2 py-1 text-gray-400 text-xs cursor-not-allowed">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" 
           class="px-2 py-1 text-xs hover:bg-primary/15 rounded-full transition">
            ‹
        </a>
    @endif

    {{-- Page Indicator --}}
    <span class="text-xs font-medium">
        {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
    </span>

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" 
           class="px-2 py-1 text-xs hover:bg-primary/15 rounded-full transition">
            ›
        </a>
    @else
        <span class="px-2 py-1 text-gray-400 text-xs cursor-not-allowed">›</span>
    @endif

</div>


        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between mt-2">
            <div>
                <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>


                <span class="flex items-center gap-1 px-3 py-1 backdrop-blur-md bg-primary/10 rounded-3xl shadow-md ">


                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-2 py-1 text-gray-400 text-xs cursor-not-allowed">‹</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                    class="px-2 py-1 text-xs hover:bg-primary/15 rounded-full transition">
                    ‹
                    </a>
                @endif

                {{-- Numbers --}}
                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-1 text-xs font-semibold 
                                            bg-primary/15 rounded-full">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                class="px-2 py-1 text-xs hover:bg-primary/15 rounded-full transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                    class="px-2 py-1 text-xs hover:bg-primary/15 rounded-md transition">
                    ›
                    </a>
                @else
                    <span class="px-2 py-1 text-gray-400 text-xs cursor-not-allowed">›</span>
                @endif



            </div>
        </div>
    </nav>
@endif
