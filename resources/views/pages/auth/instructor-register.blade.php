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
