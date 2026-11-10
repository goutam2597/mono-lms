<x-layouts.app title="Verify Credential - Mono LMS" active="certificates">

    <!-- Google Fonts for Prestigious Diploma & Certificate Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Alex+Brush&family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        .font-cinzel { font-family: 'Cinzel', Georgia, serif; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
        .font-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-modern { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-signature { font-family: 'Alex Brush', cursive; }
        .font-signature-alt { font-family: 'Great Vibes', cursive; }
        .gold-foil-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #fef3c7 35%, #b48608 70%, #fef08a 100%);
        }
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
                    $style = $template?->template_type ?? 'classic';
                    $primaryColor = $template?->border_color ?: '#344c3d';
                    $accentColor = $template?->accent_color ?: '#738a6e';
                    $bgColor = $template?->bg_color ?: ($style === 'modern' ? '#ffffff' : '#fdfbf7');
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
                        <div class="w-full max-w-2xl aspect-[1.414/1] shadow-xl relative overflow-hidden flex flex-col justify-between select-none"
                             @if ($style === 'modern')
                                 style="background-color: {{ $bgColor }};"
                             @elseif ($style === 'elegant')
                                 style="background-color: {{ $bgColor }}; border: 8px solid #08131e;"
                             @else
                                 style="background-color: {{ $bgColor }}; border: 8px double {{ $primaryColor }};"
                             @endif
                        >

                            @if ($style === 'modern')
                                <!-- MODERN MINIMALIST -->
                                <div class="absolute top-0 left-0 right-0 h-2" style="background: linear-gradient(90deg, {{ $primaryColor }} 0%, {{ $accentColor }} 70%, #d4af37 100%);"></div>
                                <div class="absolute top-2 left-0 bottom-0 w-2" style="background: {{ $primaryColor }};"></div>
                                <span class="absolute top-3 right-4 font-mono text-[10px] text-slate-300 select-none">+</span>
                                <span class="absolute bottom-3 right-4 font-mono text-[10px] text-slate-300 select-none">+</span>

                                <div class="p-6 sm:p-8 pl-8 flex-1 flex flex-col justify-between font-modern text-left">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full" style="background: {{ $primaryColor }};"></span>
                                            <span class="font-mono text-[8px] sm:text-[9px] font-bold uppercase tracking-widest text-slate-400">MONO LMS // ACADEMIC REGISTRY</span>
                                        </div>
                                        <h3 class="font-modern text-base sm:text-xl font-black uppercase text-slate-900 mt-1">{{ $certificate->course_title }}</h3>
                                    </div>

                                    <div class="my-auto py-2">
                                        <span class="font-mono text-[8px] text-slate-400 uppercase font-semibold">Credential Conferred Upon</span>
                                        <h4 class="font-modern text-xl sm:text-2xl font-extrabold tracking-tight mt-0.5" style="color: {{ $primaryColor }};">{{ $certificate->student_name }}</h4>
                                        <p class="text-[9px] sm:text-xs text-slate-500 font-modern mt-1">{{ $certificate->grade ?: 'Highest Academic Distinction' }}</p>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[8px] sm:text-[10px]">
                                        <div>
                                            <span class="font-bold text-slate-800 block">{{ $certificate->instructor_name ?: 'Course Mentor' }}</span>
                                            <span class="font-mono text-slate-400 text-[8px]">Instructor</span>
                                        </div>
                                        <div class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono font-bold text-slate-700 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>VERIFIED</span>
                                        </div>
                                    </div>
                                </div>

                            @elseif ($style === 'elegant')
                                <!-- ELEGANT ROYAL -->
                                <div class="absolute inset-2 border pointer-events-none" style="border-color: #d4af37; opacity: 0.9;"></div>
                                <svg class="absolute top-2 left-2 w-5 h-5 text-[#d4af37]" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                <svg class="absolute top-2 right-2 w-5 h-5 text-[#d4af37] transform rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                <svg class="absolute bottom-2 left-2 w-5 h-5 text-[#d4af37] transform -rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                <svg class="absolute bottom-2 right-2 w-5 h-5 text-[#d4af37] transform rotate-180" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>

                                <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between text-center font-cormorant">
                                    <div class="relative z-10 pt-1">
                                        <span class="font-cinzel text-[8px] sm:text-[9px] font-bold uppercase tracking-[0.25em] text-[#854d0e] block">ROYAL FACULTY SENATE</span>
                                        <h3 class="font-cinzel text-base sm:text-xl font-black uppercase text-slate-900 mt-1">{{ $certificate->course_title }}</h3>
                                    </div>

                                    <div class="relative z-10 my-auto py-2">
                                        <span class="font-cormorant italic text-[9px] text-slate-500 block">Conferred With Highest Honor Upon</span>
                                        <p class="font-playfair text-xl sm:text-2xl font-black text-[#08131e] border-b-2 border-[#d4af37] inline-block px-4 pb-0.5 mt-1">{{ $certificate->student_name }}</p>
                                    </div>

                                    <div class="relative z-10 pt-2 border-t border-slate-200/90 flex items-end justify-between text-[8px] sm:text-[10px]">
                                        <div class="text-left w-24">
                                            <span class="font-signature-alt text-base text-slate-900 block leading-none truncate">{{ $certificate->instructor_name ?: 'Course Mentor' }}</span>
                                            <span class="text-slate-400 text-[8px] block">Lead Faculty</span>
                                        </div>
                                        <div class="w-10 h-10 rounded-full gold-foil-gradient border border-amber-600 shadow-md flex items-center justify-center text-amber-950 font-bold text-xs shrink-0">
                                            ★
                                        </div>
                                        <div class="text-right w-24">
                                            <span class="font-mono text-[8px] font-bold text-amber-900 block">ROYAL SEAL</span>
                                            <span class="text-slate-400 text-[8px] block">ACCREDITED</span>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <!-- CLASSIC ACADEMIC -->
                                <div class="absolute inset-1.5 border border-slate-300 pointer-events-none"></div>
                                <svg class="absolute top-2 left-2 w-6 h-6 pointer-events-none" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                <svg class="absolute top-2 right-2 w-6 h-6 pointer-events-none transform rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                <svg class="absolute bottom-2 left-2 w-6 h-6 pointer-events-none transform -rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                <svg class="absolute bottom-2 right-2 w-6 h-6 pointer-events-none transform rotate-180" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>

                                <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between text-center">
                                    <div class="relative z-10 pt-1">
                                        <span class="font-cinzel text-[8px] sm:text-[9px] font-bold uppercase tracking-[0.2em] text-slate-500 block">MONO LMS SENATE</span>
                                        <h3 class="font-cinzel text-base sm:text-xl font-black uppercase text-slate-900 mt-1" style="color: {{ $primaryColor }};">{{ $certificate->course_title }}</h3>
                                    </div>

                                    <div class="relative z-10 my-auto py-2">
                                        <p class="font-playfair italic text-[9px] text-slate-400">This proudly certifies that</p>
                                        <p class="font-playfair text-xl sm:text-2xl font-extrabold text-slate-900 border-b-2 border-amber-600/40 inline-block px-4 pb-0.5 mt-1">{{ $certificate->student_name }}</p>
                                    </div>

                                    <div class="relative z-10 pt-2 border-t border-slate-200/80 flex items-end justify-between text-[8px] sm:text-[10px]">
                                        <div class="text-left w-24">
                                            <span class="font-signature text-base text-slate-800 block select-none leading-none truncate">{{ $certificate->instructor_name ?: 'Course Mentor' }}</span>
                                            <span class="text-slate-400 text-[8px] block">Instructor</span>
                                        </div>
                                        <div class="w-10 h-10 rounded-full gold-foil-gradient border-2 border-amber-600 shadow-md flex items-center justify-center text-amber-950 font-bold text-xs shrink-0">
                                            ★
                                        </div>
                                        <div class="text-right w-24">
                                            <span class="font-mono text-[8px] font-bold text-slate-700 block">ACCREDITED</span>
                                            <span class="text-slate-400 text-[8px] block">VERIFIED</span>
                                        </div>
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
