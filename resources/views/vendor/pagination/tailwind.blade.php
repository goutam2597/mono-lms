@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="w-full flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Mobile View: Prev / Next with compact count --}}
        <div class="flex items-center justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold text-gray-400 bg-[#f4f6f9] border border-gray-200 rounded-lg cursor-not-allowed">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold text-[#191919] bg-white border border-[#e7e7e7] rounded-lg hover:border-[#738a6e] hover:text-[#344c3d] transition-colors shadow-2xs">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            <span class="text-xs text-[#6d6d6d] font-medium">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold text-[#191919] bg-white border border-[#e7e7e7] rounded-lg hover:border-[#738a6e] hover:text-[#344c3d] transition-colors shadow-2xs">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold text-gray-400 bg-[#f4f6f9] border border-gray-200 rounded-lg cursor-not-allowed">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop View: "Showing X to Y of Z results" + numeric buttons --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-sm text-[#6d6d6d]">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-[#191919]">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-bold text-[#191919]">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-bold text-[#191919]">{{ $paginator->count() }}</span>
                    @endif
                    {!! __('of') !!}
                    <span class="font-bold text-[#191919]">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div class="flex items-center gap-1.5">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="w-9 h-9 flex items-center justify-center text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 flex items-center justify-center text-[#191919] bg-white border border-[#e7e7e7] rounded-lg hover:border-[#738a6e] hover:text-[#344c3d] hover:bg-[#f4f6f9] transition-colors shadow-2xs" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="w-9 h-9 flex items-center justify-center text-sm font-medium text-gray-400 cursor-default">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="w-9 h-9 flex items-center justify-center text-sm font-bold text-white bg-[#344c3d] rounded-lg shadow-xs select-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center text-sm font-semibold text-[#191919] bg-white border border-[#e7e7e7] rounded-lg hover:border-[#738a6e] hover:text-[#344c3d] hover:bg-[#f4f6f9] transition-colors shadow-2xs" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 flex items-center justify-center text-[#191919] bg-white border border-[#e7e7e7] rounded-lg hover:border-[#738a6e] hover:text-[#344c3d] hover:bg-[#f4f6f9] transition-colors shadow-2xs" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" class="w-9 h-9 flex items-center justify-center text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
