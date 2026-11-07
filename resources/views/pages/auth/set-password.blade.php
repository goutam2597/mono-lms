<x-layouts.auth :title="__('auth.reset_title').' - Dreams LMS'">

    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-[#191919] tracking-tight">{{ __('auth.reset_title') }}</h1>
            <p class="text-xs text-[#6d6d6d] mt-1.5">{{ __('auth.reset_subtitle') }}</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="panel" value="{{ $panel }}">

            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold text-[#191919]">
                    <span>{{ __('auth.email') }}</span>
                </label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required class="w-full bg-white border {{ $errors->has('email') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">
                @error('email')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-semibold text-[#191919]">
                    <span>{{ __('auth.password') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input id="password" name="password" type="password" required placeholder="•••••••• (min 8)" class="w-full bg-white border {{ $errors->has('password') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667]">
                @error('password')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="password_confirmation" class="text-xs font-semibold text-[#191919]">
                    <span>{{ __('auth.confirm_password') }}</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667]">
            </div>

            <button type="submit" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold py-3 rounded-full flex items-center justify-center gap-2 transition-all shadow-md mt-2">
                <span>{{ __('auth.reset_password') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

</x-layouts.auth>
