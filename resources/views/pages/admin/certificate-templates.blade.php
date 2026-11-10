<x-layouts.dashboard title="Certificate Templates - Mono LMS" active="certificates" userType="admin">

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

    <div class="space-y-6">
        <!-- Top Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Design Templates</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $templates->count() }}</p>
                    <span class="text-[11px] text-slate-400">Available award styles</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Default Template</span>
                    <p class="text-base font-bold text-slate-900 mt-1 line-clamp-1">{{ $defaultTemplate?->title ?? 'None Set' }}</p>
                    <span class="text-[11px] text-emerald-600/80 font-medium font-mono uppercase">{{ str_replace('_', ' ', $defaultTemplate?->template_type ?? 'template 3') }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Issued Certificates</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalIssued ?? 0) }}</p>
                    <span class="text-[11px] text-slate-400">Awarded to graduates</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
            </div>
        </div>

        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-5 sm:p-6 shadow-xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Certificate Templates Gallery</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Design, customize, and manage executive diplomas & accredited certificates designed from Figma Dev Mode.</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('certificates.verify.show') }}" target="_blank" class="action-btn action-btn-ghost h-9 px-3.5 text-xs font-semibold shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Verify Registry</span>
                </a>
                <button type="button" onclick="openCreateModal()" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider shrink-0 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Template</span>
                </button>
            </div>
        </div>

        <!-- Certificate Templates Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($templates as $template)
                @php
                    $style = $template->template_type ?? 'template_3';
                    $primaryColor = $template->border_color ?: '#1a1815';
                    $accentColor = $template->accent_color ?: '#835a2a';
                    $bgColor = $template->bg_color ?: '#ffffff';
                @endphp
                <div class="bg-white border border-slate-200 rounded-none overflow-hidden shadow-xs flex flex-col justify-between hover:border-slate-400 transition-all group">
                    <!-- Certificate Miniature Graphical Canvas -->
                    <div class="p-4 sm:p-5 bg-slate-100/70 border-b border-slate-200 flex items-center justify-center">

                        @if ($style === 'template_7' || $style === 'modern')
                            <!-- FIGMA TEMPLATE 7 MINIATURE -->
                            <div class="relative w-full aspect-[16/11] p-3 rounded-none shadow-md overflow-hidden flex flex-col justify-between text-left select-none border border-slate-200 group-hover:shadow-lg transition-shadow bg-white">
                                <div class="absolute inset-1.5 border border-dashed border-slate-200 pointer-events-none"></div>
                                <div class="absolute top-0 right-0 w-[55%] pointer-events-none opacity-90">
                                    <img src="{{ asset('images/certificates/template-7/top-accent.svg') }}" class="w-full h-auto" alt="">
                                </div>
                                <div class="relative z-10 pt-0.5">
                                    <span class="font-baskerville text-[7px] text-[#5a5549] uppercase block leading-tight">CERTIFICATE of</span>
                                    <h4 class="font-baskerville text-[10px] font-bold text-slate-900 uppercase">ACHIEVEMENT</h4>
                                </div>
                                <div class="relative z-10 my-auto py-1">
                                    <span class="font-['Inter'] text-[6px] uppercase tracking-wider text-slate-400 font-semibold block">Presented to</span>
                                    <h5 class="font-island text-[18px] text-[#835a2a] leading-none">Alexandre Montgomery</h5>
                                    <img src="{{ asset('images/certificates/template-7/name-underline.svg') }}" class="w-24 h-auto mt-0.5" alt="">
                                </div>
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('images/certificates/template-7/badge-seal.svg') }}" class="w-10 h-10 object-contain drop-shadow" alt="">
                                </div>
                                <div class="relative z-10 pt-1 border-t border-slate-100 flex items-center justify-between text-[7px]">
                                    <div>
                                        <span class="font-signature text-xs text-slate-800 block select-none leading-none">{{ $template->signer_name }}</span>
                                        <span class="text-slate-400 text-[6px] truncate block">{{ $template->signature_title }}</span>
                                    </div>
                                    <div class="font-mono text-[7px] text-slate-400">
                                        VERIFIED ✓
                                    </div>
                                </div>
                            </div>

                        @elseif ($style === 'template_8' || $style === 'elegant')
                            <!-- FIGMA TEMPLATE 8 MINIATURE -->
                            <div class="relative w-full aspect-[16/11] rounded-none shadow-md overflow-hidden flex text-left select-none border border-slate-200 group-hover:shadow-lg transition-shadow bg-white">
                                <div class="w-[72%] p-3 flex flex-col justify-between">
                                    <div>
                                        <span class="font-baskerville text-[7px] text-[#5a5549] uppercase block leading-tight">CERTIFICATE of</span>
                                        <h4 class="font-baskerville text-[10px] font-bold text-slate-900 uppercase">ACHIEVEMENT</h4>
                                    </div>
                                    <div class="my-auto py-1">
                                        <span class="font-['Inter'] text-[6px] uppercase tracking-wider text-slate-400 block font-semibold">Presented to</span>
                                        <h5 class="font-island text-[18px] text-[#835a2a] leading-none">Alexandre Montgomery</h5>
                                        <img src="{{ asset('images/certificates/template-8/name-underline.svg') }}" class="w-24 h-auto mt-0.5" alt="">
                                    </div>
                                    <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-[7px]">
                                        <div>
                                            <span class="font-signature text-xs text-slate-800 block select-none leading-none">{{ $template->signer_name }}</span>
                                            <span class="text-slate-400 text-[6px] truncate block">{{ $template->signature_title }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-[28%] bg-slate-50 border-l border-[#e5cfb7] relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-70 -rotate-45 scale-125">
                                        <img src="{{ asset('images/certificates/template-8/pattern-1.svg') }}" class="w-full h-auto" alt="">
                                    </div>
                                    <div class="absolute -left-4 top-1/2 -translate-y-1/2 z-10">
                                        <img src="{{ asset('images/certificates/template-8/badge-seal.svg') }}" class="w-8 h-8 object-contain drop-shadow" alt="">
                                    </div>
                                </div>
                            </div>

                        @elseif ($style === 'template_9')
                            <!-- FIGMA TEMPLATE 9 MINIATURE -->
                            <div class="relative w-full aspect-[16/11] rounded-none shadow-md overflow-hidden flex text-left select-none border border-slate-200 group-hover:shadow-lg transition-shadow bg-[#fdfbf7]">
                                <div class="w-[74%] p-3 flex flex-col justify-between">
                                    <div>
                                        <span class="font-baskerville text-[7px] text-[#4d493f] uppercase block leading-tight">Certificate of</span>
                                        <h4 class="font-baskerville text-[10px] font-bold text-slate-900 uppercase">Achievement</h4>
                                    </div>
                                    <div class="my-auto py-1">
                                        <span class="font-noto text-[6px] text-slate-500 block">Presented to</span>
                                        <h5 class="font-island text-[18px] text-[#835a2a] leading-none">Alexandre Montgomery</h5>
                                        <img src="{{ asset('images/certificates/template-9/line.svg') }}" class="w-24 h-auto mt-0.5" alt="">
                                    </div>
                                    <div class="pt-1 border-t border-slate-200 flex items-center justify-between text-[7px]">
                                        <span class="font-noto text-[7px] text-slate-700">{{ $template->signer_name }}</span>
                                        <span class="font-mono text-[6px] text-slate-400">VERIFIED</span>
                                    </div>
                                </div>
                                <div class="w-[26%] bg-[#faf7f2] relative overflow-hidden border-l border-[#e5cfb7]">
                                    <div class="absolute inset-0 opacity-80 rotate-12 scale-150">
                                        <img src="{{ asset('images/certificates/template-9/pattern.svg') }}" class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <img src="{{ asset('images/certificates/template-9/seal-circle.svg') }}" class="w-7 h-7 object-contain drop-shadow" alt="">
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- FIGMA TEMPLATE 3 (DEFAULT) MINIATURE -->
                            <div class="relative w-full aspect-[16/11] p-3 rounded-none shadow-md overflow-hidden flex flex-col justify-between text-center select-none group-hover:shadow-lg transition-shadow bg-white">
                                <img src="{{ asset('images/certificates/template-3/bg-pattern.svg') }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none opacity-80" alt="">
                                <div class="absolute inset-1.5 pointer-events-none">
                                    <img src="{{ asset('images/certificates/template-3/border-decor.svg') }}" class="w-full h-full object-fill" alt="">
                                </div>
                                <div class="relative z-10 pt-0.5">
                                    <h4 class="font-baskerville text-[10px] font-bold text-slate-900 uppercase leading-none">CERTIFICATE</h4>
                                    <div class="flex items-center justify-center gap-1 mt-0.5">
                                        <img src="{{ asset('images/certificates/template-3/flourish-left.svg') }}" class="h-1.5 w-auto" alt="">
                                        <span class="font-baskerville text-[6px] text-[#33312a] uppercase">OF ACHIEVEMENT</span>
                                        <img src="{{ asset('images/certificates/template-3/flourish-right.svg') }}" class="h-1.5 w-auto" alt="">
                                    </div>
                                </div>
                                <div class="relative z-10 my-auto py-1">
                                    <span class="font-['Inter'] text-[6px] uppercase tracking-wider text-slate-400 font-semibold block">Presented to</span>
                                    <h5 class="font-island text-[20px] text-[#835a2a] leading-none">Alexandre Montgomery</h5>
                                    <div class="flex items-center justify-center w-28 mx-auto -mt-0.5">
                                        <img src="{{ asset('images/certificates/template-3/underline-left.svg') }}" class="h-1.5 w-auto" alt="">
                                        <div class="flex-1 h-px bg-[#835a2a]"></div>
                                        <img src="{{ asset('images/certificates/template-3/underline-right.svg') }}" class="h-1.5 w-auto" alt="">
                                    </div>
                                </div>
                                <div class="relative z-10 pt-1 border-t border-slate-200/80 flex items-end justify-between text-[7px]">
                                    <div class="text-left w-14">
                                        <span class="text-slate-400 text-[6px] block truncate">Award Date</span>
                                    </div>
                                    <div class="relative -mb-1">
                                        <img src="{{ asset('images/certificates/template-3/badge.svg') }}" class="w-7 h-7 object-contain drop-shadow" alt="Seal">
                                    </div>
                                    <div class="text-right w-14">
                                        <span class="font-signature text-xs text-slate-800 block select-none leading-none truncate">{{ $template->signer_name }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Template Info & Controls -->
                    <div class="p-5 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-slate-900">{{ $template->title }}</h3>
                                    @if($template->is_default)
                                        <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Default
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider px-1.5 py-0.5 bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ str_replace('_', ' ', $template->template_type) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">•</span>
                                    <span class="text-[10px] text-slate-500 font-medium">
                                        {{ $template->certificates_count }} Issued
                                    </span>
                                </div>
                            </div>

                            @if(! $template->is_default)
                                <form method="POST" action="{{ route('admin.certificates.default', $template) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn action-btn-ghost h-7 px-2.5 text-[10px]">
                                        Set Default
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-1">
                                    ✓ Active
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="openEditModal({{ json_encode($template) }})"
                                        class="text-xs font-semibold text-slate-700 hover:text-slate-900 hover:underline">
                                    Edit Settings
                                </button>
                                <span class="text-slate-300">•</span>
                                <button type="button"
                                        onclick="openPreviewModal({{ json_encode($template) }})"
                                        class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">
                                    Live Preview
                                </button>
                                <span class="text-slate-300">•</span>
                                <a href="{{ route('admin.certificates.preview', $template) }}"
                                   target="_blank"
                                   class="text-xs font-semibold text-emerald-700 hover:underline"
                                   title="Open full A4 landscape printable sheet">
                                    Full A4
                                </a>
                            </div>

                            @if (! $template->is_default)
                                <form method="POST" action="{{ route('admin.certificates.destroy', $template) }}" onsubmit="return confirm('Delete template \'{{ $template->title }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold" title="Delete Template">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Issued Certificates Registry -->
        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919] text-base">Graduation Registry & Issued Certificates</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Real-time log of credentials awarded to students upon course completion</p>
                </div>
                <span class="text-xs font-bold text-slate-700 font-mono">
                    {{ number_format($totalIssued) }} total verified records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold">Credential ID</th>
                            <th class="px-5 py-3 font-semibold">Student Name</th>
                            <th class="px-5 py-3 font-semibold">Course Title</th>
                            <th class="px-5 py-3 font-semibold">Template</th>
                            <th class="px-5 py-3 font-semibold">Standing</th>
                            <th class="px-5 py-3 font-semibold">Issue Date</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($issued as $cert)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900 whitespace-nowrap">
                                    {{ $cert->certificate_number }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <p class="font-bold text-slate-900">{{ $cert->student_name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $cert->student?->email ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 max-w-xs">
                                    <p class="font-semibold text-slate-800 truncate">{{ $cert->course_title }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">Instructor: {{ $cert->instructor_name ?: 'Course Mentor' }}</p>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-600">
                                    {{ $cert->template?->title ?? 'Default Template' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-emerald-700 font-bold font-mono text-[11px]">
                                        {{ $cert->grade ?: 'Passed with Distinction' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-500 text-[11px]">
                                    {{ $cert->issue_date->format('M d, Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('student.certificates.print', $cert) }}"
                                           target="_blank"
                                           class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition-colors">
                                            Print / PDF
                                        </a>
                                        <a href="{{ $cert->verificationUrl() }}"
                                           target="_blank"
                                           class="px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[11px] font-semibold transition-colors">
                                            Verify
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                    No student certificates issued yet. When students complete 100% of curriculum lessons, issued credentials will appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($issued->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $issued->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Certificate Modal -->
    <div id="createCertModal" class="modal-overlay" role="dialog" aria-modal="true" onclick="if(event.target===this)closeModal('createCertModal')">
        <div class="panel panel-md space-y-5 max-w-xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">New Certificate Template</h3>
                    <p class="text-xs text-slate-500">Choose from Figma Dev Mode designs (3, 7, 8, 9) or classic layouts.</p>
                </div>
                <button type="button" onclick="closeModal('createCertModal')" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.certificates.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Template Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Heritage Grand Diploma" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Design Style *</label>
                    <select name="template_type" id="create_template_type" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="template_3">Figma 3: Heritage Grand Achievement (Guilloché & Gold Ribbon Seal)</option>
                        <option value="template_7">Figma 7: Contemporary Prestige Award (Wave Header & Watermark)</option>
                        <option value="template_8">Figma 8: Executive Diagonal Monogram (Right Rosette Column & Starburst)</option>
                        <option value="template_9">Figma 9: Royal Minimalist Distinction (Lattice Column & Brand Crest)</option>
                        <option value="classic">Legacy Classic Academic</option>
                        <option value="modern">Legacy Modern Minimalist</option>
                        <option value="elegant">Legacy Elegant Executive</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signer Name *</label>
                        <input type="text" name="signer_name" required value="Dr. Alexander Wright" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signature Title *</label>
                        <input type="text" name="signature_title" required value="Program Director & Dean" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Award Subtitle Text</label>
                    <textarea name="subtitle" rows="2" class="w-full p-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 text-xs">This certificate acknowledges your outstanding dedication, mastery, and distinguished accomplishment in successfully completing the comprehensive curriculum.</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-slate-500 text-[11px] mb-1">Primary Color</label>
                        <input type="text" name="border_color" value="#1a1815" class="w-full px-2 py-1 text-xs font-mono border border-slate-200 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 text-[11px] mb-1">Background Color</label>
                        <input type="text" name="bg_color" value="#ffffff" class="w-full px-2 py-1 text-xs font-mono border border-slate-200 focus:outline-none">
                    </div>
                </div>

                <!-- QR Toggle -->
                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_qr_code" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-semibold text-slate-700">Include Cryptographic QR Verification Code</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('createCertModal')" class="action-btn action-btn-ghost h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Certificate Modal -->
    <div id="editCertModal" class="modal-overlay" role="dialog" aria-modal="true" onclick="if(event.target===this)closeModal('editCertModal')">
        <div class="panel panel-md space-y-5 max-w-xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Edit Certificate Template</h3>
                    <p class="text-xs text-slate-500">Update style, signatures, and titles.</p>
                </div>
                <button type="button" onclick="closeModal('editCertModal')" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form id="editTemplateForm" method="POST" action="" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Template Title *</label>
                    <input type="text" id="edit_title" name="title" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Design Style *</label>
                    <select id="edit_template_type" name="template_type" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="template_3">Figma 3: Heritage Grand Achievement (Guilloché & Gold Ribbon Seal)</option>
                        <option value="template_7">Figma 7: Contemporary Prestige Award (Wave Header & Watermark)</option>
                        <option value="template_8">Figma 8: Executive Diagonal Monogram (Right Rosette Column & Starburst)</option>
                        <option value="template_9">Figma 9: Royal Minimalist Distinction (Lattice Column & Brand Crest)</option>
                        <option value="classic">Legacy Classic Academic</option>
                        <option value="modern">Legacy Modern Minimalist</option>
                        <option value="elegant">Legacy Elegant Executive</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signer Name *</label>
                        <input type="text" id="edit_signer_name" name="signer_name" required class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signature Title *</label>
                        <input type="text" id="edit_signature_title" name="signature_title" required class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Award Subtitle Text</label>
                    <textarea id="edit_subtitle" name="subtitle" rows="2" class="w-full p-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 text-xs"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-slate-500 text-[11px] mb-1">Primary Color</label>
                        <input type="text" id="edit_border_color" name="border_color" value="#1a1815" class="w-full px-2 py-1 text-xs font-mono border border-slate-200 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-500 text-[11px] mb-1">Background Color</label>
                        <input type="text" id="edit_bg_color" name="bg_color" value="#ffffff" class="w-full px-2 py-1 text-xs font-mono border border-slate-200 focus:outline-none">
                    </div>
                </div>

                <!-- QR Toggle -->
                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="edit_show_qr_code" name="show_qr_code" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-semibold text-slate-700">Include Cryptographic QR Verification Code</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editCertModal')" class="action-btn action-btn-ghost h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Preview Modal -->
    <div id="previewCertModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white max-w-5xl w-full border border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 my-6">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="font-bold text-slate-900 text-sm" id="preview-modal-title">Live Certificate Preview</h3>
                </div>

                <div class="flex items-center gap-2.5">
                    <a id="preview-fullscreen-link" href="#" target="_blank" class="action-btn action-btn-primary h-8 px-3.5 text-xs font-semibold">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Full A4 Printable View</span>
                    </a>
                    <button type="button" onclick="document.getElementById('previewCertModal').classList.add('hidden')" class="action-btn action-btn-ghost h-8 px-3 text-xs text-slate-500 hover:text-slate-900 cursor-pointer">
                        Close &times;
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-10 bg-slate-200/70 flex items-center justify-center overflow-x-auto">
                <iframe id="preview-live-iframe" src="" class="w-full max-w-4xl aspect-[1.414/1] border border-slate-300 shadow-2xl bg-white rounded-none"></iframe>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createCertModal').classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        function openEditModal(template) {
            const form = document.getElementById('editTemplateForm');
            form.action = '/admin/certificate-templates/' + template.id;

            document.getElementById('edit_title').value = template.title || '';
            document.getElementById('edit_template_type').value = template.template_type || 'template_3';
            document.getElementById('edit_signer_name').value = template.signer_name || '';
            document.getElementById('edit_signature_title').value = template.signature_title || '';
            document.getElementById('edit_subtitle').value = template.subtitle || '';
            document.getElementById('edit_border_color').value = template.border_color || '#1a1815';
            document.getElementById('edit_bg_color').value = template.bg_color || '#ffffff';

            const qrCheckbox = document.getElementById('edit_show_qr_code');
            if (qrCheckbox) {
                qrCheckbox.checked = template.show_qr_code !== false;
            }

            document.getElementById('editCertModal').classList.add('open');
        }

        function openPreviewModal(template) {
            const title = template.title || 'Certificate Preview';
            document.getElementById('preview-modal-title').textContent = 'Live Preview: ' + title;
            const url = '/admin/certificate-templates/' + template.id + '/preview';
            document.getElementById('preview-fullscreen-link').href = url;
            document.getElementById('preview-live-iframe').src = url;

            document.getElementById('previewCertModal').classList.remove('hidden');
        }
    </script>

</x-layouts.dashboard>
