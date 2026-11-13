<x-layouts.dashboard :title="__('admin.students.title').' - Mono LMS'" active="students" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-2xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ __('app.common.edit') }} — {{ $student->name }}</h3>
            <p class="text-xs text-slate-500 mt-1">Update student account details, contact phone, and enrollment status.</p>
        </div>

        <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                    @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                    @error('email')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['active' => __('app.common.active'), 'inactive' => __('app.common.inactive')]"
                        :selected="old('status', $student->status)"
                        :searchable="false" />
                </div>
            </div>

            <!-- Avatar -->
            <x-media-selector
                name="avatar"
                :value="old('avatar', $student->avatar)"
                label="Avatar Image"
                shape="circle"
                :defaultUrl="'https://ui-avatars.com/api/?name='.urlencode($student->name ?? 'Student').'&background=344c3d&color=fff'"
            />

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.students.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>