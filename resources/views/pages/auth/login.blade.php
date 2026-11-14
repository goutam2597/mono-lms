<x-layouts.auth title="Sign into Your Account - Mono LMS">

    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-[#191919] tracking-tight">{{ __('auth.welcome_back') }}</h1>
            <p class="text-xs text-[#6d6d6d] mt-1.5">{{ __('auth.sign_in_subtitle') }}</p>
        </div>

        @if (session('status'))
            <div class="bg-[#03c95a]/10 border border-[#03c95a]/30 text-[#0d7a38] px-4 py-3 rounded-xl text-xs font-medium">{{ session('status') }}</div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>{{ __('auth.email') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative flex items-center">
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full bg-white border {{ $errors->has('email') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-lg px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e] pr-10">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                @error('email')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Password Input -->
            <div class="space-y-1.5">
                <label for="password" class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>{{ __('auth.password') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative flex items-center">
                    <input id="password" name="password" type="password" required placeholder="••••••••" class="w-full bg-white border {{ $errors->has('password') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-lg px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e] pr-10">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </div>
                @error('password')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="control">
                    <span class="text-[#191919]">{{ __('auth.remember_me') }}</span>
                </label>
                <a href="{{ route('password.request') }}" class="font-medium text-[#738a6e] hover:underline">
                    {{ __('auth.forgot_password') }}
                </a>
            </div>

            <!-- Bot Protection -->
            <x-turnstile action="login" />

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-[#344c3d] hover:bg-[#25372c] text-white text-sm font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md mt-2">
                <span>{{ __('auth.sign_in') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        @if (\App\Models\Setting::enabled('google_login_enabled') && \App\Models\Setting::enabled('google_allow_student') && \App\Models\Setting::get('google_client_id'))
            <div class="flex items-center gap-3 text-[11px] text-[#9a9a9a]">
                <span class="h-px flex-1 bg-[#e7e7e7]"></span>
                <span>or</span>
                <span class="h-px flex-1 bg-[#e7e7e7]"></span>
            </div>
            <a href="{{ route('login.google.redirect', ['panel' => 'student']) }}" class="w-full flex items-center justify-center gap-2.5 border border-[#e7e7e7] bg-white hover:bg-[#f7f7f5] text-[#191919] text-xs font-semibold py-3 rounded-xl transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84C6.71 7.31 9.14 5.38 12 5.38z"/></svg>
                {{ __('auth.sign_in') }} with Google
            </a>
        @endif

        <!-- Sign Up Switcher -->
        <div class="text-center text-xs text-[#5d5d5d] pt-2">
            <span>{{ __('auth.no_account') }} </span>
            <a href="{{ route('register') }}" class="font-semibold text-[#738a6e] hover:underline">
                {{ __('auth.register') }}
            </a>
        </div>
    </div>

</x-layouts.auth>
