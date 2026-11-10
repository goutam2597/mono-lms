<x-layouts.app title="Verify Credential - Mono LMS" active="certificates">

    <!-- Hero Header -->
    <div class="bg-[#f4f6f9] py-14 sm:py-20 border-b border-[#e7e7e7]">
        <div class="max-w-4xl mx-auto px-4 sm:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                Official Credential Registry
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#191919] tracking-tight">
                Certificate Verification
            </h1>
            <p class="text-sm sm:text-base text-slate-600 mt-3 max-w-xl mx-auto leading-relaxed">
                Confirm the authenticity and graduation standing of any student certificate issued by the Mono LMS Academy.
            </p>

            <!-- Verification Search Bar -->
            <form method="POST" action="{{ route('certificates.verify.search') }}" class="mt-8 max-w-xl mx-auto flex items-stretch gap-2 bg-white p-2 rounded-xl shadow-lg border border-slate-200">
                @csrf
                <div class="relative flex-1">
                    <input type="text"
                           name="code"
                           value="{{ old('code', $code ?? '') }}"
                           required
                           placeholder="Enter Credential ID (e.g. CERT-LMS-ABC-1234)..."
                           class="w-full px-4 py-3 text-xs sm:text-sm font-mono text-slate-900 placeholder-slate-400 focus:outline-none">
                </div>
                <button type="submit"
                        class="px-6 py-3 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-transform hover:scale-105 shadow-md shrink-0">
                    Verify
                </button>
            </form>
        </div>
    </div>

    <!-- Verification Results & Info -->
    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-12">
        @if ($searched)
            @if ($certificate)
                <!-- Verified Result Card -->
                <div class="bg-white border-2 border-emerald-500 rounded-2xl p-6 sm:p-10 shadow-xl space-y-6 animate-in fade-in zoom-in-95 duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xl shrink-0">
                                ✓
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                    Official Verified Credential
                                </span>
                                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">Authentic Certificate Confirmed</h2>
                            </div>
                        </div>

                        <div class="text-right font-mono text-xs text-slate-500">
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Credential ID</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $certificate->certificate_number }}</strong>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div class="space-y-1">
                            <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Recipient Student</span>
                            <p class="text-base font-bold text-slate-900">{{ $certificate->student_name }}</p>
                            <p class="text-xs text-slate-500">Verified identity registered on Mono LMS</p>
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Completed Curriculum</span>
                            <p class="text-base font-bold text-slate-900">{{ $certificate->course_title }}</p>
                            @if ($certificate->course)
                                <a href="{{ route('courses.show', $certificate->course->slug) }}" target="_blank" class="text-xs text-[var(--theme-primary,#344c3d)] hover:underline font-semibold inline-flex items-center gap-1">
                                    <span>View Course Overview</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Award Date</span>
                            <p class="text-sm font-semibold text-slate-800">{{ $certificate->issue_date->format('F d, Y') }}</p>
                            <p class="text-xs text-slate-400">{{ $certificate->issue_date->diffForHumans() }}</p>
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Academic Standing</span>
                            <p class="text-sm font-bold text-emerald-700 font-mono">{{ $certificate->grade ?: 'Passed with Distinction' }}</p>
                            <p class="text-xs text-slate-500">Faculty Instructor: {{ $certificate->instructor_name ?: 'Course Mentor' }}</p>
                        </div>
                    </div>

                    <!-- Bottom Action Bar -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-500 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Cryptographically logged and verified in Mono LMS secure registry.</span>
                        </div>

                        <a href="{{ route('student.certificates.print', $certificate) }}"
                           target="_blank"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>View Full Certificate</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Invalid ID Alert -->
                <div class="bg-rose-50 border-2 border-rose-300 rounded-2xl p-6 sm:p-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl mx-auto">
                        ✕
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Credential Not Found</h3>
                    <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
                        We could not locate any active certificate matching ID <strong class="font-mono text-rose-700">{{ $code }}</strong>. Please confirm the credential ID or contact our academic registry.
                    </p>
                </div>
            @endif
        @else
            <!-- Informational 3-col Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6">
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto text-lg font-bold">
                        🛡️
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Tamper-Proof Credentials</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Every certificate possesses an immutable alphanumeric credential ID registered at completion.</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center mx-auto text-lg font-bold">
                        ⚡
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Instant Global Verification</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Employers, university registrars, and recruiters can instantly verify skills worldwide in seconds.</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center mx-auto text-lg font-bold">
                        ★
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Accredited Standards</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Certificates adhere to industry recognized curriculum guidelines and verified faculty instruction.</p>
                </div>
            </div>
        @endif
    </div>

</x-layouts.app>
