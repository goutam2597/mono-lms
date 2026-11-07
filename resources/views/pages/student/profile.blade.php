<x-layouts.dashboard title="My Profile - Dreams LMS" active="profile" userType="student">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl p-8 shadow-xs space-y-8">
        <div>
            <h2 class="text-xl font-bold text-[#191919]">My Profile</h2>
            <p class="text-xs text-[#6d6d6d] mt-1">Manage your account information and preferences.</p>
        </div>

        <!-- Avatar Change Section -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-6 border-b border-gray-100">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <button class="bg-[#392c7d] hover:bg-[#ff4667] text-white text-xs font-semibold px-4 py-2 rounded-full transition-colors">
                        Upload New Photo
                    </button>
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-full transition-colors">
                        Delete
                    </button>
                </div>
                <p class="text-[11px] text-[#888888]">JPG, PNG or GIF up to 2MB. Recommended dimensions 300x300.</p>
            </div>
        </div>

        <!-- Profile Form -->
        <form class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">First Name <span class="text-red-500">*</span></label>
                    <input type="text" value="Eugene" class="w-full bg-white border border-[#e7e7e7] rounded-lg px-4 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" value="Andre" class="w-full bg-white border border-[#e7e7e7] rounded-lg px-4 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">User Name <span class="text-red-500">*</span></label>
                    <input type="text" value="eugeneandre" class="w-full bg-white border border-[#e7e7e7] rounded-lg px-4 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#191919]">Phone Number</label>
                    <input type="tel" value="+1 (555) 382-9012" class="w-full bg-white border border-[#e7e7e7] rounded-lg px-4 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">Email Address <span class="text-red-500">*</span></label>
                <input type="email" value="eugene@dreamslms.com" class="w-full bg-white border border-[#e7e7e7] rounded-lg px-4 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">Bio / About</label>
                <textarea rows="4" class="w-full bg-white border border-[#e7e7e7] rounded-lg px-4 py-2.5 text-xs text-[#191919] focus:outline-none focus:border-[#ff4667]">Passionate product enthusiast and UI/UX learner. Enrolled in comprehensive design and programming courses to prepare for an entry-level tech position.</textarea>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="button" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-bold px-6 py-2.5 rounded-full transition-colors shadow-sm">
                    Update Profile
                </button>
                <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold px-6 py-2.5 rounded-full transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
