<x-layouts.auth title="Sign into Your Account - Dreams LMS">

    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-[#191919] tracking-tight">Sign into Your Account</h1>
            <p class="text-xs text-[#6d6d6d] mt-1.5">Welcome back! Please enter your details to access your courses.</p>
        </div>

        <form action="#" method="POST" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>Email</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative flex items-center">
                    <input type="email" required placeholder="Enter your email" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667] pr-10">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>

            <!-- Password Input -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919] flex items-center gap-0.5">
                    <span>Password</span>
                    <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative flex items-center">
                    <input type="password" required placeholder="••••••••" class="w-full bg-white border border-[#e7e7e7] rounded-md px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#ff4667] pr-10">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" checked class="w-4 h-4 rounded text-[#ff4667] focus:ring-[#ff4667] border-gray-300">
                    <span class="text-[#191919]">Remember Me</span>
                </label>
                <a href="{{ route('forgot-password') }}" class="font-medium text-[#ff4667] hover:underline">
                    Forgot Password?
                </a>
            </div>

            <!-- Submit Button (Exact Figma style) -->
            <button type="button" onclick="window.location.href='{{ route('student.dashboard') }}'" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold py-3 rounded-full flex items-center justify-center gap-2 transition-all shadow-md mt-2">
                <span>Login</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Social Sign In Container (Figma: 373:48175) -->
        <div class="space-y-4 pt-2">
            <!-- "Or" Divider -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-[#e7e7e7] w-full"></div>
                <span class="bg-white px-3 text-xs text-[#888888] relative">Or</span>
            </div>

            <!-- Social Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button class="bg-[#f4f6f9] hover:bg-gray-200 border border-transparent rounded-full py-2.5 px-4 text-xs font-medium text-[#5d5d5d] flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg>
                    <span>Google</span>
                </button>
                <button class="bg-[#f4f6f9] hover:bg-gray-200 border border-transparent rounded-full py-2.5 px-4 text-xs font-medium text-[#5d5d5d] flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span>Facebook</span>
                </button>
            </div>

            <!-- Sign Up Switcher -->
            <div class="text-center text-xs text-[#5d5d5d] pt-2">
                <span>Don't you have an account? </span>
                <a href="{{ route('register') }}" class="font-semibold text-[#ff4667] hover:underline">
                    Sign up
                </a>
            </div>
        </div>
    </div>

</x-layouts.auth>
