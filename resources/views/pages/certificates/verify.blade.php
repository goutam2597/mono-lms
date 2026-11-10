<x-layouts.app title="Verify Credential - Mono LMS" active="certificates">

    <!-- Google Fonts for Prestigious Diploma & Certificate Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@500;600;700&family=Cinzel:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Island+Moments&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Alex+Brush&family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        .font-baskerville { font-family: 'Libre Baskerville', Georgia, serif; }
        .font-island { font-family: 'Island Moments', cursive; }
        .font-noto { font-family: 'Noto Serif', Georgia, serif; }
        .font-barlow { font-family: 'Barlow', sans-serif; }
        .font-cinzel { font-family: 'Cinzel', Georgia, serif; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
        .font-modern { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-signature { font-family: 'Alex Brush', cursive; }
        .font-signature-alt { font-family: 'Great Vibes', cursive; }
    </style>

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
                Confirm the authenticity, graduation standing, and academic accreditation of any diploma issued by the Mono LMS Academy.
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
                        class="px-6 py-3 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-transform hover:scale-105 shadow-md shrink-0 cursor-pointer">
                    Verify
                </button>
            </form>
        </div>
    </div>

    <!-- Verification Results & Info -->
    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-12">
        @if ($searched)
            @if ($certificate)
                @php
                    $template = $certificate->template ?? \App\Models\CertificateTemplate::getDefault();
                    $style = $template?->template_type ?? 'template_3';
                @endphp

                <!-- Verified Result Card -->
                <div class="bg-white border-2 border-emerald-500 rounded-2xl p-6 sm:p-10 shadow-xl space-y-8 animate-in fade-in zoom-in-95 duration-200">
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

                        <div class="text-left sm:text-right font-mono text-xs text-slate-500">
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Credential ID</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $certificate->certificate_number }}</strong>
                        </div>
                    </div>

                    <!-- Visual Diploma Certificate Miniature -->
                    <div class="bg-slate-100/70 p-4 sm:p-8 rounded-xl border border-slate-200 flex items-center justify-center">
                        <div class="w-full max-w-2xl aspect-[1.414/1] shadow-xl relative overflow-hidden flex flex-col justify-between select-none bg-white">

                            @if ($style === 'template_7' || $style === 'modern')
                                <div class="w-full h-full p-6 sm:p-8 flex flex-col justify-between text-left relative bg-white">
                                    <div class="absolute inset-3 border border-dashed border-slate-200 pointer-events-none"></div>
                                    <div class="absolute top-0 right-0 w-[55%] pointer-events-none opacity-90">
                                        <img src="{{ asset('images/certificates/template-7/top-accent.svg') }}" class="w-full h-auto" alt="">
                                    </div>
                                    <div class="relative z-10 pt-1">
                                        <span class="font-baskerville text-xs text-[#5a5549] uppercase block leading-tight">CERTIFICATE of</span>
                                        <h3 class="font-baskerville text-xl sm:text-2xl font-bold text-slate-900 uppercase">ACHIEVEMENT</h3>
                                    </div>
                                    <div class="relative z-10 my-auto py-2">
                                        <span class="font-['Inter'] text-xs uppercase tracking-wider text-slate-400 font-semibold block">Presented to</span>
                                        <h4 class="font-island text-3xl sm:text-5xl text-[#835a2a] leading-none">{{ $certificate->student_name }}</h4>
                                        <img src="{{ asset('images/certificates/template-7/name-underline.svg') }}" class="w-48 h-auto mt-1" alt="">
                                        <p class="font-sans text-xs text-slate-600 mt-2 max-w-md">{{ $certificate->course_title }}</p>
                                    </div>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('images/certificates/template-7/badge-seal.svg') }}" class="w-20 h-20 sm:w-28 sm:h-28 object-contain drop-shadow" alt="">
                                    </div>
                                    <div class="relative z-10 pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                        <span class="font-mono text-slate-600 font-bold">{{ $certificate->certificate_number }}</span>
                                        <span class="font-mono text-emerald-700 font-bold">OFFICIAL VERIFIED ✓</span>
                                    </div>
                                </div>

                            @elseif ($style === 'template_8' || $style === 'elegant')
                                <div class="w-full h-full rounded-none shadow-md overflow-hidden flex text-left relative bg-white">
                                    <div class="w-[72%] p-6 sm:p-8 flex flex-col justify-between">
                                        <div>
                                            <span class="font-baskerville text-xs text-[#5a5549] uppercase block leading-tight">CERTIFICATE of</span>
                                            <h3 class="font-baskerville text-xl sm:text-2xl font-bold text-slate-900 uppercase">ACHIEVEMENT</h3>
                                        </div>
                                        <div class="my-auto py-2">
                                            <span class="font-['Inter'] text-xs uppercase tracking-wider text-slate-400 block font-semibold">Presented to</span>
                                            <h4 class="font-island text-3xl sm:text-5xl text-[#835a2a] leading-none">{{ $certificate->student_name }}</h4>
                                            <img src="{{ asset('images/certificates/template-8/name-underline.svg') }}" class="w-48 h-auto mt-1" alt="">
                                            <p class="font-sans text-xs text-slate-600 mt-2 max-w-sm">{{ $certificate->course_title }}</p>
                                        </div>
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                            <span class="font-mono text-slate-600 font-bold">{{ $certificate->certificate_number }}</span>
                                        </div>
                                    </div>
                                    <div class="w-[28%] bg-slate-50 border-l border-[#e5cfb7] relative overflow-hidden">
                                        <div class="absolute inset-0 opacity-70 -rotate-45 scale-125">
                                            <img src="{{ asset('images/certificates/template-8/pattern-1.svg') }}" class="w-full h-auto" alt="">
                                        </div>
                                        <div class="absolute -left-6 top-1/2 -translate-y-1/2 z-10">
                                            <img src="{{ asset('images/certificates/template-8/badge-seal.svg') }}" class="w-16 h-16 sm:w-20 sm:h-20 object-contain drop-shadow" alt="">
                                        </div>
                                    </div>
                                </div>

                            @elseif ($style === 'template_9')
                                <div class="w-full h-full rounded-none shadow-md overflow-hidden flex text-left relative bg-[#fdfbf7]">
                                    <div class="w-[74%] p-6 sm:p-8 flex flex-col justify-between">
                                        <div>
                                            <span class="font-baskerville text-xs text-[#4d493f] uppercase block leading-tight">Certificate of</span>
                                            <h3 class="font-baskerville text-xl sm:text-2xl font-bold text-slate-900 uppercase">Achievement</h3>
                                        </div>
                                        <div class="my-auto py-2">
                                            <span class="font-noto text-xs text-slate-500 block">Presented to</span>
                                            <h4 class="font-island text-3xl sm:text-5xl text-[#835a2a] leading-none">{{ $certificate->student_name }}</h4>
                                            <img src="{{ asset('images/certificates/template-9/line.svg') }}" class="w-48 h-auto mt-1" alt="">
                                            <p class="font-noto text-xs text-slate-600 mt-2 max-w-sm">{{ $certificate->course_title }}</p>
                                        </div>
                                        <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
                                            <span class="font-mono text-slate-700 font-bold">{{ $certificate->certificate_number }}</span>
                                            <span class="font-mono text-emerald-700 font-bold">VERIFIED</span>
                                        </div>
                                    </div>
                                    <div class="w-[26%] bg-[#faf7f2] relative overflow-hidden border-l border-[#e5cfb7]">
                                        <div class="absolute inset-0 opacity-80 rotate-12 scale-150">
                                            <img src="{{ asset('images/certificates/template-9/pattern.svg') }}" class="w-full h-full object-cover" alt="">
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <img src="{{ asset('images/certificates/template-9/seal-circle.svg') }}" class="w-16 h-16 object-contain drop-shadow" alt="">
                                        </div>
                                    </div>
                                </div>

                            @else
                                <!-- FIGMA TEMPLATE 3 (DEFAULT) -->
                                <div class="w-full h-full p-6 sm:p-8 flex flex-col justify-between text-center relative bg-white">
                                    <img src="{{ asset('images/certificates/template-3/bg-pattern.svg') }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none opacity-80" alt="">
                                    <div class="absolute inset-3 pointer-events-none">
                                        <img src="{{ asset('images/certificates/template-3/border-decor.svg') }}" class="w-full h-full object-fill" alt="">
                                    </div>
                                    <div class="relative z-10 pt-1">
                                        <h3 class="font-baskerville text-xl sm:text-3xl font-bold text-slate-900 uppercase leading-none">CERTIFICATE</h3>
                                        <div class="flex items-center justify-center gap-2 mt-1">
                                            <img src="{{ asset('images/certificates/template-3/flourish-left.svg') }}" class="h-2.5 w-auto" alt="">
                                            <span class="font-baskerville text-[9px] sm:text-xs text-[#33312a] uppercase">OF ACHIEVEMENT</span>
                                            <img src="{{ asset('images/certificates/template-3/flourish-right.svg') }}" class="h-2.5 w-auto" alt="">
                                        </div>
                                    </div>
                                    <div class="relative z-10 my-auto py-2">
                                        <span class="font-['Inter'] text-[10px] uppercase tracking-wider text-slate-400 font-semibold block">Presented to</span>
                                        <h4 class="font-island text-3xl sm:text-6xl text-[#835a2a] leading-none">{{ $certificate->student_name }}</h4>
                                        <div class="flex items-center justify-center w-56 mx-auto -mt-1">
                                            <img src="{{ asset('images/certificates/template-3/underline-left.svg') }}" class="h-2 w-auto" alt="">
                                            <div class="flex-1 h-px bg-[#835a2a]"></div>
                                            <img src="{{ asset('images/certificates/template-3/underline-right.svg') }}" class="h-2 w-auto" alt="">
                                        </div>
                                        <p class="font-sans text-xs text-slate-600 mt-2 max-w-sm mx-auto">{{ $certificate->course_title }}</p>
                                    </div>
                                    <div class="relative z-10 pt-2 border-t border-slate-200/80 flex items-end justify-between text-xs">
                                        <span class="font-mono text-slate-600">{{ $certificate->issue_date->format('d M Y') }}</span>
                                        <div class="relative -mb-2">
                                            <img src="{{ asset('images/certificates/template-3/badge.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow" alt="Seal">
                                        </div>
                                        <span class="font-mono text-emerald-700 font-bold">VERIFIED ✓</span>
                                    </div>
                                </div>
                            @endif

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

                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <a href="{{ route('student.certificates.print', $certificate) }}"
                               target="_blank"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Print / Save PDF</span>
                            </a>
                        </div>
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
