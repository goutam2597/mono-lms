<footer class="bg-[#08131e] text-white pt-16 pb-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-12">
            <!-- Brand Column -->
            <div class="lg:col-span-4 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-[#392c7d] flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white">Dreams<span class="text-[#ff4667]">LMS</span></span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    Platform designed to help organizations, educators, and learners manage, deliver, and track learning and training activities.
                </p>
                <!-- App Badges -->
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 bg-[#142333] border border-gray-700 hover:border-gray-500 rounded-lg text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.89c.62-.75 1.04-1.8 1.04-2.89 0-.15-.01-.29-.04-.44-.99.04-2.19.66-2.88 1.47-.56.64-.99 1.68-.99 2.74 0 .15.02.29.04.41 1.09.08 2.21-.54 2.83-1.29z"/></svg>
                        <div class="text-left">
                            <div class="text-[9px] text-gray-400 uppercase leading-none">Download on</div>
                            <div class="text-xs font-semibold leading-tight">App Store</div>
                        </div>
                    </a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 bg-[#142333] border border-gray-700 hover:border-gray-500 rounded-lg text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 20.5v-17c0-.83.67-1.5 1.5-1.5.31 0 .61.1.86.29l13.5 8.5c.69.44.89 1.36.45 2.05-.12.19-.28.35-.45.45l-13.5 8.5c-.25.19-.55.29-.86.29-.83 0-1.5-.67-1.5-1.58z"/></svg>
                        <div class="text-left">
                            <div class="text-[9px] text-gray-400 uppercase leading-none">Get it on</div>
                            <div class="text-xs font-semibold leading-tight">Google Play</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Support Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">Support</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Education</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Manage Deliveries</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Orders</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Payments</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Blogs</a></li>
                </ul>
            </div>

            <!-- Pages Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">Pages</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="{{ route('courses.grid') }}" class="hover:text-[#ff4667] transition-colors">Categories</a></li>
                    <li><a href="{{ route('courses.grid') }}" class="hover:text-[#ff4667] transition-colors">Courses</a></li>
                    <li><a href="{{ route('instructors.grid') }}" class="hover:text-[#ff4667] transition-colors">Instructors</a></li>
                    <li><a href="{{ route('screens') }}" class="hover:text-[#ff4667] transition-colors">All Screens</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Useful Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">Useful Links</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Our Values</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Advisory Board</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Our Partners</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Become a Partner</a></li>
                    <li><a href="#" class="hover:text-[#ff4667] transition-colors">Work at Future Learn</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-white mb-4">Newsletter</h4>
                <p class="text-xs text-gray-400 mb-3">Subscribe to get latest updates and course discounts.</p>
                <div class="space-y-2">
                    <input type="email" placeholder="Enter your email" class="w-full bg-[#142333] border border-gray-700 rounded-lg px-3 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#ff4667]" />
                    <button class="w-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold py-2 rounded-lg transition-colors">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-400">
            <p>© {{ date('Y') }} Dreams LMS. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition-colors">Terms & Conditions</a>
                <span>|</span>
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
