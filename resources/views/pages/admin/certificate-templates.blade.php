<x-layouts.dashboard title="Certificate Templates - Mono LMS" active="certificates" userType="admin">

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
        .gold-foil-text {
            background: linear-gradient(135deg, #996515 0%, #d4af37 40%, #854d0e 80%, #b48608 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
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
                    <span class="text-[11px] text-emerald-600/80 font-medium font-mono uppercase">{{ $defaultTemplate?->template_type ?? 'classic' }} layout</span>
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
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Design, customize, and manage executive diplomas & accredited certificates automatically awarded upon graduation.</p>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($templates as $template)
                @php
                    $style = $template->template_type ?? 'classic';
                    $primaryColor = $template->border_color ?: '#344c3d';
                    $accentColor = $template->accent_color ?: '#738a6e';
                    $bgColor = $template->bg_color ?: ($style === 'modern' ? '#ffffff' : '#fdfbf7');
                @endphp
                <div class="bg-white border border-slate-200 rounded-none overflow-hidden shadow-xs flex flex-col justify-between hover:border-slate-400 transition-all group">
                    <!-- Certificate Miniature Graphical Canvas -->
                    <div class="p-5 bg-slate-100/70 border-b border-slate-200 flex items-center justify-center">

                        @if ($style === 'modern')
                            <!-- Modern Minimalist Miniature -->
                            <div class="relative w-full aspect-[16/11] p-3.5 rounded-none shadow-md overflow-hidden flex flex-col justify-between text-left select-none border border-slate-200 group-hover:shadow-lg transition-shadow"
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
                                    <h4 class="font-modern text-[10px] font-black uppercase text-slate-900 mt-1 line-clamp-1">{{ $template->title }}</h4>
                                </div>

                                <div class="pl-2 my-auto py-1">
                                    <span class="font-mono text-[7px] text-slate-400 uppercase font-semibold">Conferred upon</span>
                                    <h5 class="font-modern text-[12px] font-extrabold tracking-tight" style="color: {{ $primaryColor }};">Alexandre Montgomery</h5>
                                    <p class="text-[7px] text-slate-500 font-modern line-clamp-1 mt-0.5">{{ $template->subtitle ?: 'Mastery & Practical System Engineering' }}</p>
                                </div>

                                <div class="pl-2 pt-1 border-t border-slate-100 flex items-center justify-between text-[7px]">
                                    <div>
                                        <span class="font-modern font-bold text-slate-800 block text-[8px] truncate max-w-[100px]">{{ $template->signer_name }}</span>
                                        <span class="font-mono text-slate-400 text-[6px] truncate block max-w-[100px]">{{ $template->signature_title }}</span>
                                    </div>
                                    <div class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono text-[7px] font-bold text-slate-700 flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                        <span>VERIFIED</span>
                                    </div>
                                </div>
                            </div>

                        @elseif ($style === 'elegant')
                            <!-- Elegant Royal Executive Miniature -->
                            <div class="relative w-full aspect-[16/11] p-3.5 rounded-none shadow-md overflow-hidden flex flex-col justify-between text-center select-none group-hover:shadow-lg transition-shadow"
                                 style="background-color: {{ $bgColor }}; border: 6px solid #08131e;">
                                <div class="absolute inset-1 border pointer-events-none" style="border-color: #d4af37; opacity: 0.85;"></div>
                                <!-- Gold corner flourishes -->
                                <svg class="absolute top-1 left-1 w-4 h-4 text-[#d4af37]" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                <svg class="absolute top-1 right-1 w-4 h-4 text-[#d4af37] transform rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                <svg class="absolute bottom-1 left-1 w-4 h-4 text-[#d4af37] transform -rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                                <svg class="absolute bottom-1 right-1 w-4 h-4 text-[#d4af37] transform rotate-180" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>

                                <div class="relative z-10 pt-1">
                                    <span class="font-cinzel text-[7px] font-bold uppercase tracking-[0.25em] text-[#854d0e] block">ROYAL FACULTY SENATE</span>
                                    <h4 class="font-cinzel text-[10px] font-black uppercase text-slate-900 mt-0.5 line-clamp-1">{{ $template->title }}</h4>
                                </div>

                                <div class="relative z-10 my-auto py-1">
                                    <span class="font-cormorant italic text-[8px] text-slate-500 block">Conferred With Highest Honor</span>
                                    <p class="font-playfair text-[12px] font-black text-[#08131e] border-b border-[#d4af37] inline-block px-3 pb-0.5 mt-0.5">Alexandre Montgomery</p>
                                    <p class="text-[7px] text-slate-600 font-serif mt-0.5 line-clamp-1 max-w-[190px] mx-auto">{{ $template->subtitle ?: 'Executive Masterclass Curriculum' }}</p>
                                </div>

                                <div class="relative z-10 pt-1 border-t border-slate-200/90 flex items-end justify-between text-[7px]">
                                    <div class="text-left w-16">
                                        <span class="font-signature-alt text-xs text-slate-900 block select-none leading-none truncate">{{ $template->signer_name }}</span>
                                        <span class="text-slate-400 text-[6px] truncate block">{{ $template->signature_title }}</span>
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
                            <!-- Classic Academic Diploma Miniature -->
                            <div class="relative w-full aspect-[16/11] p-3.5 rounded-none shadow-md overflow-hidden flex flex-col justify-between text-center select-none group-hover:shadow-lg transition-shadow"
                                 style="background-color: {{ $bgColor }}; border: 5px double {{ $primaryColor }};">
                                <div class="absolute inset-1 border border-slate-300/70 pointer-events-none"></div>
                                <svg class="absolute top-1 left-1 w-5 h-5 pointer-events-none" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                <svg class="absolute top-1 right-1 w-5 h-5 pointer-events-none transform rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                <svg class="absolute bottom-1 left-1 w-5 h-5 pointer-events-none transform -rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                                <svg class="absolute bottom-1 right-1 w-5 h-5 pointer-events-none transform rotate-180" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>

                                <div class="relative z-10 pt-1">
                                    <span class="font-cinzel text-[7px] font-bold uppercase tracking-[0.2em] text-slate-500 block leading-tight">MONO LMS SENATE</span>
                                    <h4 class="font-cinzel text-[10px] font-black uppercase text-slate-900 mt-0.5 line-clamp-1" style="color: {{ $primaryColor }};">{{ $template->title }}</h4>
                                </div>

                                <div class="relative z-10 my-auto py-1">
                                    <span class="font-playfair italic text-[8px] text-slate-400 block">This is proudly awarded to</span>
                                    <p class="font-playfair text-[12px] font-extrabold text-slate-900 border-b border-amber-600/40 inline-block px-3 pb-0.5 mt-0.5">Alexandre Montgomery</p>
                                    <p class="text-[7px] text-slate-500 font-sans mt-0.5 line-clamp-1 max-w-[190px] mx-auto">{{ $template->subtitle ?: 'Mastery in Full-Stack Software Engineering' }}</p>
                                </div>

                                <div class="relative z-10 pt-1 border-t border-slate-200/80 flex items-end justify-between text-[7px]">
                                    <div class="text-left w-16">
                                        <span class="font-signature text-xs text-slate-800 block select-none leading-none truncate">{{ $template->signer_name }}</span>
                                        <span class="text-slate-400 text-[6px] truncate block">{{ $template->signature_title }}</span>
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
                                        {{ ucfirst($template->template_type) }} Style
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
                    <p class="text-xs text-slate-500">Configure layout style, signatures, and palette for diplomas.</p>
                </div>
                <button type="button" onclick="closeModal('createCertModal')" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.certificates.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Template Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Masterclass Executive Diploma" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Layout Style *</label>
                    <select name="template_type" id="create_template_type" onchange="applyPresetPalette('create', this.value)" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="classic">Classic Academic (Ivy League / Oxford)</option>
                        <option value="modern">Modern Minimalist (Silicon Valley / Tech Executive)</option>
                        <option value="elegant">Elegant Executive (Royal Navy / Gold Foil)</option>
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
                    <textarea name="subtitle" rows="2" placeholder="For successfully mastering the curriculum with academic excellence..." class="w-full p-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 text-xs">For successfully completing the comprehensive curriculum, demonstrating practical problem-solving competence, and fulfilling all graduation assessment requirements.</textarea>
                </div>

                <!-- Palette Colors -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-slate-700">Color Palette Customization</span>
                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="setPalette('create', '#344c3d', '#738a6e', '#fdfbf7')" class="text-[10px] font-medium text-emerald-700 hover:underline">Forest</button>
                            <span class="text-slate-300">•</span>
                            <button type="button" onclick="setPalette('create', '#0284c7', '#38bdf8', '#ffffff')" class="text-[10px] font-medium text-sky-700 hover:underline">Modern</button>
                            <span class="text-slate-300">•</span>
                            <button type="button" onclick="setPalette('create', '#08131e', '#d4af37', '#fdfbf7')" class="text-[10px] font-medium text-amber-700 hover:underline">Royal Gold</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-medium text-slate-500 text-[11px] mb-1">Border / Primary</label>
                            <div class="flex items-center border border-slate-200 bg-white">
                                <input type="color" id="create_border_picker" value="#344c3d" oninput="document.getElementById('create_border_color').value=this.value" class="w-8 h-8 p-1 border-0 bg-transparent cursor-pointer">
                                <input type="text" id="create_border_color" name="border_color" value="#344c3d" class="w-full px-2 py-1 text-xs font-mono border-0 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 text-[11px] mb-1">Accent Color</label>
                            <div class="flex items-center border border-slate-200 bg-white">
                                <input type="color" id="create_accent_picker" value="#738a6e" oninput="document.getElementById('create_accent_color').value=this.value" class="w-8 h-8 p-1 border-0 bg-transparent cursor-pointer">
                                <input type="text" id="create_accent_color" name="accent_color" value="#738a6e" class="w-full px-2 py-1 text-xs font-mono border-0 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 text-[11px] mb-1">Background</label>
                            <div class="flex items-center border border-slate-200 bg-white">
                                <input type="color" id="create_bg_picker" value="#fdfbf7" oninput="document.getElementById('create_bg_color').value=this.value" class="w-8 h-8 p-1 border-0 bg-transparent cursor-pointer">
                                <input type="text" id="create_bg_color" name="bg_color" value="#fdfbf7" class="w-full px-2 py-1 text-xs font-mono border-0 focus:outline-none">
                            </div>
                        </div>
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
                    <p class="text-xs text-slate-500">Update style, signatures, and colors.</p>
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
                    <label class="block font-semibold text-slate-700 mb-1">Layout Style *</label>
                    <select id="edit_template_type" name="template_type" onchange="applyPresetPalette('edit', this.value)" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="classic">Classic Academic (Ivy League / Oxford)</option>
                        <option value="modern">Modern Minimalist (Silicon Valley / Tech Executive)</option>
                        <option value="elegant">Elegant Executive (Royal Navy / Gold Foil)</option>
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

                <!-- Palette Colors -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-slate-700">Color Palette Customization</span>
                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="setPalette('edit', '#344c3d', '#738a6e', '#fdfbf7')" class="text-[10px] font-medium text-emerald-700 hover:underline">Forest</button>
                            <span class="text-slate-300">•</span>
                            <button type="button" onclick="setPalette('edit', '#0284c7', '#38bdf8', '#ffffff')" class="text-[10px] font-medium text-sky-700 hover:underline">Modern</button>
                            <span class="text-slate-300">•</span>
                            <button type="button" onclick="setPalette('edit', '#08131e', '#d4af37', '#fdfbf7')" class="text-[10px] font-medium text-amber-700 hover:underline">Royal Gold</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-medium text-slate-500 text-[11px] mb-1">Border / Primary</label>
                            <div class="flex items-center border border-slate-200 bg-white">
                                <input type="color" id="edit_border_picker" value="#344c3d" oninput="document.getElementById('edit_border_color').value=this.value" class="w-8 h-8 p-1 border-0 bg-transparent cursor-pointer">
                                <input type="text" id="edit_border_color" name="border_color" class="w-full px-2 py-1 text-xs font-mono border-0 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 text-[11px] mb-1">Accent Color</label>
                            <div class="flex items-center border border-slate-200 bg-white">
                                <input type="color" id="edit_accent_picker" value="#738a6e" oninput="document.getElementById('edit_accent_color').value=this.value" class="w-8 h-8 p-1 border-0 bg-transparent cursor-pointer">
                                <input type="text" id="edit_accent_color" name="accent_color" class="w-full px-2 py-1 text-xs font-mono border-0 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-500 text-[11px] mb-1">Background</label>
                            <div class="flex items-center border border-slate-200 bg-white">
                                <input type="color" id="edit_bg_picker" value="#fdfbf7" oninput="document.getElementById('edit_bg_color').value=this.value" class="w-8 h-8 p-1 border-0 bg-transparent cursor-pointer">
                                <input type="text" id="edit_bg_color" name="bg_color" class="w-full px-2 py-1 text-xs font-mono border-0 focus:outline-none">
                            </div>
                        </div>
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
            <!-- Modal Header Toolbar -->
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
                    <button type="button" onclick="document.getElementById('previewCertModal').classList.add('hidden')" class="action-btn action-btn-ghost h-8 px-3 text-xs text-slate-500 hover:text-slate-900">
                        Close &times;
                    </button>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="p-6 sm:p-10 bg-slate-200/70 flex items-center justify-center overflow-x-auto">

                <!-- 1. MODERN PREVIEW FRAME -->
                <div id="modal-frame-modern" class="hidden w-full max-w-3xl aspect-[1.414/1] bg-white p-8 sm:p-12 shadow-2xl relative overflow-hidden flex flex-col justify-between text-left font-modern select-none">
                    <div class="absolute top-0 left-0 right-0 h-2.5" id="modern-top-bar" style="background: linear-gradient(90deg, #0284c7 0%, #38bdf8 70%, #d4af37 100%);"></div>
                    <div class="absolute top-2.5 left-0 bottom-0 w-2.5" id="modern-left-bar" style="background: #0284c7;"></div>
                    <span class="absolute top-4 right-4 font-mono text-xs text-slate-300 select-none">+</span>
                    <span class="absolute bottom-4 right-4 font-mono text-xs text-slate-300 select-none">+</span>

                    <div class="pl-4 pt-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" id="modern-dot" style="background: #0284c7;"></span>
                            <span class="font-mono text-[9px] font-bold uppercase tracking-widest text-slate-400">MONO LMS // ACADEMIC REGISTRY</span>
                        </div>
                        <h2 class="font-modern text-xl sm:text-2xl font-black uppercase text-slate-900 mt-1" id="modern-preview-title">Certificate Title</h2>
                    </div>

                    <div class="pl-4 my-auto py-2 space-y-1.5">
                        <span class="font-mono text-[10px] text-slate-400 uppercase font-semibold">This accredited credential is conferred upon</span>
                        <h3 class="font-modern text-2xl sm:text-3xl font-extrabold tracking-tight" id="modern-student-name" style="color: #0284c7;">Alexandre Montgomery</h3>
                        <p class="text-xs text-slate-600 max-w-lg leading-relaxed font-sans" id="modern-preview-subtitle">for completing the comprehensive curriculum with academic distinction.</p>
                        <div class="inline-block bg-slate-50 border border-slate-200 px-3 py-1 rounded mt-1 font-bold text-xs text-slate-800">
                            Full-Stack Web Development & System Architecture
                        </div>
                    </div>

                    <div class="pl-4 pt-4 border-t border-slate-100 flex items-end justify-between">
                        <div class="flex items-center gap-8">
                            <div>
                                <div class="font-signature text-2xl text-slate-800 select-none h-7">Mentor Signature</div>
                                <div class="border-t border-slate-300 pt-0.5 mt-0.5 w-36">
                                    <span class="font-bold text-slate-900 text-[11px] block">Dr. Julian Vance</span>
                                    <span class="text-slate-400 text-[9px] uppercase font-mono">Lead Instructor</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-signature text-2xl text-slate-800 select-none h-7" id="modern-signer-sig">Dr. Alexander Wright</div>
                                <div class="border-t border-slate-300 pt-0.5 mt-0.5 w-36">
                                    <span class="font-bold text-slate-900 text-[11px] block" id="modern-signer-name">Dr. Alexander Wright</span>
                                    <span class="text-slate-400 text-[9px] uppercase font-mono" id="modern-signer-title">Program Director</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 bg-slate-50 p-2 rounded border border-slate-200 text-[9px] font-mono">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-bold text-slate-800">SECURE VERIFIED</span>
                        </div>
                    </div>
                </div>

                <!-- 2. ELEGANT PREVIEW FRAME -->
                <div id="modal-frame-elegant" class="hidden w-full max-w-3xl aspect-[1.414/1] bg-[#fdfbf7] p-8 sm:p-12 shadow-2xl relative overflow-hidden flex flex-col justify-between text-center font-cormorant select-none border-[10px] border-[#08131e]">
                    <div class="absolute inset-2 border-2 pointer-events-none" style="border-color: #d4af37; opacity: 0.9;"></div>
                    <svg class="absolute top-3 left-3 w-8 h-8 text-[#d4af37]" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                    <svg class="absolute top-3 right-3 w-8 h-8 text-[#d4af37] transform rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                    <svg class="absolute bottom-3 left-3 w-8 h-8 text-[#d4af37] transform -rotate-90" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>
                    <svg class="absolute bottom-3 right-3 w-8 h-8 text-[#d4af37] transform rotate-180" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/></svg>

                    <div class="relative z-10 pt-1">
                        <span class="font-cinzel text-[9px] font-bold uppercase tracking-[0.3em] text-[#854d0e] block">THE SENATE OF MONO LMS ACADEMY</span>
                        <h2 class="font-cinzel text-xl sm:text-2xl font-black uppercase text-slate-900 mt-1" id="elegant-preview-title">Diploma of Executive Excellence</h2>
                    </div>

                    <div class="relative z-10 my-auto py-2">
                        <p class="font-cormorant text-xs italic text-slate-500">This distinguished credential proudly certifies that</p>
                        <div class="inline-block border-b-2 border-[#d4af37] px-6 pb-0.5 mt-1">
                            <h3 class="font-playfair text-2xl sm:text-3xl font-extrabold text-[#08131e]">Alexandre Montgomery</h3>
                        </div>
                        <p class="text-xs text-slate-600 max-w-md mx-auto mt-2 leading-relaxed font-sans" id="elegant-preview-subtitle">has satisfied all educational requirements and masterclass milestones.</p>
                        <h4 class="font-cinzel text-sm sm:text-base font-bold mt-1 text-[#08131e]" id="elegant-course-title">Full-Stack Software Engineering Masterclass</h4>
                    </div>

                    <div class="relative z-10 pt-3 border-t border-slate-200/90 flex items-end justify-between">
                        <div class="text-center w-36">
                            <div class="font-signature text-2xl text-[#08131e] h-7">Dr. Julian Vance</div>
                            <div class="border-t border-slate-400 pt-0.5 mt-0.5">
                                <span class="font-bold text-slate-900 text-[11px] block">Dr. Julian Vance</span>
                                <span class="text-slate-500 text-[9px] uppercase font-sans">Lead Faculty</span>
                            </div>
                        </div>

                        <div class="w-14 h-14 rounded-full gold-foil-gradient border-2 border-amber-600 shadow-md flex flex-col items-center justify-center text-amber-950 font-bold">
                            <span class="text-sm leading-none">★</span>
                            <span class="text-[6px] font-black uppercase tracking-widest mt-0.5">ROYAL SEAL</span>
                        </div>

                        <div class="text-center w-36">
                            <div class="font-signature-alt text-2xl text-[#08131e] h-7" id="elegant-signer-sig">Dr. Alexander Wright</div>
                            <div class="border-t border-slate-400 pt-0.5 mt-0.5">
                                <span class="font-bold text-slate-900 text-[11px] block" id="elegant-signer-name">Dr. Alexander Wright</span>
                                <span class="text-slate-500 text-[9px] uppercase font-sans" id="elegant-signer-title">Dean of Faculty</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. CLASSIC PREVIEW FRAME -->
                <div id="modal-frame-classic" class="w-full max-w-3xl aspect-[1.414/1] bg-[#fdfbf7] p-8 sm:p-12 shadow-2xl relative overflow-hidden flex flex-col justify-between text-center select-none"
                     style="border: 8px double #344c3d;">
                    <div class="absolute inset-2 border border-slate-300 pointer-events-none"></div>
                    <svg class="absolute top-3 left-3 w-8 h-8 pointer-events-none" id="classic-corner-1" style="color: #344c3d;" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                    <svg class="absolute top-3 right-3 w-8 h-8 pointer-events-none transform rotate-90" id="classic-corner-2" style="color: #344c3d;" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                    <svg class="absolute bottom-3 left-3 w-8 h-8 pointer-events-none transform -rotate-90" id="classic-corner-3" style="color: #344c3d;" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>
                    <svg class="absolute bottom-3 right-3 w-8 h-8 pointer-events-none transform rotate-180" id="classic-corner-4" style="color: #344c3d;" viewBox="0 0 100 100" fill="currentColor"><path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/></svg>

                    <div class="relative z-10 pt-1">
                        <span class="font-cinzel text-[9px] font-bold uppercase tracking-[0.25em] text-slate-500 block">MONO LMS ACADEMIC SENATE</span>
                        <h2 class="font-cinzel text-xl sm:text-2xl font-black uppercase text-slate-900 mt-1" id="classic-preview-title">Certificate of Achievement</h2>
                    </div>

                    <div class="relative z-10 my-auto py-2">
                        <p class="font-playfair italic text-xs text-slate-400">This proudly certifies that</p>
                        <div class="inline-block border-b-2 border-slate-300 px-6 pb-0.5 mt-1" id="classic-underline">
                            <h3 class="font-playfair text-2xl sm:text-3xl font-extrabold text-slate-900">Alexandre Montgomery</h3>
                        </div>
                        <p class="text-xs text-slate-600 max-w-md mx-auto mt-2 leading-relaxed font-sans" id="classic-preview-subtitle">has successfully fulfilled all curriculum requirements with distinction.</p>
                        <h4 class="font-cinzel text-sm sm:text-base font-bold mt-1" id="classic-course-title" style="color: #344c3d;">Full-Stack Web Development Masterclass</h4>
                    </div>

                    <div class="relative z-10 pt-3 border-t border-slate-200/90 flex items-end justify-between">
                        <div class="text-center w-36">
                            <div class="font-signature text-2xl text-slate-800 h-7">Dr. Julian Vance</div>
                            <div class="border-t border-slate-400 pt-0.5 mt-0.5">
                                <span class="font-bold text-slate-900 text-[11px] block">Dr. Julian Vance</span>
                                <span class="text-slate-500 text-[9px] uppercase font-sans">Instructor</span>
                            </div>
                        </div>

                        <div class="w-14 h-14 rounded-full gold-foil-gradient border-2 border-amber-600 shadow-md flex flex-col items-center justify-center text-amber-950 font-bold">
                            <span class="text-sm leading-none">★</span>
                            <span class="text-[6px] font-black uppercase tracking-widest mt-0.5">ACCREDITED</span>
                        </div>

                        <div class="text-center w-36">
                            <div class="font-signature text-2xl text-slate-800 h-7" id="classic-signer-sig">Dr. Alexander Wright</div>
                            <div class="border-t border-slate-400 pt-0.5 mt-0.5">
                                <span class="font-bold text-slate-900 text-[11px] block" id="classic-signer-name">Dr. Alexander Wright</span>
                                <span class="text-slate-500 text-[9px] uppercase font-sans" id="classic-signer-title">Dean</span>
                            </div>
                        </div>
                    </div>
                </div>

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

        function setPalette(prefix, border, accent, bg) {
            document.getElementById(prefix + '_border_color').value = border;
            document.getElementById(prefix + '_border_picker').value = border;
            document.getElementById(prefix + '_accent_color').value = accent;
            document.getElementById(prefix + '_accent_picker').value = accent;
            document.getElementById(prefix + '_bg_color').value = bg;
            document.getElementById(prefix + '_bg_picker').value = bg;
        }

        function applyPresetPalette(prefix, style) {
            if (style === 'modern') {
                setPalette(prefix, '#0284c7', '#38bdf8', '#ffffff');
            } else if (style === 'elegant') {
                setPalette(prefix, '#08131e', '#d4af37', '#fdfbf7');
            } else {
                setPalette(prefix, '#344c3d', '#738a6e', '#fdfbf7');
            }
        }

        function openEditModal(template) {
            const form = document.getElementById('editTemplateForm');
            form.action = '/admin/certificate-templates/' + template.id;

            document.getElementById('edit_title').value = template.title || '';
            document.getElementById('edit_template_type').value = template.template_type || 'classic';
            document.getElementById('edit_signer_name').value = template.signer_name || '';
            document.getElementById('edit_signature_title').value = template.signature_title || '';
            document.getElementById('edit_subtitle').value = template.subtitle || '';

            const border = template.border_color || '#344c3d';
            const accent = template.accent_color || '#738a6e';
            const bg = template.bg_color || '#ffffff';

            setPalette('edit', border, accent, bg);

            const qrCheckbox = document.getElementById('edit_show_qr_code');
            if (qrCheckbox) {
                qrCheckbox.checked = template.show_qr_code !== false;
            }

            document.getElementById('editCertModal').classList.add('open');
        }

        function openPreviewModal(template) {
            const type = template.template_type || 'classic';
            const title = template.title || 'Certificate of Achievement';
            const subtitle = template.subtitle || 'for completing the comprehensive curriculum with academic distinction.';
            const signerName = template.signer_name || 'Dr. Alexander Wright';
            const signatureTitle = template.signature_title || 'Program Director';
            const border = template.border_color || '#344c3d';
            const accent = template.accent_color || '#738a6e';
            const bg = template.bg_color || '#ffffff';

            document.getElementById('preview-modal-title').textContent = 'Live Preview: ' + title;
            document.getElementById('preview-fullscreen-link').href = '/admin/certificate-templates/' + template.id + '/preview';

            // Hide all frames
            const modernFrame = document.getElementById('modal-frame-modern');
            const elegantFrame = document.getElementById('modal-frame-elegant');
            const classicFrame = document.getElementById('modal-frame-classic');

            modernFrame.classList.add('hidden');
            elegantFrame.classList.add('hidden');
            classicFrame.classList.add('hidden');

            if (type === 'modern') {
                modernFrame.classList.remove('hidden');
                modernFrame.style.backgroundColor = bg;
                document.getElementById('modern-preview-title').textContent = title;
                document.getElementById('modern-preview-subtitle').textContent = subtitle;
                document.getElementById('modern-signer-name').textContent = signerName;
                document.getElementById('modern-signer-sig').textContent = signerName;
                document.getElementById('modern-signer-title').textContent = signatureTitle;
                document.getElementById('modern-student-name').style.color = border;
                document.getElementById('modern-dot').style.backgroundColor = border;
                document.getElementById('modern-left-bar').style.backgroundColor = border;
                document.getElementById('modern-top-bar').style.background = `linear-gradient(90deg, ${border} 0%, ${accent} 70%, #d4af37 100%)`;
            } else if (type === 'elegant') {
                elegantFrame.classList.remove('hidden');
                elegantFrame.style.backgroundColor = bg !== '#ffffff' ? bg : '#fdfbf7';
                elegantFrame.style.borderColor = border;
                document.getElementById('elegant-preview-title').textContent = title;
                document.getElementById('elegant-preview-subtitle').textContent = subtitle;
                document.getElementById('elegant-signer-name').textContent = signerName;
                document.getElementById('elegant-signer-sig').textContent = signerName;
                document.getElementById('elegant-signer-title').textContent = signatureTitle;
                document.getElementById('elegant-course-title').style.color = border;
            } else {
                classicFrame.classList.remove('hidden');
                classicFrame.style.backgroundColor = bg !== '#ffffff' ? bg : '#fdfbf7';
                classicFrame.style.borderColor = border;
                document.getElementById('classic-preview-title').textContent = title;
                document.getElementById('classic-preview-title').style.color = border;
                document.getElementById('classic-preview-subtitle').textContent = subtitle;
                document.getElementById('classic-signer-name').textContent = signerName;
                document.getElementById('classic-signer-sig').textContent = signerName;
                document.getElementById('classic-signer-title').textContent = signatureTitle;
                document.getElementById('classic-course-title').style.color = border;
                document.getElementById('classic-underline').style.borderColor = border;
                ['classic-corner-1', 'classic-corner-2', 'classic-corner-3', 'classic-corner-4'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.color = border;
                });
            }

            document.getElementById('previewCertModal').classList.remove('hidden');
        }
    </script>

</x-layouts.dashboard>
