<x-layouts.app :title="($page->meta_title ?: $page->title).' - Mono LMS'" active="contact">

    @if ($isDraftPreview ?? false)
        <div class="bg-amber-500 text-slate-950 font-bold text-xs py-2 px-4 text-center sticky top-0 z-50 shadow-md">
            ⚠️ Draft Preview Mode: This page is currently unpublished and only visible to administrators.
        </div>
    @endif

    <!-- Hero Header -->
    <section class="bg-[#f4f6f9] py-14 sm:py-20 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 text-center">
            @if ($eyebrow = $page->setting('eyebrow'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                    {{ $eyebrow }}
                </span>
            @endif
            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#191919] tracking-tight max-w-3xl mx-auto">
                {{ $page->title }}
            </h1>
            @if ($subtitle = $page->setting('subtitle'))
                <p class="text-base sm:text-lg text-slate-600 mt-4 max-w-2xl mx-auto leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
            <nav class="flex items-center justify-center gap-2 text-xs text-[#6d6d6d] mt-6">
                <a href="{{ route('home') }}" class="hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                <span>/</span>
                <span class="text-[#738a6e] font-medium">{{ $page->title }}</span>
            </nav>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Email -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 shadow-2xs hover:border-[var(--theme-primary,#344c3d)]/40 transition-colors">
                    <div class="w-11 h-11 rounded-xl bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    </div>
                    <h3 class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Email Support</h3>
                    <p class="text-sm font-bold text-slate-900 break-all">{{ $page->setting('email', 'support@mono-lms.com') }}</p>
                    <p class="text-xs text-slate-500 mt-1">24/7 dedicated support</p>
                </div>

                <!-- Phone -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 shadow-2xs hover:border-[var(--theme-primary,#344c3d)]/40 transition-colors">
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </div>
                    <h3 class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Direct Hotline</h3>
                    <p class="text-sm font-bold text-slate-900">{{ $page->setting('phone', '+1 (458) 877-7787') }}</p>
                    <p class="text-xs text-slate-500 mt-1">Toll-free customer line</p>
                </div>

                <!-- Address -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 shadow-2xs hover:border-[var(--theme-primary,#344c3d)]/40 transition-colors">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <h3 class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Headquarters</h3>
                    <p class="text-sm font-bold text-slate-900 leading-snug">{{ $page->setting('address', '1442 Crosswind Drive, Madisonville, KY 42431') }}</p>
                </div>

                <!-- Office Hours -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 shadow-2xs hover:border-[var(--theme-primary,#344c3d)]/40 transition-colors">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Operating Hours</h3>
                    <p class="text-sm font-bold text-slate-900 leading-snug">{{ $page->setting('office_hours', 'Mon – Fri: 9:00 AM – 6:00 PM EST') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content: Form & Map Split -->
    <section class="py-12 pb-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Left: Interactive Contact Form -->
                <div class="lg:col-span-7">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-xs">
                        <div class="mb-8">
                            <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                                    {{ $page->setting('form_heading', 'Send Us a Message') }}
                                </h2>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Public Inquiry · No Login Required
                                </span>
                            </div>
                            @if ($formSub = $page->setting('form_subtitle'))
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    {{ $formSub }}
                                </p>
                            @else
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    Have a question or need assistance? Fill out the form below and our team will get back to you promptly.
                                </p>
                            @endif
                        </div>

                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                                <p class="font-bold">Please correct the following errors:</p>
                                <ul class="list-disc list-inside mt-1 text-xs">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="contact_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        Your Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="contact_name"
                                           name="name"
                                           value="{{ old('name', auth('student')->user()?->name ?? auth('instructor')->user()?->name ?? auth('admin')->user()?->name) }}"
                                           required
                                           placeholder="e.g. Alex Johnson"
                                           class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--theme-primary,#344c3d)]/20 focus:border-[var(--theme-primary,#344c3d)]">
                                </div>

                                <div>
                                    <label for="contact_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           id="contact_email"
                                           name="email"
                                           value="{{ old('email', auth('student')->user()?->email ?? auth('instructor')->user()?->email ?? auth('admin')->user()?->email) }}"
                                           required
                                           placeholder="you@domain.com"
                                           class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--theme-primary,#344c3d)]/20 focus:border-[var(--theme-primary,#344c3d)]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="contact_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        Phone Number (Optional)
                                    </label>
                                    <input type="text"
                                           id="contact_phone"
                                           name="phone"
                                           value="{{ old('phone', auth('student')->user()?->phone ?? auth('instructor')->user()?->phone) }}"
                                           placeholder="+1 (555) 000-0000"
                                           class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--theme-primary,#344c3d)]/20 focus:border-[var(--theme-primary,#344c3d)]">
                                </div>

                                <div>
                                    <label for="contact_subject" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                        Inquiry Subject <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="contact_subject"
                                           name="subject"
                                           value="{{ old('subject') }}"
                                           required
                                           placeholder="e.g. Course enrollment question"
                                           class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--theme-primary,#344c3d)]/20 focus:border-[var(--theme-primary,#344c3d)]">
                                </div>
                            </div>

                            <div>
                                <label for="contact_message" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Message Details <span class="text-red-500">*</span>
                                </label>
                                <textarea id="contact_message"
                                          name="message"
                                          rows="5"
                                          required
                                          placeholder="How can our support team assist you today?"
                                          class="w-full px-4 py-3 text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--theme-primary,#344c3d)]/20 focus:border-[var(--theme-primary,#344c3d)]">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer">
                                <span>Submit Inquiry</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Interactive Map & Helpbox -->
                <div class="lg:col-span-5 space-y-6">
                    @if ($mapUrl = $page->setting('map_embed_url'))
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden shadow-xs h-80 sm:h-96">
                            <iframe src="{{ $mapUrl }}"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="w-full h-full"></iframe>
                        </div>
                    @endif

                    <div class="bg-slate-900 text-white rounded-2xl p-6 sm:p-8 shadow-md">
                        <h3 class="text-lg font-bold">Need Helpdesk Support?</h3>
                        <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                            Already enrolled in a course or teaching on the platform? Log in to your student or instructor panel to submit and track direct support tickets.
                        </p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white transition-colors">
                                <span>Student Login</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                            <a href="{{ route('instructor.login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold bg-[#344c3d] hover:bg-[#25372c] text-white transition-colors">
                                <span>Instructor Portal</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
