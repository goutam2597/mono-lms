<x-layouts.dashboard :title="__('instructor.profile.title').' - Mono LMS'" active="profile" userType="instructor">

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Profile Information Card -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-[#191919]">{{ __('instructor.profile.title') }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Manage your public instructor profile details, avatar, and contact info.</p>
                </div>
                <div>
                    @if ($instructor->isVerified())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Verified Instructor
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            {{ $instructor->verification_status === 'pending' ? 'Verification Pending' : 'Unverified' }}
                        </span>
                    @endif
                </div>
            </div>

            @php
                $avatarFallback = 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=344C3D&color=FFFFFF&size=200&bold=true';
                $previewUrl = $instructor->avatarUrl() ?: $avatarFallback;
            @endphp

            <form action="{{ route('instructor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar Upload Section -->
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100">
                    <div class="relative shrink-0 group">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border border-slate-200 ring-2 ring-slate-100 shadow-xs bg-slate-100">
                            <img id="avatar-preview"
                                 src="{{ $previewUrl }}"
                                 alt="{{ $instructor->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='{{ $avatarFallback }}';">
                        </div>
                        <label for="avatar-input" class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0013.07 7H15a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                        <input type="file" name="avatar" id="avatar-input" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="hidden">
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-800">Profile Photo</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Upload a clean headshot. Recommended square format (JPG, PNG, or WebP up to 4 MB).</p>
                        <div class="flex items-center gap-2.5 mt-3 justify-center sm:justify-start">
                            <label for="avatar-input" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg px-3.5 py-1.5 cursor-pointer hover:bg-slate-50 transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span>Choose New Photo</span>
                            </label>
                            @if ($instructor->avatar)
                                <button type="submit" form="remove-avatar-form" class="text-xs font-semibold text-red-600 hover:text-red-700 px-2.5 py-1.5 transition-colors cursor-pointer">
                                    Remove Photo
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Text Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }} *</label>
                        <input type="text" name="name" value="{{ old('name', $instructor->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('name')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.email') }} *</label>
                        <input type="email" name="email" value="{{ old('email', $instructor->email) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('email')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $instructor->phone) }}" placeholder="+1 234 567 890" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('phone')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#191919]">{{ __('admin.instructors.designation') }}</label>
                        <input type="text" name="designation" value="{{ old('designation', $instructor->designation) }}" placeholder="e.g. Senior PHP Architect &amp; Instructor" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                        @error('designation')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('admin.instructors.bio') }}</label>
                    <textarea name="bio" rows="4" placeholder="Tell students about your teaching experience, industry background, and topics of expertise..." class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">{{ old('bio', $instructor->bio) }}</textarea>
                    @error('bio')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-2xs cursor-pointer">
                        {{ __('app.common.save_changes') }}
                    </button>
                </div>
            </form>

            <form id="remove-avatar-form" action="{{ route('instructor.profile.update') }}" method="POST" class="hidden">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $instructor->name }}">
                <input type="hidden" name="email" value="{{ $instructor->email }}">
                <input type="hidden" name="remove_avatar" value="1">
            </form>
        </div>

        <!-- Instructor Verification Card -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-[#191919]">Instructor Verification &amp; Credentials</h2>
                    <p class="text-xs text-gray-500 mt-1">National ID and instructor certification required for public instructor listing and course creation.</p>
                </div>
                <div>
                    @if ($instructor->isVerified())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Verified Instructor
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
                    <p>Instructor Certificate: <strong class="{{ $instructor->certification_file ? 'text-emerald-700' : 'text-gray-400' }}">{{ $instructor->certification_file ? 'Attached' : 'Not submitted' }}</strong></p>
                    <p>National ID / Passport: <strong class="{{ $instructor->national_id_file ? 'text-emerald-700' : 'text-gray-400' }}">{{ $instructor->national_id_file ? 'Attached' : 'Not submitted' }}</strong></p>
                </div>
                <a href="{{ route('instructor.verification.show') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-semibold transition-colors shadow-2xs">
                    <span>{{ $instructor->isVerified() ? 'View Verification Details' : 'Manage / Submit Documents' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Password Update Card -->
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

                <button type="submit" class="bg-[#08131e] hover:bg-[#1b2737] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors cursor-pointer">{{ __('app.common.save') }}</button>
            </form>
        </div>
    </div>

    <!-- Live Avatar Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var avatarInput = document.getElementById('avatar-input');
            var avatarPreview = document.getElementById('avatar-preview');

            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function () {
                    var file = this.files && this.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            avatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>

</x-layouts.dashboard>
