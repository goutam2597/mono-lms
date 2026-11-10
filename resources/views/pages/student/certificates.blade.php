<x-layouts.dashboard title="My Certificates - Mono LMS" active="certificates" userType="student">

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

    @php
        $studentUser = auth('student')->user();
        $studentName = $studentUser?->name ?? 'Student';
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <h2 class="text-base font-bold uppercase tracking-wider text-slate-900">My Certificates & Diplomas</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20">
                        {{ $totalCount ?? $certificates->total() }} Earned
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Accredited, verifiable certificates awarded upon curriculum completion</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('certificates.verify.show') }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3.5 py-2 rounded-[6px] transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Verify Credential Tool</span>
                </a>
                <a href="{{ route('student.enrolled-courses') }}" class="inline-flex items-center gap-1.5 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-4 py-2 rounded-[6px] transition-colors shadow-xs">
                    <span>Enrolled Courses</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 shadow-xs flex items-center justify-between gap-4">
            <form method="GET" action="{{ route('student.certificates') }}" class="flex items-center gap-3 flex-1 max-w-md">
                <div class="relative flex-1">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search by course title or credential ID..."
                           class="w-full bg-white border border-slate-300 rounded-[6px] px-3.5 py-2 pl-9 text-xs focus:outline-none focus:border-[var(--theme-primary,#344c3d)]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="submit" class="px-3.5 py-2 bg-[#08131e] text-white font-semibold rounded-[6px] text-xs">
                    Search
                </button>
                @if (request('q'))
                    <a href="{{ route('student.certificates') }}" class="text-xs text-rose-600 font-medium hover:underline">Reset</a>
                @endif
            </form>
            <span class="text-xs text-slate-400 hidden sm:block">All certificates carry cryptographically verifiable credential IDs.</span>
        </div>

        <!-- Certificates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($certificates as $cert)
                @php
                    $template = $cert->template ?? \App\Models\CertificateTemplate::getDefault();
                    $style = $template?->template_type ?? 'classic';
                    $primaryColor = $template?->border_color ?: '#344c3d';
                    $accentColor = $template?->accent_color ?: '#738a6e';
                    $bgColor = $template?->bg_color ?: ($style === 'modern' ? '#ffffff' : '#fdfbf7');
                @endphp
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Certificate Clickable Visual Representation -->
                        <a href="{{ route('student.certificates.print', $cert) }}"
                           target="_blank"
                           class="block relative aspect-[16/11] rounded-[6px] overflow-hidden mb-4 shadow-sm hover:shadow-md transition-transform group-hover:scale-[1.01]"
                           title="Click to view full certificate">

                            @if ($style === 'modern')
                                <!-- MODERN MINIATURE -->
                                <div class="w-full h-full p-3.5 flex flex-col justify-between text-left select-none border border-slate-200"
                                     style="background-color: {{ $bgColor }};">
                                    <div class="absolute top-0 left-0 right-0 h-1.5" style="background: linear-gradient(90deg, {{ $primaryColor }} 0%, {{ $accentColor }} 70%, #d4af37 100%);"></div>
                                    <div class="absolute top-1.5 left-0 bottom-0 w-1.5" style="background: {{ $primaryColor }};"></div>
                                    <span class="absolute top-2 right-2.5 font-mono text-[8px] text-slate-300 select-none">+</span>
                                    <span class="absolute bottom-2 right-2.5 font-mono text-[8px] text-slate-300 select-none">+</span>

                                    <div class="pl-2 pt-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $primaryColor }};"></span>
                                            <span class="font-mono text-[7px] font-bold uppercase tracking-widest text-slate-400">MONO LMS // REGISTRY</span>
                                        </div>
                                        <h4 class="font-modern text-[10px] font-black uppercase text-slate-900 mt-1 line-clamp-1">{{ $cert->course_title }}</h4>
                                    </div>

                                    <div class="pl-2 my-auto py-1">
                                        <span class="font-mono text-[7px] text-slate-400 uppercase font-semibold">Awarded To</span>
                                        <h5 class="font-modern text-[12px] font-extrabold tracking-tight" style="color: {{ $primaryColor }};">{{ $cert->student_name }}</h5>
                                        <p class="text-[7px] text-slate-500 font-modern line-clamp-1 mt-0.5">{{ $cert->grade ?: 'Passed with Academic Distinction' }}</p>
                                    </div>

                                    <div class="pl-2 pt-1 border-t border-slate-100 flex items-center justify-between text-[7px]">
                                        <div>
                                            <span class="font-mono font-bold text-slate-800 block text-[8px] truncate max-w-[100px]">{{ $cert->certificate_number }}</span>
                                            <span class="font-mono text-slate-400 text-[6px]">{{ $cert->issue_date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono text-[7px] font-bold text-slate-700 flex items-center gap-1">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                            <span>VERIFIED</span>
                                        </div>
                                    </div>
                                </div>

                            @elseif ($style === 'elegant')
                                <!-- ELEGANT ROYAL MINIATURE -->
                                <div class="w-full h-full p-3.5 flex flex-col justify-between text-center select-none border-[5px] border-[#08131e]"
                                     style="background-color: {{ $bgColor }};">
                                    <div class="absolute inset-1 border pointer-events-none" style="border-color: #d4af37; opacity: 0.85;"></div>
                                    <svg class="absolute top-1 left-1 w-4 h-4 text-[#d4af37]" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                    <svg class="absolute top-1 right-1 w-4 h-4 text-[#d4af37] transform rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                    <svg class="absolute bottom-1 left-1 w-4 h-4 text-[#d4af37] transform -rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                    <svg class="absolute bottom-1 right-1 w-4 h-4 text-[#d4af37] transform rotate-180" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>

                                    <div class="relative z-10 pt-0.5">
                                        <span class="font-cinzel text-[7px] font-bold uppercase tracking-[0.25em] text-[#854d0e] block">ROYAL FACULTY SENATE</span>
                                        <h4 class="font-cinzel text-[9px] font-black uppercase text-slate-900 mt-0.5 line-clamp-1">{{ $cert->course_title }}</h4>
                                    </div>

                                    <div class="relative z-10 my-auto py-1">
                                        <span class="font-cormorant italic text-[7px] text-slate-500 block">Conferred With Distinction Upon</span>
                                        <p class="font-playfair text-[11px] font-black text-[#08131e] border-b border-[#d4af37] inline-block px-2 pb-0.5 mt-0.5">{{ $cert->student_name }}</p>
                                    </div>

                                    <div class="relative z-10 pt-1 border-t border-slate-200/90 flex items-end justify-between text-[7px]">
                                        <div class="text-left w-16">
                                            <span class="font-mono text-[7px] font-bold text-slate-800 block truncate">{{ $cert->certificate_number }}</span>
                                            <span class="text-slate-400 text-[6px] block">{{ $cert->issue_date->format('M Y') }}</span>
                                        </div>
                                        <div class="relative -mb-1 w-6 h-6 rounded-full gold-foil-gradient border border-amber-600 shadow-sm flex items-center justify-center text-amber-950 font-bold text-[8px] shrink-0">
                                            ★
                                        </div>
                                        <div class="text-right w-16">
                                            <span class="font-mono text-[7px] font-bold text-amber-900 block">EXCELLENCE</span>
                                            <span class="text-slate-400 text-[6px] block">ACCREDITED</span>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <!-- CLASSIC ACADEMIC MINIATURE -->
                                <div class="w-full h-full p-3.5 flex flex-col justify-between text-center select-none"
                                     style="background-color: {{ $bgColor }}; border: 4px double {{ $primaryColor }};">
                                    <div class="absolute inset-1 border border-slate-300/70 pointer-events-none"></div>
                                    <svg class="absolute top-1 left-1 w-5 h-5 pointer-events-none" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                    <svg class="absolute top-1 right-1 w-5 h-5 pointer-events-none transform rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                    <svg class="absolute bottom-1 left-1 w-5 h-5 pointer-events-none transform -rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                    <svg class="absolute bottom-1 right-1 w-5 h-5 pointer-events-none transform rotate-180" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>

                                    <div class="relative z-10 pt-0.5">
                                        <span class="font-cinzel text-[7px] font-bold uppercase tracking-[0.2em] text-slate-500 block leading-tight">MONO LMS SENATE</span>
                                        <h4 class="font-cinzel text-[9px] font-black uppercase text-slate-900 mt-0.5 line-clamp-1" style="color: {{ $primaryColor }};">{{ $cert->course_title }}</h4>
                                    </div>

                                    <div class="relative z-10 my-auto py-1">
                                        <span class="font-playfair italic text-[7px] text-slate-400 block">Proudly Awarded to</span>
                                        <p class="font-playfair text-[11px] font-extrabold text-slate-900 border-b border-amber-600/40 inline-block px-2 pb-0.5 mt-0.5">{{ $cert->student_name }}</p>
                                    </div>

                                    <div class="relative z-10 pt-1 border-t border-slate-200/80 flex items-end justify-between text-[7px]">
                                        <div class="text-left w-16">
                                            <span class="font-mono text-[7px] font-bold text-slate-800 block truncate">{{ $cert->certificate_number }}</span>
                                            <span class="text-slate-400 text-[6px] block">{{ $cert->issue_date->format('M Y') }}</span>
                                        </div>
                                        <div class="relative -mb-1 w-6 h-6 rounded-full gold-foil-gradient border-2 border-amber-600 shadow-sm flex items-center justify-center text-amber-950 font-bold text-[8px] shrink-0">
                                            ★
                                        </div>
                                        <div class="text-right w-16">
                                            <span class="font-mono text-[7px] font-bold text-slate-700 block">ACCREDITED</span>
                                            <span class="text-slate-400 text-[6px] block">VERIFIED</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </a>

                        <h3 class="font-bold text-sm text-slate-900 mb-1 leading-snug line-clamp-2">
                            {{ $cert->course_title }}
                        </h3>

                        <div class="text-xs text-slate-500 space-y-1.5 my-3 bg-slate-50 p-2.5 rounded-[6px] border border-slate-100">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[11px]">Credential ID:</span>
                                <strong class="font-mono text-slate-800 text-[11px] font-bold">{{ $cert->certificate_number }}</strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[11px]">Issue Date:</span>
                                <strong class="text-slate-700 text-[11px]">{{ $cert->issue_date->format('M d, Y') }}</strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-[11px]">Standing:</span>
                                <strong class="text-emerald-700 font-semibold text-[11px]">{{ $cert->grade ?: 'Passed with Distinction' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('student.certificates.print', $cert) }}"
                           target="_blank"
                           class="flex-1 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold py-2 px-3 rounded-[5px] transition-colors flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print / PDF</span>
                        </a>

                        <button type="button"
                                onclick="copyShareLink('{{ $cert->verificationUrl() }}', this)"
                                class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-2 rounded-[5px] transition-colors cursor-pointer"
                                title="Copy verification link">
                            <span>Share</span>
                        </button>

                        <a href="{{ $cert->verificationUrl() }}"
                           target="_blank"
                           class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold p-2 rounded-[5px] transition-colors"
                           title="Verify Credential Online">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-[#e2e8f0] rounded-[10px] p-12 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">No Certificates Earned Yet</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                        Complete 100% of the lessons in any of your enrolled courses to automatically unlock and receive your verified Certificate of Achievement.
                    </p>
                    <a href="{{ route('student.enrolled-courses') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold rounded-[6px] transition-colors shadow-xs">
                        <span>Continue Learning</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            @endforelse
        </div>

        @if ($certificates->hasPages())
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 shadow-xs">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>

    <script>
        function copyShareLink(url, btn) {
            navigator.clipboard.writeText(url).then(function() {
                var original = btn.innerHTML;
                btn.innerHTML = '<span class="text-emerald-600 font-bold">Copied! ✓</span>';
                setTimeout(function() { btn.innerHTML = original; }, 2000);
            });
        }
    </script>

</x-layouts.dashboard>
