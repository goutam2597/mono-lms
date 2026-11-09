<x-layouts.auth title="Become An Instructor - Mono LMS">

    <div class="space-y-6">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-[var(--theme-primary,#344c3d)]">Teach on Mono LMS</span>
            <h1 class="text-3xl font-bold text-[#191919] tracking-tight mt-1">Instructor Registration</h1>
            <p class="text-xs text-[#6d6d6d] mt-1.5">Join top instructors sharing knowledge, creating courses, and earning on Mono LMS.</p>
        </div>

        <form action="{{ route('instructor.register.attempt') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="name" class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>{{ __('auth.full_name') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="Jane Doe" class="w-full bg-white border {{ $errors->has('name') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e]">
                @error('name')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>{{ __('auth.email') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="jane@example.com" class="w-full bg-white border {{ $errors->has('email') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e]">
                @error('email')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="designation" class="text-xs font-semibold text-[#191919]">
                    Professional Title / Headline
                </label>
                <input id="designation" name="designation" type="text" value="{{ old('designation') }}" placeholder="Senior Full-Stack Engineer & Instructor" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e]">
                @error('designation')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>{{ __('auth.password') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input id="password" name="password" type="password" required placeholder="•••••••• (min 8 characters)" class="w-full bg-white border {{ $errors->has('password') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e]">
                @error('password')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="flex items-start gap-2 cursor-pointer select-none text-xs text-[#191919]">
                    <input type="checkbox" name="terms" value="1" required class="control mt-0.5">
                    <span>I agree to the Instructor Terms of Service & Privacy Policy</span>
                </label>
                @error('terms')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="w-full bg-[#344c3d] hover:bg-[#25372c] text-white text-sm font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md mt-2">
                <span>Create Instructor Account</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        @if (\App\Models\Setting::enabled('google_login_enabled') && \App\Models\Setting::enabled('google_allow_instructor') && \App\Models\Setting::get('google_client_id'))
            <div class="flex items-center gap-3 text-[11px] text-[#9a9a9a]">
                <span class="h-px flex-1 bg-[#e7e7e7]"></span>
                <span>or</span>
                <span class="h-px flex-1 bg-[#e7e7e7]"></span>
            </div>
            <a href="{{ route('login.google.redirect', ['panel' => 'instructor']) }}" class="w-full flex items-center justify-center gap-2.5 border border-[#e7e7e7] bg-white hover:bg-[#f7f7f5] text-[#191919] text-xs font-semibold py-3 rounded-xl transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84C6.71 7.31 9.14 5.38 12 5.38z"/></svg>
                Sign up with Google
            </a>
        @endif

        <div class="text-center text-xs text-[#5d5d5d] pt-2 space-y-1">
            <p>
                <span>Already have an instructor account? </span>
                <a href="{{ route('instructor.login') }}" class="font-semibold text-[#738a6e] hover:underline">
                    Instructor Sign In
                </a>
            </p>
            <p>
                <span>Are you a student? </span>
                <a href="{{ route('register') }}" class="text-gray-500 hover:underline">
                    Create Student Account
                </a>
            </p>
        </div>
    </div>

</x-layouts.auth>
