<x-layouts.dashboard title="All Users - Mono LMS" active="users" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">User Directory</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $stats['total'] }} Total Accounts
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Unified directory of students, instructors, and system administrators.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-xs">
                    <span>+ Add Admin</span>
                </a>
                <a href="{{ route('admin.instructors.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#283a2f] text-white transition-colors shadow-xs">
                    <span>+ Add Instructor</span>
                </a>
            </div>
        </div>

        <!-- 3 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs hover:border-slate-300 transition-colors">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Learners (Students)</span>
                <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['students'] }}</p>
                <span class="text-[11px] text-slate-400">Enrolled platform students</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs hover:border-slate-300 transition-colors">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Instructors & Mentors</span>
                <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['instructors'] }}</p>
                <span class="text-[11px] text-slate-400">Active teaching faculty</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs hover:border-slate-300 transition-colors">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Administrators</span>
                <p class="text-2xl font-extrabold text-[#344c3d] mt-1">{{ $stats['admins'] }}</p>
                <span class="text-[11px] text-slate-400">Super admins & managers</span>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[260px]">
                <div class="relative flex-1 max-w-sm">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-9 pr-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div class="flex items-center gap-1.5 text-xs font-semibold">
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-xl {{ !request('role') ? 'bg-[#344c3d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">All</a>
                    <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="px-3 py-1.5 rounded-xl {{ request('role') === 'student' ? 'bg-[#344c3d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">Students</a>
                    <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}" class="px-3 py-1.5 rounded-xl {{ request('role') === 'instructor' ? 'bg-[#344c3d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">Instructors</a>
                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-3 py-1.5 rounded-xl {{ request('role') === 'admin' ? 'bg-[#344c3d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">Admins</a>
                </div>
            </div>

            <button type="submit" class="text-xs font-semibold px-4 py-2 bg-[#344c3d] text-white rounded-xl hover:bg-[#283a2f] shadow-xs">
                Search
            </button>
        </form>

        <!-- Users Table -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3.5 px-4">User</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Registered</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        @if(!empty($user['avatar']))
                                            <img src="{{ $user['avatar'] }}" alt="" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                                        @else
                                            <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($user['name'], 0, 1)) }}
                                            </span>
                                        @endif
                                        <span class="font-bold text-slate-900">{{ $user['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                    {{ $user['email'] }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $roleClass = match($user['role']) {
                                            'Super Admin', 'Admin' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'Instructor' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            default => 'bg-blue-50 text-blue-800 border-blue-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $roleClass }}">
                                        {{ $user['role'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $user['status'] === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($user['status'] ?? 'active') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap text-[11px]">
                                    {{ $user['created_at'] ? $user['created_at']->format('M d, Y') : '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ $user['edit_url'] }}" class="text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
