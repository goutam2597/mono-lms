<x-layouts.auth title="Create an Account - Dreams LMS">

    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-[#191919] tracking-tight">Create an Account</h1>
            <p class="text-xs text-[#6d6d6d] mt-1.5">Join thousands of students and mentors today.</p>
        </div>

        <form action="#" method="POST" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>Full Name</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input type="text" required placeholder="Enter your full name" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667]">
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>Email Address</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input type="email" required placeholder="Enter your email" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667]">
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>Password</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <input type="password" required placeholder="Create a strong password" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667]">
            </div>

            <!-- Terms checkbox -->
            <div class="flex items-start gap-2 text-xs pt-1">
                <input type="checkbox" required checked class="w-4 h-4 rounded text-[#ff4667] focus:ring-[#ff4667] border-gray-300 mt-0.5">
                <span class="text-[#6d6d6d]">
                    I agree to the <a href="#" class="text-[#ff4667] underline">Terms & Conditions</a> and <a href="#" class="text-[#ff4667] underline">Privacy Policy</a>.
                </span>
            </div>

            <!-- Submit Button -->
            <button type="button" onclick="window.location.href='{{ route('otp') }}'" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold py-3 rounded-full flex items-center justify-center gap-2 transition-all shadow-md mt-2">
                <span>Create Account</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="space-y-4 pt-2">
            <div class="relative flex items-center justify-center">
                <div class="border-t border-[#e7e7e7] w-full"></div>
                <span class="bg-white px-3 text-xs text-[#888888] relative">Or</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button class="bg-[#f4f6f9] hover:bg-gray-200 rounded-full py-2.5 px-4 text-xs font-medium text-[#5d5d5d] flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg>
                    <span>Google</span>
                </button>
                <button class="bg-[#f4f6f9] hover:bg-gray-200 rounded-full py-2.5 px-4 text-xs font-medium text-[#5d5d5d] flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span>Facebook</span>
                </button>
            </div>

            <div class="text-center text-xs text-[#5d5d5d] pt-2">
                <span>Already have an account? </span>
                <a href="{{ route('login') }}" class="font-semibold text-[#ff4667] hover:underline">
                    Sign in
                </a>
            </div>
        </div>
    </div>

</x-layouts.auth>
