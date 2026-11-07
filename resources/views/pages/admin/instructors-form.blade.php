<x-layouts.dashboard :title="($instructor->exists ? __('admin.instructors.edit') : __('admin.instructors.create')).' - Dreams LMS'" active="instructors" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-2xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ $instructor->exists ? __('admin.instructors.edit') : __('admin.instructors.create') }}</h3>

        <form action="{{ $instructor->exists ? route('admin.instructors.update', $instructor) : route('admin.instructors.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($instructor->exists) @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $instructor->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                    @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $instructor->email) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                    @error('email')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.password') }} {{ $instructor->exists ? '('.__('instructor.profile.new_password_hint').')' : '*' }}</label>
                    <input type="password" name="password" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                    @error('password')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $instructor->phone) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('admin.instructors.designation') }}</label>
                    <input type="text" name="designation" value="{{ old('designation', $instructor->designation) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['active' => __('app.common.active'), 'inactive' => __('app.common.inactive')]"
                        :selected="old('status', $instructor->status ?? 'active')"
                        :searchable="false" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.instructors.bio') }}</label>
                <textarea name="bio" rows="4" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">{{ old('bio', $instructor->bio) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.instructors.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
