@props([
    'name' => 'Eugene Andre',
    'role' => 'Student',
    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
    'enrolled' => '12',
    'certificates' => '05',
    'completed' => '10'
])

<div class="relative bg-gradient-to-r from-[#392c7d] to-[#1f1947] rounded-2xl p-6 sm:p-8 text-white mb-8 overflow-hidden shadow-md">
    <!-- Decorative background elements -->
    <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute right-32 top-0 w-32 h-32 rounded-full bg-[#738a6e]/15 pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="relative">
                <img src="{{ $avatar }}" alt="{{ $name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-4 border-white/20 shadow-lg">
                <span class="absolute bottom-1 right-1 w-4 h-4 bg-[#03c95a] border-2 border-[#392c7d] rounded-full"></span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $name }}</h1>
                    <span class="bg-[#344c3d] text-white text-[11px] font-semibold px-2.5 py-0.5 rounded-full">{{ $role }}</span>
                </div>
                <p class="text-gray-300 text-sm mt-1">student@dreamslms.com</p>

                <!-- Quick Stats -->
                <div class="flex items-center gap-4 sm:gap-6 mt-3 text-xs sm:text-sm text-gray-300">
                    <div><strong class="text-white font-bold">{{ $enrolled }}</strong> Enrolled Courses</div>
                    <div>•</div>
                    <div><strong class="text-white font-bold">{{ $certificates }}</strong> Certificates</div>
                    <div>•</div>
                    <div><strong class="text-white font-bold">{{ $completed }}</strong> Completed</div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto flex-wrap">
            <a href="{{ route('student.profile') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit Profile</span>
            </a>
            <a href="{{ route('courses.index') }}" class="bg-[#344c3d] hover:bg-[#25372c] text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors w-full sm:w-auto shadow-sm">
                <span>Browse Courses</span>
            </a>
            <button type="button" id="student-theme-toggle" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-semibold px-3 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors cursor-pointer w-full sm:w-auto" title="Toggle Dark/Light Mode">
                <svg class="w-4 h-4 hidden dark:inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                <svg class="w-4 h-4 inline-block dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                <span class="inline">Theme</span>
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        var btn = document.getElementById('student-theme-toggle');
        if (!btn) return;
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        btn.addEventListener('click', function () {
            var isDark = document.documentElement.classList.toggle('dark');
            var nextTheme = isDark ? 'dark' : 'light';
            try { localStorage.setItem('mono_theme_student', nextTheme); } catch (e) {}
            fetch('{{ route('theme.toggle', 'student') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: nextTheme })
            }).catch(function () {});
        });
    })();
</script>
