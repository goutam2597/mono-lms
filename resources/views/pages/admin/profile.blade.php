<x-layouts.dashboard title="My Profile - Mono LMS" active="profile" userType="admin">
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-neutral-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-neutral-200 font-medium">My Profile</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Account Settings</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-neutral-400 mt-0.5">Manage your personal information, profile photo, and password</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="action-btn action-btn-ghost sm">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Identity Banner Card -->
        <div class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[6px] p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="relative shrink-0">
                    @php $profileAvatar = $admin->avatarUrl(); @endphp
                    @if ($profileAvatar)
                        <img src="{{ $profileAvatar }}" alt="" class="w-16 h-16 rounded-[8px] object-cover ring-1 ring-slate-200 dark:ring-neutral-800" onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.style.display='flex'; }">
                        <div class="w-16 h-16 rounded-[8px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xl items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs hidden">
                            {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-[8px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xl flex items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs">
                            {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $admin->name }}</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-bold uppercase tracking-wider bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] dark:text-[#86efac]">
                            {{ $admin->isSuperAdmin() ? 'Super Admin' : ($admin->role?->name ?? 'Administrator') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">
                            {{ ucfirst($admin->status ?? 'active') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-neutral-400 mt-1 truncate">{{ $admin->email }}</p>
                    <p class="text-[11px] text-slate-400 dark:text-neutral-500 mt-0.5">Account Member since {{ $admin->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('home') }}" target="_blank" class="action-btn action-btn-ghost sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    <span>View Site</span>
                </a>
            </div>
        </div>

        <!-- Forms Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left: Personal Information (7 cols) -->
            <div class="lg:col-span-7 bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[6px] p-5 sm:p-6 shadow-xs space-y-5">
                <div class="border-b border-gray-100 dark:border-[#1f1f1f] pb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">Personal Information</h3>
                    <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">Update your identity and contact details</p>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Picker -->
                    <div>
                        <x-media-selector
                            name="avatar"
                            :value="old('avatar', $admin->avatar)"
                            label="Profile Photo"
                            shape="circle"
                            :defaultUrl="'https://ui-avatars.com/api/?name='.urlencode($admin->name ?? 'Admin').'&background=344c3d&color=fff'"
                        />
                        <p class="text-[11px] text-slate-400 dark:text-neutral-500 mt-1">Select an image from the Media Library or upload a new photo.</p>
                    </div>

                    <!-- Name -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-[#262626] rounded-[4px] px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                        @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-[#262626] rounded-[4px] px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                            @error('email')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" placeholder="+1 (555) 000-0000" class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-[#262626] rounded-[4px] px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                            @error('phone')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Role & Language -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">System Role</label>
                            <input type="text" value="{{ $admin->isSuperAdmin() ? 'Super Admin (All Permissions)' : ($admin->role?->name ?? 'Administrator') }}" disabled class="w-full border border-slate-200 dark:border-[#262626] rounded-[4px] px-3 py-2 text-xs bg-slate-50 dark:bg-[#161616] text-slate-500 dark:text-neutral-400 cursor-not-allowed">
                            <p class="text-[10px] text-slate-400 dark:text-neutral-500">Assigned by system administrator.</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">Preferred Language</label>
                            @php
                                $locales = \App\Models\Language::active()->pluck('name', 'code')->toArray();
                            @endphp
                            <x-select name="locale"
                                :options="$locales"
                                :selected="old('locale', $admin->locale ?? app()->getLocale())"
                                placeholder="Select Language" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary">
                            Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Change Password (5 cols) -->
            <div class="lg:col-span-5 bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[6px] p-5 sm:p-6 shadow-xs space-y-5">
                <div class="border-b border-gray-100 dark:border-[#1f1f1f] pb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">Security & Password</h3>
                    <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">Ensure your account is using a secure password</p>
                </div>

                <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">Current Password <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full bg-white dark:bg-[#121212] border {{ $errors->has('current_password') ? 'border-red-400' : 'border-slate-200 dark:border-[#262626]' }} rounded-[4px] px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                        @error('current_password')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- New Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">New Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full bg-white dark:bg-[#121212] border {{ $errors->has('password') ? 'border-red-400' : 'border-slate-200 dark:border-[#262626]' }} rounded-[4px] px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                        @error('password')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700 dark:text-neutral-300 block">Confirm New Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat new password" class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-[#262626] rounded-[4px] px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="action-btn action-btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
