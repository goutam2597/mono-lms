<x-layouts.dashboard title="My Certificates - Mono LMS" active="certificates" userType="student">

    @php
        $studentUser = auth('student')->user();
        $studentName = $studentUser?->name ?? 'Student';
    @endphp

    <div class="space-y-5">
        <!-- Header (Boxy) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">My Certificates (05)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Accredited verified certificates earned from completed LMS curricula</p>
            </div>
            <button type="button" class="inline-flex items-center gap-1.5 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-4 py-2 rounded-[6px] transition-colors shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Verify Credential ID</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $certs = [
                    ['title' => 'The Complete Business & Management Masterclass', 'code' => 'CERT-LMS-98214', 'date' => 'October 15, 2026', 'grade' => 'Grade A+ (94%)'],
                    ['title' => 'Modern React with Redux & Next.js 15', 'code' => 'CERT-LMS-74620', 'date' => 'September 28, 2026', 'grade' => 'Grade A (89%)'],
                    ['title' => 'Graphic Design Masterclass: Photoshop & Illustrator', 'code' => 'CERT-LMS-55319', 'date' => 'August 14, 2026', 'grade' => 'Grade A+ (96%)'],
                    ['title' => 'Agile Scrum Master Certification Guide', 'code' => 'CERT-LMS-41092', 'date' => 'July 05, 2026', 'grade' => 'Grade A (91%)'],
                    ['title' => 'SEO & Inbound Marketing Foundations', 'code' => 'CERT-LMS-33105', 'date' => 'May 20, 2026', 'grade' => 'Grade A+ (95%)'],
                ];
            @endphp

            @foreach($certs as $cert)
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <!-- Certificate Thumbnail Preview -->
                        <div class="relative h-36 bg-gradient-to-br from-slate-50 to-emerald-50/40 rounded-[6px] border border-dashed border-[var(--theme-primary,#344c3d)]/40 p-4 flex flex-col justify-between text-center mb-4">
                            <div class="text-[9px] font-bold tracking-widest text-[var(--theme-primary,#344c3d)] uppercase">Mono LMS Accredited Certificate</div>
                            <div>
                                <div class="text-xs font-bold text-slate-900 line-clamp-1">{{ $cert['title'] }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">Presented to {{ $studentName }}</div>
                            </div>
                            <div class="flex items-center justify-between text-[9px] text-[var(--theme-accent,#738a6e)] font-mono">
                                <span>{{ $cert['code'] }}</span>
                                <span class="font-bold text-emerald-600">Verified ✓</span>
                            </div>
                        </div>

                        <h4 class="font-bold text-xs sm:text-sm text-slate-900 mb-1.5 leading-snug">{{ $cert['title'] }}</h4>
                        <div class="text-xs text-slate-500 space-y-1 mb-4">
                            <div>Issued: <strong class="text-slate-800 font-semibold">{{ $cert['date'] }}</strong></div>
                            <div>Score: <strong class="text-emerald-600 font-semibold">{{ $cert['grade'] }}</strong></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                        <button type="button" class="flex-1 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold py-2 rounded-[5px] transition-colors flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Download PDF</span>
                        </button>
                        <button type="button" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-2 rounded-[5px] transition-colors cursor-pointer" title="Share credential">
                            Share
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.dashboard>
