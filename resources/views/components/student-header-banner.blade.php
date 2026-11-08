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

        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('student.profile') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors w-full md:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit Profile</span>
            </a>
            <a href="{{ route('courses.index') }}" class="bg-[#344c3d] hover:bg-[#25372c] text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors w-full md:w-auto shadow-sm">
                <span>Browse Courses</span>
            </a>
        </div>
    </div>
</div>
