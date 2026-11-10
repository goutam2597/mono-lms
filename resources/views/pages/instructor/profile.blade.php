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

        <!-- Teacher Verification Card -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-[#191919]">Teacher Verification &amp; Credentials</h2>
                    <p class="text-xs text-gray-500 mt-1">National ID and teaching certification required for public instructor listing and course creation.</p>
                </div>
                <div>
                    @if ($instructor->isVerified())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Verified Teacher
                        </span>
                    @elseif ($instructor->isPendingVerification())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Pending Admin Review
                        </span>
                    @elseif ($instructor->isVerificationRejected())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                            Verification Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            Unverified
                        </span>
                    @endif
                </div>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="text-xs text-gray-600 space-y-1">
                    <p>Teaching Certificate: <strong class="{{ $instructor->certification_file ? 'text-emerald-700' : 'text-gray-400' }}">{{ $instructor->certification_file ? 'Attached' : 'Not submitted' }}</strong></p>
                    <p>National ID / Passport: <strong class="{{ $instructor->national_id_file ? 'text-emerald-700' : 'text-gray-400' }}">{{ $instructor->national_id_file ? 'Attached' : 'Not submitted' }}</strong></p>
                </div>
                <a href="{{ route('instructor.verification.show') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-semibold transition-colors shadow-2xs">
                    <span>{{ $instructor->isVerified() ? 'View Verification Details' : 'Manage / Submit Documents' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
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
