<x-layouts.auth title="Forgot Password - Dreams LMS">

    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-[#191919] tracking-tight">Forgot Password</h1>
            <p class="text-xs text-[#6d6d6d] mt-1.5">Enter your email address and we will send you a 4-digit verification code to reset your password.</p>
        </div>

        <form action="#" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>Email Address</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative flex items-center">
                    <input type="email" required placeholder="Enter registered email" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667] pr-10">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="button" onclick="window.location.href='{{ route('otp') }}'" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold py-3 rounded-full flex items-center justify-center gap-2 transition-all shadow-md mt-4">
                <span>Send Code</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="text-center text-xs text-[#5d5d5d] pt-4">
            <span>Remember your password? </span>
            <a href="{{ route('login') }}" class="font-semibold text-[#ff4667] hover:underline">
                Back to Sign in
            </a>
        </div>
    </div>

</x-layouts.auth>
