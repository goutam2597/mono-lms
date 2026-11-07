<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lock Screen – Dreams LMS</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#191919] min-h-screen flex items-center justify-center p-4 sm:p-6" style="background: linear-gradient(144.39deg, rgb(255, 238, 240) 2.28%, rgb(248, 246, 255) 74.3%);">
    
    <!-- Lock Screen Card (Figma: 375:50392 & 375:50557) -->
    <div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-gray-100 flex flex-col items-center text-center space-y-8">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg bg-[#392c7d] flex items-center justify-center text-white shadow-sm">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
            </div>
            <span class="text-2xl font-bold tracking-tight text-[#191919]">Dreams<span class="text-[#ff4667]">LMS</span></span>
        </a>

        <!-- Welcome Back Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-extrabold text-[#191919]">Welcome Back</h1>
            <p class="text-sm text-[#6d6d6d]">Please enter your password to unlock your session</p>
        </div>

        <!-- User Info: Avatar + Name (Figma: 375:50832) -->
        <div class="flex flex-col items-center space-y-3">
            <div class="relative w-28 h-28 rounded-full p-1 border-2 border-[#ff4667] shadow-md">
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=240&auto=format&fit=crop&q=80" 
                    alt="Ronald Richard" class="w-full h-full object-cover rounded-full">
                <span class="absolute bottom-1 right-2 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></span>
            </div>
            <div class="font-bold text-lg text-[#191919]">Ronald Richard</div>
        </div>

        <!-- Unlock Form -->
        <form action="{{ route('student.dashboard') }}" method="GET" class="w-full space-y-5 text-left">
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-[#191919]">
                    Password <span class="text-[#e70d0d]">*</span>
                </label>
                <div class="relative rounded-md shadow-xs">
                    <input type="password" placeholder="Enter your password" required autofocus
                        class="w-full text-sm rounded-md border border-[#e7e7e7] px-3.5 py-2.5 pr-10 text-[#191919] focus:outline-none focus:border-[#ff4667] focus:ring-1 focus:ring-[#ff4667]">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button (Figma: 375:50575) -->
            <button type="submit" class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white py-3 px-6 rounded-full font-medium text-sm transition-all shadow-md flex items-center justify-center gap-2">
                <span>Sign In</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>

            <!-- Switch Account Link -->
            <p class="text-center text-xs text-[#6d6d6d] pt-2">
                Not you? <a href="{{ route('login') }}" class="font-semibold text-[#392c7d] hover:text-[#ff4667] hover:underline">Sign in with a different account</a>
            </p>
        </form>

    </div>
</body>
</html>
