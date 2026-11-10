<x-layouts.app :title="($page->meta_title ?: $page->title).' - Mono LMS'" active="privacy">

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
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs text-slate-500 mt-6">
                @if ($effectiveDate = $page->setting('effective_date'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-white border border-slate-200 text-slate-700 font-semibold shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Last Revised: {{ $effectiveDate }}
                    </span>
                @endif
                <nav class="flex items-center gap-2">
                    <a href="{{ route('home') }}" class="hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                    <span>/</span>
                    <span class="text-[#738a6e] font-medium">{{ $page->title }}</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Main Legal Prose Body -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-8">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-12 shadow-xs">
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed
                            [&>h3]:text-xl [&>h3]:font-bold [&>h3]:text-slate-900 [&>h3]:mt-8 [&>h3]:mb-3 [&>h3]:pt-4 [&>h3]:border-t [&>h3]:border-slate-100 first:[&>h3]:mt-0 first:[&>h3]:pt-0 first:[&>h3]:border-0
                            [&>p]:text-sm sm:[&>p]:text-base [&>p]:leading-relaxed [&>p]:mb-4
                            [&>ul]:list-disc [&>ul]:list-inside [&>ul]:space-y-2 [&>ul]:mb-6 [&>ul]:text-sm sm:[&>ul]:text-base">
                    {!! $page->content !!}
                </div>

                @if ($dpoEmail = $page->setting('dpo_email'))
                    <div class="mt-12 p-6 bg-slate-50 border border-slate-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Data Protection Officer (DPO)</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Contact our compliance officers directly for data subject requests.</p>
                        </div>
                        <a href="mailto:{{ $dpoEmail }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[var(--theme-primary,#344c3d)] text-white text-xs font-semibold hover:bg-[var(--theme-primary-hover,#25372c)] transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <span>{{ $dpoEmail }}</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-layouts.app>
