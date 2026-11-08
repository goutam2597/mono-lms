<x-layouts.dashboard :title="__('admin.students.title').' - Mono LMS'" active="students" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-2xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ __('app.common.edit') }} — {{ $student->name }}</h3>

        <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                    @error('email')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['active' => __('app.common.active'), 'inactive' => __('app.common.inactive')]"
                        :selected="old('status', $student->status)"
                        :searchable="false" />
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#344c3d] hover:bg-[#283a2f] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.students.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
