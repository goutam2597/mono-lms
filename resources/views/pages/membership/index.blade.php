<x-layouts.app :title="__('membership.title').' - Mono LMS'" active="membership">

    <!-- Page Header -->
    <div class="bg-[#f4f6f9] py-12 border-b border-[#e7e7e7] text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-8">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-[#ff4667] text-white shadow-sm">{{ __('membership.badge') }}</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#191919] mt-4">{{ __('membership.title') }}</h1>
            <p class="text-sm sm:text-base text-[#6d6d6d] mt-3">{{ __('membership.subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="max-w-6xl mx-auto px-4 sm:px-8 pt-6">
            <div class="bg-[#03c95a]/10 border border-[#03c95a]/30 text-[#0d7a38] px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($plans as $plan)
                <div class="relative bg-white border-2 rounded-2xl p-8 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col {{ $plan->is_popular ? 'border-[#ff4667]' : 'border-[#e7e7e7]' }}">
                    @if ($plan->is_popular)
                        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#ff4667] text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm whitespace-nowrap">
                            ★ {{ __('membership.most_popular') }}
                        </span>
                    @endif

                    <h3 class="text-xl font-bold text-[#191919]">{{ $plan->name }}</h3>
                    <p class="text-sm text-[#6d6d6d] mt-2 min-h-[40px]">{{ $plan->description }}</p>

                    <div class="flex items-baseline gap-2 mt-5">
                        <span class="text-4xl font-extrabold text-[#191919]">{{ __('app.common.currency') }}{{ number_format($plan->price, 2) }}</span>
                        <span class="text-sm text-gray-400">/ {{ __('membership.'.$plan->billing_period) }}</span>
                    </div>

                    <ul class="mt-6 space-y-3 text-sm border-t border-gray-100 pt-6 flex-1">
                        @foreach ($allFeatures as $feature)
                            @php $included = in_array($feature, $plan->features ?? []); @endphp
                            <li class="flex items-start gap-2.5 {{ $included ? 'text-[#5d5d5d]' : 'text-gray-300' }}">
                                @if ($included)
                                    <span class="text-[#03c95a] font-bold mt-0.5">✓</span>
                                @else
                                    <span class="mt-0.5 w-3.5 h-3.5 rounded-full border border-gray-300 inline-flex items-center justify-center text-gray-300 text-[9px] shrink-0">✕</span>
                                @endif
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto pt-8">
                        @auth('student')
                            @if ($currentSubscription?->plan_id === $plan->id && $currentSubscription?->status === 'active')
                                <span class="block w-full text-center bg-[#03c95a]/10 text-[#0d7a38] font-bold py-3 rounded-full text-sm">{{ __('membership.current_plan') }}</span>
                            @else
                                <form action="{{ route('membership.subscribe', $plan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full {{ $plan->is_popular ? 'bg-[#ff4667] hover:bg-[#e03a58]' : 'bg-[#08131e] hover:bg-[#1b2737]' }} text-white font-bold py-3 rounded-full transition-colors text-sm cursor-pointer">
                                        {{ __('membership.subscribe') }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center {{ $plan->is_popular ? 'bg-[#ff4667] hover:bg-[#e03a58]' : 'bg-[#08131e] hover:bg-[#1b2737]' }} text-white font-bold py-3 rounded-full transition-colors text-sm">
                                {{ __('membership.sign_in_to_subscribe') }}
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-[#e7e7e7] rounded-2xl p-16 text-center">
                    <p class="text-sm text-gray-400">{{ __('membership.empty') }}</p>
                </div>
            @endforelse
        </div>

        <p class="text-center text-xs text-gray-400 mt-10">{{ __('membership.cancel_hint') }}</p>
    </div>

</x-layouts.app>
