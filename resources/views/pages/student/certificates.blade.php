<x-layouts.dashboard title="My Certificates - Mono LMS" active="certificates" userType="student">

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
                    $style = $template?->template_type ?? 'template_3';
                @endphp
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Certificate Clickable Visual Representation -->
                        <a href="{{ route('student.certificates.print', $cert) }}"
                           target="_blank"
                           class="block relative aspect-[16/11] rounded-[6px] overflow-hidden mb-4 shadow-sm hover:shadow-md transition-transform group-hover:scale-[1.01]"
                           title="Click to view full certificate">

                            <div class="relative w-full aspect-[16/11] p-3 rounded-none shadow-md overflow-hidden flex flex-col justify-between text-center select-none bg-[#fdfcfa] border border-[#e5ddd0]">
                                <!-- Ornamental gold corners/borders -->
                                <div class="absolute inset-1.5 border border-[#b68d59] pointer-events-none"></div>
                                <div class="relative z-10 pt-0.5">
                                    <h4 class="font-baskerville text-[10px] font-bold text-slate-900 uppercase leading-none">CERTIFICATE</h4>
                                    <div class="flex items-center justify-center gap-1 mt-0.5">
                                        <span class="w-3 h-px bg-[#c29d6f]"></span>
                                        <span class="font-baskerville text-[6px] text-[#383634] uppercase font-semibold">OF ACHIEVEMENT</span>
                                        <span class="w-3 h-px bg-[#c29d6f]"></span>
                                    </div>
                                    <div class="flex items-center justify-center gap-1 mt-0.5 text-[#c29d6f] text-[5px]">
                                        <span>★</span><span>★</span><span>★</span><span>★</span>
                                    </div>
                                </div>
                                <div class="relative z-10 my-auto py-1">
                                    <span class="font-['Inter'] text-[6px] uppercase tracking-wider text-[#87817a] font-medium block">THIS IS PROUDLY PRESENTED TO</span>
                                    <h5 class="font-signature text-[20px] text-[#7d5729] leading-none">{{ $cert->student_name }}</h5>
                                    <div class="w-28 h-px bg-[#c5a278] mx-auto mt-0.5"></div>
                                    <p class="text-[5.5px] text-[#6b6763] line-clamp-1 max-w-[200px] mx-auto mt-0.5">{{ $cert->course_title }}</p>
                                </div>
                                <div class="relative z-10 pt-1 border-t border-[#e5ddd0] flex items-end justify-between text-[7px]">
                                    <div class="text-left w-14">
                                        <div class="w-8 h-px bg-[#b89160] mb-0.5"></div>
                                        <span class="text-[#55514d] text-[6px] block">{{ $cert->issue_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex flex-col items-center -mb-1">
                                        <span class="text-[6px] font-serif font-bold text-[#b68d59] leading-tight">★ BEST ★</span>
                                        <span class="text-[5px] font-serif text-[#b68d59] leading-none">AWARD</span>
                                    </div>
                                    <div class="text-right w-14">
                                        <div class="w-8 h-px bg-[#b89160] mb-0.5 ml-auto"></div>
                                        <span class="font-signature text-xs text-[#4a4642] block select-none leading-none truncate">{{ $cert->instructor_name ?: ($template->signer_name ?? 'Dean') }}</span>
                                    </div>
                                </div>
                            </div>
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
