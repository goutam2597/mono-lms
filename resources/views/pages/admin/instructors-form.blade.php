<x-layouts.dashboard :title="($instructor->exists ? __('admin.instructors.edit') : __('admin.instructors.create')).' - Mono LMS'" active="instructors" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-2xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $instructor->exists ? __('admin.instructors.edit') : __('admin.instructors.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Manage instructor profile details, teaching credentials, and assigned bio.</p>
        </div>

        <form action="{{ $instructor->exists ? route('admin.instructors.update', $instructor) : route('admin.instructors.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($instructor->exists) @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $instructor->name) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                    @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $instructor->email) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                    @error('email')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.password') }} {{ $instructor->exists ? '('.__('instructor.profile.new_password_hint').')' : '*' }}</label>
                    <input type="password" name="password" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                    @error('password')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $instructor->phone) }}" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.instructors.designation') }}</label>
                    <input type="text" name="designation" value="{{ old('designation', $instructor->designation) }}" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['active' => __('app.common.active'), 'inactive' => __('app.common.inactive'), 'pending_verification' => 'Pending Verification']"
                        :selected="old('status', $instructor->status ?? 'active')"
                        :searchable="false" />
                </div>
            </div>

            <!-- Avatar -->
            <x-media-selector
                name="avatar"
                :value="old('avatar', $instructor->avatar)"
                label="Avatar Image"
                shape="circle"
                :defaultUrl="'https://ui-avatars.com/api/?name='.urlencode($instructor->name ?? 'Instructor').'&background=344c3d&color=fff'"
            />

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.instructors.bio') }}</label>
                <textarea name="bio" rows="4" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">{{ old('bio', $instructor->bio) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.instructors.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>