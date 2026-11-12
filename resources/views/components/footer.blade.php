<footer class="bg-[#08131e] text-white pt-16 pb-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-12">
            <!-- Brand Column -->
            <div class="lg:col-span-4 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center shrink-0">
                    <img src="{{ \App\Models\Setting::logoUrl(asset('images/logo-white.png')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" width="150" height="40" decoding="async" class="h-9 sm:h-10 w-[135px] sm:w-[150px] aspect-[938/250] object-contain shrink-0" />
                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    {{ \App\Models\Setting::get('footer_about_text') ?: __('app.footer.about') }}
                </p>
                <!-- App Badges -->
                @php
                    $appStoreEnabled = \App\Models\Setting::isAppStoreEnabled();
                    $playStoreEnabled = \App\Models\Setting::isPlayStoreEnabled();
                    $appStoreUrl = \App\Models\Setting::appStoreUrl();
                    $playStoreUrl = \App\Models\Setting::playStoreUrl();
                    $hasAppStore = $appStoreEnabled && filled($appStoreUrl);
                    $hasPlayStore = $playStoreEnabled && filled($playStoreUrl);
                @endphp
                @if ($hasAppStore || $hasPlayStore)
                    <div class="flex items-center gap-3 pt-2">
                        @if ($hasAppStore)
                            <a href="{{ $appStoreUrl }}"
                               @if(str_starts_with($appStoreUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif
                               class="inline-block transition-transform duration-200 hover:scale-105 opacity-90 hover:opacity-100"
                               title="Download on the App Store">
                                <img src="{{ asset('images/app-store-badge.svg') }}" alt="Download on the App Store" class="h-10 w-auto" />
                            </a>
                        @endif
                        @if ($hasPlayStore)
                            <a href="{{ $playStoreUrl }}"
                               @if(str_starts_with($playStoreUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif
                               class="inline-block transition-transform duration-200 hover:scale-105 opacity-90 hover:opacity-100"
                               title="Get it on Google Play">
                                <img src="{{ asset('images/google-play-badge.svg') }}" alt="Get it on Google Play" class="h-10 w-auto" />
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Dynamic Footer Navigation Columns -->
            @php
                $footerCols = \App\Models\Setting::footerColumns();
                $footerBottom = \App\Models\Setting::footerBottomLinks();
            @endphp
            @foreach ($footerCols as $col)
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white mb-4">{{ $col['title'] ?? 'Links' }}</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        @foreach ($col['links'] ?? [] as $link)
                            @php
                                $lUrl = $link['url'] ?? '#';
                                $resLUrl = str_starts_with($lUrl, 'http') || $lUrl === '#' ? $lUrl : url($lUrl);
                            @endphp
                            <li>
                                <a href="{{ $resLUrl }}"
                                   target="{{ $link['target'] ?? '_self' }}"
                                   @if(($link['target'] ?? '') === '_blank') rel="noopener noreferrer" @endif
                                   class="hover:text-[#bfcfbb] transition-colors">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <!-- Newsletter Column -->
            @if (\App\Models\Setting::get('footer_newsletter_enabled', '1') === '1')
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white mb-4">{{ \App\Models\Setting::get('footer_newsletter_title') ?: __('app.footer.newsletter') }}</h4>
                    <p class="text-xs text-gray-400 mb-3">{{ \App\Models\Setting::get('footer_newsletter_hint') ?: __('app.footer.newsletter_hint') }}</p>
                    <div class="space-y-2">
                        <input type="email" placeholder="{{ __('app.footer.email_placeholder') }}" class="w-full bg-[#142333] border border-gray-700 rounded-lg px-3 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#738a6e]" />
                        <button class="w-full bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-semibold py-2 rounded-lg transition-colors">
                            {{ __('app.footer.subscribe') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bottom Copyright & Compliance -->
        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-400">
            <p>{{ \App\Models\Setting::get('footer_copyright') ?: '© '.date('Y').' Mono LMS. '.__('app.footer.copyright') }}</p>
            @if (\App\Models\Setting::get('footer_payment_methods_enabled', '1') === '1')
                @php
                    $stripWidth = \App\Models\Setting::paymentMethodsWidth();
                @endphp
                <div class="flex items-center justify-center py-1 md:py-0">
                    <img src="{{ \App\Models\Setting::paymentMethodsUrl() }}" alt="Accepted Payment Methods" style="{{ $stripWidth !== 'auto' ? 'max-width: '.$stripWidth.';' : '' }} width: 100%; height: auto;" class="max-h-8 sm:max-h-9 w-auto object-contain opacity-90 hover:opacity-100 transition-opacity" />
                </div>
            @endif
            <div class="flex items-center gap-4 flex-wrap">
                @foreach ($footerBottom as $bIndex => $bLink)
                    @php
                        $bUrl = $bLink['url'] ?? '#';
                        $resBUrl = str_starts_with($bUrl, 'http') || $bUrl === '#' ? $bUrl : url($bUrl);
                    @endphp
                    @if ($bIndex > 0)
                        <span>|</span>
                    @endif
                    <a href="{{ $resBUrl }}"
                       target="{{ $bLink['target'] ?? '_self' }}"
                       @if(($bLink['target'] ?? '') === '_blank') rel="noopener noreferrer" @endif
                       class="hover:text-white transition-colors">
                        {!! $bLink['label'] !!}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
