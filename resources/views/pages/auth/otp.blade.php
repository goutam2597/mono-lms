<x-layouts.auth title="OTP Verification – Dreams LMS">
    <div class="space-y-6">
        <!-- Header -->
        <div class="space-y-2">
            <h1 class="text-3xl font-extrabold text-[#191919]">Email OTP</h1>
            <p class="text-sm text-[#5d5d5d]">
                OTP sent to your Email Address ending <span class="font-semibold text-[#191919]">******doe@example.com</span>
            </p>
        </div>

        <!-- OTP Form -->
        <form action="{{ route('set-password') }}" method="GET" class="space-y-6">
            <div class="space-y-4">
                <!-- 4 Digit Inputs -->
                <div class="flex items-center justify-between gap-3 sm:gap-4">
                    <input type="text" maxlength="1" value="8" autofocus
                        class="w-16 h-18 text-center text-2xl font-bold rounded-lg border-2 border-[#ff4667] text-[#191919] focus:outline-none focus:ring-2 focus:ring-[#ff4667]/20 shadow-sm">
                    <input type="text" maxlength="1" placeholder="•"
                        class="w-16 h-18 text-center text-2xl font-bold rounded-lg border border-[#e7e7e7] text-[#191919] focus:outline-none focus:border-[#ff4667] focus:ring-2 focus:ring-[#ff4667]/20 shadow-sm">
                    <input type="text" maxlength="1" placeholder="•"
                        class="w-16 h-18 text-center text-2xl font-bold rounded-lg border border-[#e7e7e7] text-[#191919] focus:outline-none focus:border-[#ff4667] focus:ring-2 focus:ring-[#ff4667]/20 shadow-sm">
                    <input type="text" maxlength="1" placeholder="•"
                        class="w-16 h-18 text-center text-2xl font-bold rounded-lg border border-[#e7e7e7] text-[#191919] focus:outline-none focus:border-[#ff4667] focus:ring-2 focus:ring-[#ff4667]/20 shadow-sm">
                </div>

                <!-- Timer Badge (Figma: 375:50121) -->
                <div class="flex justify-center">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#f6cece] text-[#e70d0d] rounded-full text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>09:59 s</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button (Figma: 375:49807) -->
            <button type="submit" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white py-3 px-6 rounded-full font-medium text-sm transition-all shadow-md flex items-center justify-center gap-2">
                <span>Verify & Proceed</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>

            <!-- Resend Link -->
            <p class="text-center text-sm text-[#5d5d5d]">
                Didn't get the OTP? 
                <a href="#" class="font-medium text-[#ff4667] hover:underline">Resend OTP</a>
            </p>
        </form>
    </div>
</x-layouts.auth>
