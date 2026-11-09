<x-layouts.dashboard :title="__('student.profile.title').' - Mono LMS'" active="profile" userType="student">

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-[8px] text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-[8px] text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Profile Information Card -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] shadow-xs overflow-hidden">
            <!-- Boxy Card Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">{{ __('student.profile.title') }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('student.profile.subtitle') }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-[4px] bg-slate-100 text-slate-600 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Verified Account
                </span>
            </div>

            @php
                $avatarFallback = 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=344C3D&color=FFFFFF&size=200&bold=true';
                $previewUrl = $student->avatarUrl() ?: $avatarFallback;
            @endphp

            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar upload section -->
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100">
                    <div class="relative shrink-0 group">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-[8px] overflow-hidden border border-slate-200 ring-2 ring-slate-100 shadow-xs bg-slate-100">
                            <img id="avatar-preview"
                                 src="{{ $previewUrl }}"
                                 alt="{{ $student->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='{{ $avatarFallback }}';">
                        </div>
                        <label class="absolute inset-0 rounded-[8px] bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0013.07 7H15a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="file" name="avatar" id="avatar-input" accept="image/png,image/jpeg,image/webp" class="hidden">
                        </label>
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-800">{{ __('student.profile.avatar_hint') }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Recommended square format &middot; JPG, PNG or WebP &middot; Max 2 MB</p>
                        <div class="flex items-center gap-2.5 mt-3 justify-center sm:justify-start">
                            <label for="avatar-input" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-[6px] px-3.5 py-1.5 cursor-pointer hover:bg-slate-50 transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span>{{ __('student.profile.upload_avatar') }}</span>
                            </label>
                            @if ($student->avatar)
                                <button type="submit" form="remove-avatar-form" class="text-xs font-semibold text-rose-600 hover:text-rose-700 px-2.5 py-1.5 transition-colors cursor-pointer">
                                    {{ __('student.profile.remove_avatar') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fields Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">{{ __('student.profile.first_name') }}</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $student->name) }}"
                               required
                               class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[var(--theme-primary,#344c3d)] rounded-[6px] px-3 py-2 text-xs text-slate-900 focus:outline-none transition-colors">
                        @error('name')<p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">{{ __('app.common.email') }}</label>
                        <input type="email"
                               value="{{ $student->email }}"
                               disabled
                               class="w-full bg-slate-100 border border-slate-200 rounded-[6px] px-3 py-2 text-xs text-slate-500 cursor-not-allowed">
                    </div>

                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-xs font-semibold text-slate-700">{{ __('app.common.phone') }}</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $student->phone) }}"
                               placeholder="+1 (555) 000-0000"
                               class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[var(--theme-primary,#344c3d)] rounded-[6px] px-3 py-2 text-xs text-slate-900 focus:outline-none transition-colors">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-6 py-2.5 rounded-[6px] transition-colors shadow-xs cursor-pointer">
                        {{ __('app.common.save_changes') }}
                    </button>
                </div>
            </form>

            @if ($student->avatar)
                <form id="remove-avatar-form" action="{{ route('student.profile.update') }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="remove_avatar" value="1">
                    <input type="hidden" name="name" value="{{ $student->name }}">
                </form>
            @endif
        </div>

        <!-- Password Card -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">{{ __('student.profile.password') }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('student.profile.new_password_hint') }}</p>
            </div>

            <form action="{{ route('student.profile.password') }}" method="POST" class="p-6 space-y-4 max-w-lg">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">{{ __('student.profile.current_password') }}</label>
                    <input type="password"
                           name="current_password"
                           required
                           class="w-full bg-slate-50 border {{ $errors->has('current_password') ? 'border-rose-400' : 'border-slate-200' }} focus:bg-white focus:border-[var(--theme-primary,#344c3d)] rounded-[6px] px-3 py-2 text-xs text-slate-900 focus:outline-none transition-colors">
                    @error('current_password')<p class="text-[11px] text-rose-600 font-medium">{{ __('student.profile.current_password_incorrect') }}</p>@enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">{{ __('student.profile.new_password') }}</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[var(--theme-primary,#344c3d)] rounded-[6px] px-3 py-2 text-xs text-slate-900 focus:outline-none transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">{{ __('auth.confirm_password') }}</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[var(--theme-primary,#344c3d)] rounded-[6px] px-3 py-2 text-xs text-slate-900 focus:outline-none transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-5 py-2.5 rounded-[6px] transition-colors shadow-xs cursor-pointer">
                        {{ __('app.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var input = document.getElementById('avatar-input');
            var preview = document.getElementById('avatar-preview');
            if (!input || !preview) return;
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image must be under 2 MB.');
                    input.value = '';
                    return;
                }
                preview.src = URL.createObjectURL(file);
            });
        })();
    </script>

</x-layouts.dashboard>
