<x-layouts.app :title="($page->meta_title ?: $page->title).' - Mono LMS'" active="page">

    @if ($isDraftPreview ?? false)
        <div class="bg-amber-500 text-slate-950 font-bold text-xs py-2 px-4 text-center sticky top-0 z-50 shadow-md">
            ⚠️ Draft Preview Mode: This page is currently unpublished and only visible to administrators.
        </div>
    @endif

    <!-- Hero Header -->
    <section class="bg-[#f4f6f9] py-14 sm:py-20 border-b border-[#e7e7e7]">
        <div class="max-w-4xl mx-auto px-4 sm:px-8 text-center">
            @if ($eyebrow = $page->setting('eyebrow'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                    {{ $eyebrow }}
                </span>
            @endif
            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#191919] tracking-tight">
                {{ $page->title }}
            </h1>
            @if ($subtitle = $page->setting('subtitle'))
                <p class="text-base sm:text-lg text-slate-600 mt-4 leading-relaxed max-w-2xl mx-auto">
                    {{ $subtitle }}
                </p>
            @endif
            <nav class="flex items-center justify-center gap-2 text-xs text-[#6d6d6d] mt-6">
                <a href="{{ route('home') }}" class="hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                <span>/</span>
                <span class="text-[#738a6e] font-medium">{{ $page->title }}</span>
            </nav>
        </div>
    </section>

    <!-- Main Content Body -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-8">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-12 shadow-xs">
                @if ($page->content)
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed
                                [&>h2]:text-2xl [&>h2]:font-bold [&>h2]:text-slate-900 [&>h2]:mt-8 [&>h2]:mb-4
                                [&>h3]:text-xl [&>h3]:font-bold [&>h3]:text-slate-900 [&>h3]:mt-6 [&>h3]:mb-3
                                [&>p]:text-sm sm:[&>p]:text-base [&>p]:leading-relaxed [&>p]:mb-4
                                [&>ul]:list-disc [&>ul]:list-inside [&>ul]:space-y-2 [&>ul]:mb-6
                                [&>ol]:list-decimal [&>ol]:list-inside [&>ol]:space-y-2 [&>ol]:mb-6">
                        {!! $page->content !!}
                    </div>
                @else
                    <div class="py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        <p class="text-sm font-semibold text-slate-600">No content published yet</p>
                        <p class="text-xs text-slate-400 mt-1">This page has no content body to display.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-layouts.app>
