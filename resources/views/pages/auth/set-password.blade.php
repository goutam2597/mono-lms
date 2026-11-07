<x-layouts.auth title="Set Password – Dreams LMS">
    <div class="space-y-6">
        <!-- Header -->
        <div class="space-y-2">
            <h1 class="text-3xl font-extrabold text-[#191919]">Set Password</h1>
            <p class="text-sm text-[#5d5d5d]">
                Your new password must be different from previous password
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="GET" class="space-y-5">
            <!-- New Password -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-[#191919]">
                    Password <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative rounded-md shadow-xs">
                    <input type="password" placeholder="Enter new password" required
                        class="w-full text-sm rounded-md border border-[#e7e7e7] px-3.5 py-2.5 pr-10 text-[#191919] focus:outline-none focus:border-[#ff4667] focus:ring-1 focus:ring-[#ff4667]">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>

                <!-- Password Strength 4-segment Bar (Figma: 375:50313) -->
                <div class="flex items-center gap-2 pt-1.5">
                    <div class="flex-1 h-1.5 rounded-full bg-emerald-500"></div>
                    <div class="flex-1 h-1.5 rounded-full bg-emerald-500"></div>
                    <div class="flex-1 h-1.5 rounded-full bg-emerald-500"></div>
                    <div class="flex-1 h-1.5 rounded-full bg-[#f4f6f9]"></div>
                </div>
                <span class="text-[11px] text-[#21b477] font-semibold">Strong password</span>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-[#191919]">
                    Confirm Password <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative rounded-md shadow-xs">
                    <input type="password" placeholder="Confirm your new password" required
                        class="w-full text-sm rounded-md border border-[#e7e7e7] px-3.5 py-2.5 pr-10 text-[#191919] focus:outline-none focus:border-[#ff4667] focus:ring-1 focus:ring-[#ff4667]">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button (Figma: 375:50319) -->
            <button type="submit" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white py-3 px-6 rounded-full font-medium text-sm transition-all shadow-md flex items-center justify-center gap-2 mt-4">
                <span>Reset Password</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>
    </div>
</x-layouts.auth>
