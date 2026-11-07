<x-layouts.dashboard :title="__('student.profile.title').' - Dreams LMS'" active="profile" userType="student">

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-[#03c95a]/10 border border-[#03c95a]/30 text-[#0d7a38] px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
        @endif

        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-8 shadow-xs space-y-8">
            <div>
                <h2 class="text-xl font-bold text-[#191919]">{{ __('student.profile.title') }}</h2>
                <p class="text-xs text-gray-400 mt-1">{{ $student->email }}</p>
            </div>

            <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('student.profile.first_name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full bg-[#f8fafb] border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm focus:outline-none focus:border-[#ff4667]">
                        @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.email') }}</label>
                        <input type="email" value="{{ $student->email }}" disabled class="w-full bg-gray-50 border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm text-gray-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full bg-[#f8fafb] border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm focus:outline-none focus:border-[#ff4667]">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.language') }}</label>
                        <x-language-switcher panel="student" class="border border-[#e7e7e7] rounded-lg bg-[#f8fafb] [&>button]:w-full [&>button]:px-3.5 [&>button]:py-3 [&>button]:text-sm" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('instructor.courses.fields.image') }}</label>
                    <input type="text" name="avatar" value="{{ old('avatar', $student->avatar) }}" placeholder="https://..." class="w-full bg-[#f8fafb] border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>

                <button type="submit" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-8 py-3 rounded-full transition-colors shadow-md">{{ __('app.common.save_changes') }}</button>
            </form>
        </div>

        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-8 shadow-xs">
            <h2 class="text-xl font-bold text-[#191919] mb-6">{{ __('student.profile.password') }}</h2>

            <form action="{{ route('student.profile.password') }}" method="POST" class="space-y-5 max-w-md">
                @csrf
                @method('PUT')

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('student.profile.current_password') }}</label>
                    <input type="password" name="current_password" required class="w-full border {{ $errors->has('current_password') ? 'border-red-400' : 'border-[#e7e7e7]' }} rounded-lg px-3.5 py-3 text-sm focus:outline-none focus:border-[#ff4667]">
                    @error('current_password')<p class="text-[11px] text-red-500">{{ __('student.profile.current_password_incorrect') }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('student.profile.new_password') }}</label>
                    <input type="password" name="password" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm focus:outline-none focus:border-[#ff4667]">
                    <p class="text-[11px] text-gray-400">{{ __('student.profile.new_password_hint') }}</p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('auth.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-3 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>

                <button type="submit" class="bg-[#08131e] hover:bg-[#1b2737] text-white text-sm font-semibold px-6 py-3 rounded-full transition-colors">{{ __('app.common.save') }}</button>
            </form>
        </div>
    </div>

</x-layouts.dashboard>
