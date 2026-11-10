<footer class="bg-[#08131e] text-white pt-16 pb-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-12">
            <!-- Brand Column -->
            <div class="lg:col-span-4 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center shrink-0">
                    <img src="{{ \App\Models\Setting::logoUrl(asset('images/logo-white.png')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" width="150" height="40" decoding="async" class="h-9 sm:h-10 w-[135px] sm:w-[150px] aspect-[938/250] object-contain shrink-0" />
                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    {{ __('app.footer.about') }}
                </p>
                <!-- App Badges -->
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="inline-block transition-transform duration-200 hover:scale-105 opacity-90 hover:opacity-100" title="Download on the App Store">
                        <img src="{{ asset('images/app-store-badge.svg') }}" alt="Download on the App Store" class="h-10 w-auto" />
                    </a>
                    <a href="#" class="inline-block transition-transform duration-200 hover:scale-105 opacity-90 hover:opacity-100" title="Get it on Google Play">
                        <img src="{{ asset('images/google-play-badge.svg') }}" alt="Get it on Google Play" class="h-10 w-auto" />
                    </a>
                </div>
            </div>

            <!-- Support Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">{{ __('app.footer.support') }}</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.help_center') }}</a></li>
                    <li><a href="#" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.faq') }}</a></li>
                    <li><a href="#" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.contact') }}</a></li>
                    <li><a href="#" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.about_us') }}</a></li>
                </ul>
            </div>

            <!-- Pages Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">{{ __('app.footer.pages') }}</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="{{ route('courses.index') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.nav.courses') }}</a></li>
                    <li><a href="{{ route('instructors.index') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.nav.instructors') }}</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.nav.blog') }}</a></li>
                    <li><a href="{{ route('membership.index') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.nav.membership') }}</a></li>
                </ul>
            </div>

            <!-- Useful Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">{{ __('app.footer.useful') }}</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="{{ auth('instructor')->check() ? route('instructor.dashboard') : route('instructor.register') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.become_instructor') }}</a></li>
                    <li><a href="{{ auth('instructor')->check() ? route('instructor.dashboard') : route('instructor.login') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.instructor_portal') }}</a></li>
                    <li><a href="{{ auth('student')->check() ? route('student.dashboard') : route('login') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.footer.student_portal') }}</a></li>
                    <li><a href="{{ auth('student')->check() ? route('student.dashboard') : route('register') }}" class="hover:text-[#bfcfbb] transition-colors">{{ __('app.nav.register') }}</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">{{ __('app.footer.newsletter') }}</h4>
                <p class="text-xs text-gray-400 mb-3">{{ __('app.footer.newsletter_hint') }}</p>
                <div class="space-y-2">
                    <input type="email" placeholder="{{ __('app.footer.email_placeholder') }}" class="w-full bg-[#142333] border border-gray-700 rounded-lg px-3 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#738a6e]" />
                    <button class="w-full bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-semibold py-2 rounded-lg transition-colors">
                        {{ __('app.footer.subscribe') }}
                    </button>
                </div>
            </div>
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
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition-colors">Terms & Conditions</a>
                <span>|</span>
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
