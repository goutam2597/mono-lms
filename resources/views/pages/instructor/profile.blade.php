<x-layouts.dashboard :title="__('instructor.profile.title').' - Mono LMS'" active="profile" userType="instructor">

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-[#03c95a]/10 border border-[#03c95a]/30 text-[#0d7a38] px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
        @endif

        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
            <h2 class="text-xl font-bold text-[#191919] mb-6">{{ __('instructor.profile.title') }}</h2>

            <form action="{{ route('instructor.profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $instructor->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.email') }}</label>
                        <input type="email" value="{{ $instructor->email }}" disabled class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm bg-gray-50 text-gray-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $instructor->phone) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('admin.instructors.designation') }}</label>
                        <input type="text" name="designation" value="{{ old('designation', $instructor->designation) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('admin.instructors.bio') }}</label>
                    <textarea name="bio" rows="4" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">{{ old('bio', $instructor->bio) }}</textarea>
                </div>

                <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">{{ __('app.common.save_changes') }}</button>
            </form>
        </div>

        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
            <h2 class="text-xl font-bold text-[#191919] mb-6">{{ __('instructor.profile.password') }}</h2>

            <form action="{{ route('instructor.profile.password') }}" method="POST" class="space-y-5 max-w-md">
                @csrf
                @method('PUT')

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.profile.current_password') }}</label>
                    <input type="password" name="current_password" required class="w-full border {{ $errors->has('current_password') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    @error('current_password')<p class="text-[11px] text-red-500">{{ __('instructor.profile.current_password_incorrect') }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.profile.new_password') }}</label>
                    <input type="password" name="password" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    <p class="text-[11px] text-gray-400">{{ __('instructor.profile.new_password_hint') }}</p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('auth.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                </div>

                <button type="submit" class="bg-[#08131e] hover:bg-[#1b2737] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">{{ __('app.common.save') }}</button>
            </form>
        </div>
    </div>

</x-layouts.dashboard>
