<x-layouts.app :title="$post->title.' - Mono LMS'" active="blog">

    <!-- Article Hero -->
    <div class="bg-[#08131e] text-white py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-8 space-y-4">
            <span class="text-xs text-gray-400">{{ $post->published_at?->format('d M Y') }} · {{ __('blog.by') }} {{ $post->author?->name ?? 'MonoLMS' }}</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">{{ $post->title }}</h1>
            @if ($post->excerpt)
                <p class="text-gray-300 text-base leading-relaxed">{{ $post->excerpt }}</p>
            @endif
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-12">
        @if ($post->image)
            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-80 object-cover rounded-2xl shadow-md mb-10">
        @endif

        <article class="prose-blog space-y-5 text-[#5d5d5d] leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </article>

        <div class="mt-12 pt-8 border-t border-gray-100">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:underline">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('blog.back') }}
            </a>
        </div>

        @if ($recent->count())
            <div class="mt-16">
                <h2 class="text-xl font-bold text-[#191919] mb-6">{{ __('blog.recent') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($recent as $item)
                        <a href="{{ route('blog.show', $item->slug) }}" class="bg-white border border-[#e7e7e7] rounded-xl overflow-hidden hover:shadow-lg transition-shadow group">
                            <img src="{{ $item->image ?? 'https://picsum.photos/seed/post-'.$item->id.'/640/420' }}" alt="{{ $item->title }}" class="w-full h-32 object-cover">
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors line-clamp-2">{{ $item->title }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $item->published_at?->format('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</x-layouts.app>
