@props(['title' => 'Dashboard - Dreams LMS', 'active' => 'dashboard', 'userType' => 'student'])

<x-layouts.app :title="$title" :active="$userType === 'student' ? 'student' : ($userType === 'admin' ? 'admin' : 'instructor')">
    <div class="bg-[#f4f6f9] py-8 min-h-[calc(100vh-200px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <!-- Profile Banner -->
            @if($userType === 'student')
                @php $student = auth('student')->user(); @endphp
                <x-student-header-banner
                    :name="$student->name"
                    :avatar="$student->avatar"
                    :enrolled="str_pad((string) $student->enrollments()->count(), 2, '0', STR_PAD_LEFT)"
                    :completed="str_pad((string) $student->enrollments()->where('status', 'completed')->count(), 2, '0', STR_PAD_LEFT)"
                />
            @elseif($userType === 'admin')
                @php $admin = auth('admin')->user(); @endphp
                <div class="relative bg-gradient-to-r from-[#08131e] to-[#1f1947] rounded-2xl p-6 sm:p-8 text-white mb-8 overflow-hidden shadow-md">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <span class="w-20 h-20 rounded-full bg-[#392c7d] border-4 border-white/20 shadow-lg flex items-center justify-center text-3xl font-bold">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $admin->name }}</h1>
                                    <span class="bg-[#ff4667] text-white text-[11px] font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ $admin->isSuperAdmin() ? __('admin.admins.super_admin') : ($admin->role?->name ?? __('app.nav.admin_panel')) }}
                                    </span>
                                </div>
                                <p class="text-gray-300 text-sm mt-1">{{ __('admin.dashboard.subtitle') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-language-switcher panel="admin" class="[&>button]:text-white [&>button]:border [&>button]:border-white/20 [&>button]:rounded-full [&>button]:px-4 [&>button]:py-2" />
                        </div>
                    </div>
                </div>
            @else
                @php $instructor = auth('instructor')->user(); @endphp
                <div class="relative bg-gradient-to-r from-[#08131e] to-[#1f1947] rounded-2xl p-6 sm:p-8 text-white mb-8 overflow-hidden shadow-md">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            @if($instructor->avatar)
                                <img src="{{ $instructor->avatar }}" alt="{{ $instructor->name }}" class="w-20 h-20 rounded-full object-cover border-4 border-white/20 shadow-lg">
                            @else
                                <span class="w-20 h-20 rounded-full bg-[#392c7d] border-4 border-white/20 shadow-lg flex items-center justify-center text-3xl font-bold">
                                    {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                </span>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $instructor->name }}</h1>
                                    <span class="bg-[#ff4667] text-white text-[11px] font-semibold px-2.5 py-0.5 rounded-full">{{ __('app.common.instructor') }}</span>
                                </div>
                                <p class="text-gray-300 text-sm mt-1">{{ $instructor->designation ?? __('instructor.panel') }}</p>
                                <div class="flex items-center gap-4 mt-3 text-xs sm:text-sm text-gray-300">
                                    <div><strong class="text-white font-bold">{{ \App\Models\Enrollment::whereIn('course_id', $instructor->courses()->pluck('id'))->count() }}</strong> {{ __('app.common.students') }}</div>
                                    <div>•</div>
                                    <div><strong class="text-white font-bold">{{ $instructor->courses()->count() }}</strong> {{ __('admin.dashboard.total_courses') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-language-switcher panel="instructor" class="[&>button]:text-white [&>button]:border [&>button]:border-white/20 [&>button]:rounded-full [&>button]:px-4 [&>button]:py-2" />
                            <a href="{{ route('instructor.courses.create') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-full transition-colors shadow-sm">
                                + {{ __('instructor.courses.create') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Layout Grid: Sidebar + Content -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-3">
                    @if($userType === 'student')
                        <x-student-sidebar :active="$active" />
                    @elseif($userType === 'admin')
                        <x-admin-sidebar :active="$active" />
                    @else
                        <x-instructor-sidebar :active="$active" />
                    @endif
                </div>

                <!-- Main Content Slot -->
                <div class="lg:col-span-9">
                    <!-- Flash messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-[#03c95a]/10 border border-[#03c95a]/30 text-[#0d7a38] px-4 py-3 rounded-xl text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
