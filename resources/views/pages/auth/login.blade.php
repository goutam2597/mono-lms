<x-layouts.auth title="Sign into Your Account - Dreams LMS">

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
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full bg-white border {{ $errors->has('email') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667] pr-10">
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
                    <input id="password" name="password" type="password" required placeholder="••••••••" class="w-full bg-white border {{ $errors->has('password') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667] pr-10">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </div>
                @error('password')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#ff4667] focus:ring-[#ff4667] border-gray-300">
                    <span class="text-[#191919]">{{ __('auth.remember_me') }}</span>
                </label>
                <a href="{{ route('password.request') }}" class="font-medium text-[#ff4667] hover:underline">
                    {{ __('auth.forgot_password') }}
                </a>
            </div>

            <!-- Submit Button (Exact Figma style) -->
            <button type="submit" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold py-3 rounded-full flex items-center justify-center gap-2 transition-all shadow-md mt-2">
                <span>{{ __('auth.sign_in') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Sign Up Switcher -->
        <div class="text-center text-xs text-[#5d5d5d] pt-2">
            <span>{{ __('auth.no_account') }} </span>
            <a href="{{ route('register') }}" class="font-semibold text-[#ff4667] hover:underline">
                {{ __('auth.register') }}
            </a>
        </div>
    </div>

</x-layouts.auth>
