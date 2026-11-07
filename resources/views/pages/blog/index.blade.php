<x-layouts.app :title="__('blog.title').' - Mono LMS'" active="blog">

    <!-- Page Header -->
    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <h1 class="text-3xl font-extrabold text-[#191919]">{{ __('blog.title') }}</h1>
            <p class="text-sm text-[#6d6d6d] mt-2">{{ __('blog.subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col">
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="{{ $post->image ?? 'https://picsum.photos/seed/post-'.$post->id.'/640/420' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 space-y-3 flex-1 flex flex-col">
                        <span class="text-xs text-gray-400">{{ $post->published_at?->format('d M Y') }}</span>
                        <h3 class="text-lg font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-sm text-[#6d6d6d] line-clamp-3 flex-1">{{ $post->excerpt }}</p>
                        <span class="text-xs font-semibold text-[#ff4667]">{{ __('blog.read_more') }} →</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white border border-[#e7e7e7] rounded-2xl p-16 text-center">
                    <p class="text-sm text-gray-400">{{ __('blog.empty') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    </div>

</x-layouts.app>
