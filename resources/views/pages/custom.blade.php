<x-layouts.app :title="($page->meta_title ?: $page->title).' - Mono LMS'" active="page">

    @php
        $blocks = $page->setting('blocks', []);
        $hasBlocks = is_array($blocks) && count($blocks) > 0;
        $hasHeroBlock = $hasBlocks && ($blocks[0]['type'] ?? '') === 'hero';
    @endphp

    @if ($isDraftPreview ?? false)
        <div class="bg-amber-500 text-slate-950 font-bold text-xs py-2 px-4 text-center sticky top-0 z-50 shadow-md">
            ⚠️ Draft Preview Mode: This page is currently unpublished and only visible to administrators.
        </div>
    @endif

    @if (! $hasHeroBlock)
        <!-- Standard Hero Header (When no custom hero block is present) -->
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
    @endif

    @if ($hasBlocks)
        <!-- Visual Drag-and-Drop Page Builder Rendered Sections -->
        <div class="space-y-16 sm:space-y-24 py-12 sm:py-16">
            @foreach ($blocks as $block)
                @php
                    $type = $block['type'] ?? '';
                    $data = $block['data'] ?? [];
                @endphp

                @if ($type === 'hero')
                    <!-- 1. Hero Block -->
                    @php
                        $bgTheme = $data['bg_theme'] ?? 'dark';
                        $bgClasses = match($bgTheme) {
                            'forest' => 'bg-[#344c3d] text-white',
                            'light' => 'bg-[#f4f6f9] text-slate-900 border-y border-slate-200',
                            'gradient' => 'bg-gradient-to-br from-[#08131e] via-[#112435] to-[#1f3747] text-white',
                            default => 'bg-[#08131e] text-white',
                        };
                        $heroImg = $data['image'] ?? '';
                    @endphp
                    <section class="{{ $bgClasses }} py-16 sm:py-24">
                        <div class="max-w-6xl mx-auto px-4 sm:px-8">
                            <div class="grid grid-cols-1 {{ $heroImg ? 'lg:grid-cols-12 gap-12 items-center' : 'text-center' }}">
                                <div class="{{ $heroImg ? 'lg:col-span-7' : 'max-w-3xl mx-auto' }} space-y-6">
                                    @if (!empty($data['eyebrow']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $bgTheme === 'light' ? 'bg-emerald-100 text-emerald-800' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                            {{ $data['eyebrow'] }}
                                        </span>
                                    @endif
                                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                                        {{ $data['title'] ?? 'Welcome to Mono LMS' }}
                                    </h1>
                                    @if (!empty($data['subtitle']))
                                        <p class="text-base sm:text-lg {{ $bgTheme === 'light' ? 'text-slate-600' : 'text-gray-300' }} leading-relaxed">
                                            {{ $data['subtitle'] }}
                                        </p>
                                    @endif
                                    @if (!empty($data['btn_primary_label']) || !empty($data['btn_secondary_label']))
                                        <div class="flex flex-wrap items-center gap-4 pt-2 {{ $heroImg ? '' : 'justify-center' }}">
                                            @if (!empty($data['btn_primary_label']))
                                                <a href="{{ $data['btn_primary_url'] ?? '#' }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg transition-transform hover:-translate-y-0.5">
                                                    {{ $data['btn_primary_label'] }}
                                                </a>
                                            @endif
                                            @if (!empty($data['btn_secondary_label']))
                                                <a href="{{ $data['btn_secondary_url'] ?? '#' }}" class="px-6 py-3.5 rounded-xl font-bold text-sm {{ $bgTheme === 'light' ? 'bg-white border border-slate-300 text-slate-800 hover:bg-slate-50' : 'bg-white/10 text-white hover:bg-white/20 border border-white/20' }} transition-colors">
                                                    {{ $data['btn_secondary_label'] }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @if ($heroImg)
                                    <div class="lg:col-span-5">
                                        <img src="{{ $heroImg }}" alt="{{ $data['title'] ?? 'Hero Image' }}" class="w-full rounded-2xl shadow-2xl object-cover aspect-4/3" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                @elseif ($type === 'features_grid')
                    <!-- 2. Features Grid Block -->
                    <section class="max-w-6xl mx-auto px-4 sm:px-8">
                        @if (!empty($data['title']) || !empty($data['eyebrow']))
                            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                                @if (!empty($data['eyebrow']))
                                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block">{{ $data['eyebrow'] }}</span>
                                @endif
                                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $data['title'] ?? 'Key Features' }}</h2>
                                @if (!empty($data['subtitle']))
                                    <p class="text-sm sm:text-base text-slate-600">{{ $data['subtitle'] }}</p>
                                @endif
                            </div>
                        @endif

                        @php
                            $colsCount = (int) ($data['columns'] ?? 3);
                            $gridCols = match($colsCount) {
                                2 => 'sm:grid-cols-2',
                                4 => 'sm:grid-cols-2 lg:grid-cols-4',
                                default => 'sm:grid-cols-2 lg:grid-cols-3',
                            };
                        @endphp
                        <div class="grid grid-cols-1 {{ $gridCols }} gap-6">
                            @foreach ($data['items'] ?? [] as $item)
                                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                                    <div class="space-y-3">
                                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xl border border-emerald-100">
                                            @if (($item['icon'] ?? '') === 'trophy') 🏆
                                            @elseif (($item['icon'] ?? '') === 'book') 📚
                                            @elseif (($item['icon'] ?? '') === 'shield') 🛡️
                                            @elseif (($item['icon'] ?? '') === 'users') 👥
                                            @elseif (($item['icon'] ?? '') === 'rocket') 🚀
                                            @elseif (($item['icon'] ?? '') === 'zap') ⚡
                                            @elseif (($item['icon'] ?? '') === 'heart') ❤️
                                            @else ✨
                                            @endif
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">{{ $item['title'] ?? 'Feature' }}</h3>
                                        <p class="text-sm text-slate-600 leading-relaxed">{{ $item['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                @elseif ($type === 'split_content')
                    <!-- 3. Split Content Block -->
                    @php
                        $isImgLeft = ($data['image_position'] ?? 'right') === 'left';
                    @endphp
                    <section class="max-w-6xl mx-auto px-4 sm:px-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                            @if (!empty($data['image']) && $isImgLeft)
                                <div class="lg:col-span-5 order-2 lg:order-1">
                                    <img src="{{ $data['image'] }}" alt="{{ $data['title'] ?? '' }}" class="w-full rounded-2xl shadow-xl object-cover aspect-4/3" />
                                </div>
                            @endif

                            <div class="lg:col-span-7 space-y-6 {{ $isImgLeft ? 'order-1 lg:order-2' : '' }}">
                                @if (!empty($data['eyebrow']))
                                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block">{{ $data['eyebrow'] }}</span>
                                @endif
                                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                                    {{ $data['title'] ?? '' }}
                                </h2>
                                @if (!empty($data['description']))
                                    <p class="text-base text-slate-600 leading-relaxed">
                                        {{ $data['description'] }}
                                    </p>
                                @endif

                                @if (!empty($data['points']) && is_array($data['points']))
                                    <ul class="space-y-3">
                                        @foreach ($data['points'] as $pt)
                                            <li class="flex items-start gap-3 text-sm text-slate-700 font-medium">
                                                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                <span>{{ $pt }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if (!empty($data['btn_label']))
                                    <div class="pt-2">
                                        <a href="{{ $data['btn_url'] ?? '#' }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition-colors">
                                            <span>{{ $data['btn_label'] }}</span>
                                            <span>&rarr;</span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if (!empty($data['image']) && ! $isImgLeft)
                                <div class="lg:col-span-5">
                                    <img src="{{ $data['image'] }}" alt="{{ $data['title'] ?? '' }}" class="w-full rounded-2xl shadow-xl object-cover aspect-4/3" />
                                </div>
                            @endif
                        </div>
                    </section>

                @elseif ($type === 'stats')
                    <!-- 4. Stats Counter Block -->
                    <section class="max-w-6xl mx-auto px-4 sm:px-8">
                        <div class="p-8 sm:p-12 rounded-3xl bg-slate-900 text-white shadow-xl">
                            @if (!empty($data['title']))
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-center mb-8 tracking-tight">{{ $data['title'] }}</h2>
                            @endif
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-800">
                                @foreach ($data['items'] ?? [] as $st)
                                    <div class="pt-4 sm:pt-0 px-4">
                                        <div class="text-3xl sm:text-5xl font-black text-emerald-400 tracking-tight">{{ $st['value'] ?? '0' }}</div>
                                        <div class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-300 mt-2">{{ $st['label'] ?? '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                @elseif ($type === 'faq')
                    <!-- 5. FAQ Accordion Block -->
                    <section class="max-w-4xl mx-auto px-4 sm:px-8">
                        <div class="text-center mb-10 space-y-2">
                            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $data['title'] ?? 'Frequently Asked Questions' }}</h2>
                            @if (!empty($data['subtitle']))
                                <p class="text-sm text-slate-500">{{ $data['subtitle'] }}</p>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @foreach ($data['items'] ?? [] as $faq)
                                <details class="group bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs hover:border-slate-300 transition-colors">
                                    <summary class="flex items-center justify-between p-5 text-sm sm:text-base font-bold text-slate-900 cursor-pointer list-none select-none">
                                        <span>{{ $faq['question'] ?? '' }}</span>
                                        <svg class="w-4 h-4 text-slate-400 group-open:rotate-180 transition-transform duration-200 shrink-0 ml-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </summary>
                                    <div class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                                        {!! nl2br(e($faq['answer'] ?? '')) !!}
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </section>

                @elseif ($type === 'cta_banner')
                    <!-- 6. Call To Action Banner -->
                    <section class="max-w-6xl mx-auto px-4 sm:px-8">
                        <div class="p-8 sm:p-14 rounded-3xl bg-[#344c3d] text-white text-center shadow-xl space-y-4">
                            @if (!empty($data['eyebrow']))
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-300 block">{{ $data['eyebrow'] }}</span>
                            @endif
                            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight max-w-2xl mx-auto leading-tight">
                                {{ $data['title'] ?? 'Start Learning Today' }}
                            </h2>
                            @if (!empty($data['subtitle']))
                                <p class="text-base text-emerald-100 max-w-xl mx-auto leading-relaxed">
                                    {{ $data['subtitle'] }}
                                </p>
                            @endif
                            <div class="pt-4">
                                <a href="{{ $data['btn_url'] ?? '#' }}" class="inline-block px-8 py-4 rounded-xl bg-white text-[#344c3d] font-bold text-sm uppercase tracking-wider shadow-md hover:bg-slate-100 transition-transform hover:-translate-y-0.5">
                                    {{ $data['btn_label'] ?? 'Get Started Now' }}
                                </a>
                            </div>
                        </div>
                    </section>

                @elseif ($type === 'testimonial')
                    <!-- 7. Testimonial Spotlight -->
                    <section class="max-w-4xl mx-auto px-4 sm:px-8">
                        <div class="p-8 sm:p-12 rounded-3xl bg-slate-50 border border-slate-200 text-center space-y-4 shadow-xs">
                            <div class="flex items-center justify-center gap-1 text-amber-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <blockquote class="text-lg sm:text-2xl font-medium text-slate-900 leading-relaxed max-w-2xl mx-auto italic">
                                “{{ $data['quote'] ?? 'Mono LMS has completely transformed the way our team learns and advances their careers.' }}”
                            </blockquote>
                            <div class="pt-2">
                                <div class="font-bold text-slate-900">{{ $data['author_name'] ?? 'Alex Johnson' }}</div>
                                <div class="text-xs text-slate-500">{{ $data['author_role'] ?? 'Senior Software Engineer' }}</div>
                            </div>
                        </div>
                    </section>

                @elseif ($type === 'video_embed')
                    <!-- 8. Video Embed -->
                    <section class="max-w-4xl mx-auto px-4 sm:px-8">
                        @if (!empty($data['title']))
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 text-center mb-6">{{ $data['title'] }}</h2>
                        @endif
                        <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-xl bg-black">
                            <iframe src="{{ $data['video_url'] ?? '' }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </section>

                @elseif ($type === 'rich_text')
                    <!-- 9. Rich Text Content -->
                    <section class="max-w-4xl mx-auto px-4 sm:px-8">
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-xs prose prose-slate max-w-none">
                            {!! $data['body'] ?? '' !!}
                        </div>
                    </section>
                @endif
            @endforeach
        </div>
    @else
        <!-- Legacy Standard Prose Body (When no visual blocks) -->
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
    @endif

</x-layouts.app>
