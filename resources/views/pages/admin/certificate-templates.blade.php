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
                    $style = $template->template_type ?? 'template_1';
                    $primaryColor = $template->border_color ?: '#1a1815';
                    $accentColor = $template->accent_color ?: '#835a2a';
                    $bgColor = $template->bg_color ?: '#ffffff';
                @endphp
                <div class="bg-white border border-slate-200 rounded-none overflow-hidden shadow-xs flex flex-col justify-between hover:border-slate-400 transition-all group">
                    <!-- Certificate Miniature Graphical Canvas -->
                    <div class="p-4 sm:p-5 bg-slate-100/70 border-b border-slate-200 flex items-center justify-center">

                            @if ($style === 'template_8')
                                <!-- Template 8 Miniature Thumbnail: Navy & Gold Geometric Frame - Certificate of Appreciation (proportional, mirrors the full print view) -->
                                <div class="@container relative w-full aspect-[16/11] rounded-none shadow-md overflow-hidden group-hover:shadow-lg transition-shadow border border-slate-200" style="--m8-navy: {{ $template->border_color ?: '#2e4a8f' }}; --m8-gold: {{ $template->accent_color ?: '#f2b824' }}; background: {{ $template->bg_color ?: '#ffffff' }};">
                                    <!-- Geometric corner frame (proportional, colors follow the template) -->
                                    <div class="absolute top-[4.5cqi] left-[4.5cqi] w-[48cqi] h-[1.6cqi] bg-[var(--m8-navy)]"></div>
                                    <div class="absolute top-[4.5cqi] left-[4.5cqi] w-[1.6cqi] h-[59.5cqi] bg-[var(--m8-navy)]"></div>
                                    <div class="absolute top-[4.5cqi] right-[4.5cqi] w-[1.6cqi] h-[16cqi] bg-[var(--m8-navy)]"></div>
                                    <div class="absolute bottom-[4.5cqi] left-[4.5cqi] w-[16cqi] h-[1.6cqi] bg-[var(--m8-navy)]"></div>
                                    <div class="absolute bottom-[4.5cqi] right-[4.5cqi] w-[50cqi] h-[1.6cqi] bg-[var(--m8-navy)]"></div>
                                    <div class="absolute bottom-[4.5cqi] right-[4.5cqi] w-[1.6cqi] h-[59.5cqi] bg-[var(--m8-navy)]"></div>

                                    <div class="absolute top-[3.4cqi] left-[3cqi] w-[58cqi] h-[0.45cqi] bg-[var(--m8-gold)]"></div>
                                    <div class="absolute top-[3cqi] left-[3.4cqi] w-[0.45cqi] h-[63cqi] bg-[var(--m8-gold)]"></div>
                                    <div class="absolute bottom-[3.4cqi] left-[2.5cqi] w-[22cqi] h-[0.45cqi] bg-[var(--m8-gold)]"></div>
                                    <div class="absolute bottom-[3.4cqi] right-[2.5cqi] w-[58cqi] h-[0.45cqi] bg-[var(--m8-gold)]"></div>
                                    <div class="absolute top-[3cqi] right-[3.4cqi] w-[0.45cqi] h-[63cqi] bg-[var(--m8-gold)]"></div>

                                    <div class="absolute top-[7.4cqi] left-[4.5cqi] w-[38cqi] h-[0.3cqi] bg-[var(--m8-navy)] opacity-60"></div>
                                    <div class="absolute top-[7.4cqi] left-[7.4cqi] w-[0.3cqi] h-[46cqi] bg-[var(--m8-navy)] opacity-60"></div>
                                    <div class="absolute bottom-[7.4cqi] right-[4.5cqi] w-[40cqi] h-[0.3cqi] bg-[var(--m8-navy)] opacity-60"></div>
                                    <div class="absolute bottom-[7.4cqi] right-[7.4cqi] w-[0.3cqi] h-[46cqi] bg-[var(--m8-navy)] opacity-60"></div>

                                    <!-- Award medal with star & ribbon tails (mirrors the print view badge) -->
                                    <div class="absolute left-[8cqi] top-[46%] -translate-y-1/2 w-[12cqi] h-[16cqi] z-10">
                                        <div class="absolute top-[6.5cqi] left-[2.4cqi] w-[3.2cqi] h-[8cqi] bg-[var(--m8-navy)]" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%);"></div>
                                        <div class="absolute top-[6.5cqi] right-[2.4cqi] w-[3.2cqi] h-[8cqi] bg-[var(--m8-navy)]" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%);"></div>
                                        <div class="absolute inset-x-0 top-0 mx-auto w-[12cqi] h-[12cqi] rounded-full border-[0.9cqi] border-[#e3b64d] bg-[var(--m8-navy)] flex flex-col items-center justify-center">
                                            <span class="text-[1.4cqi] leading-none text-[#f4d97c]">★</span>
                                            <span class="text-[2.4cqi] font-bold text-[var(--m8-gold)] leading-none mt-[0.3cqi]">{{ now()->format('Y') }}</span>
                                            <span class="text-[1.3cqi] font-semibold text-white tracking-[0.15em] leading-none mt-[0.4cqi]">AWARD</span>
                                        </div>
                                    </div>

                                    <!-- Heading & recipient content (proportional) -->
                                    <div class="relative z-10 h-full flex flex-col items-center pt-[10.5cqi] pb-[12cqi] px-[21cqi]">
                                        <div class="text-center">
                                            <div class="text-[4.6cqi] font-extrabold tracking-[0.18em] text-[var(--m8-navy)] uppercase leading-none">CERTIFICATE</div>
                                            <div class="flex items-center justify-center gap-[2cqi] mt-[1.2cqi]">
                                                <span class="w-[8cqi] h-[0.35cqi] bg-[var(--m8-navy)] opacity-40"></span>
                                                <span class="text-[1.5cqi] font-semibold tracking-[0.35em] text-[var(--m8-navy)] uppercase">OF APPRECIATION</span>
                                                <span class="w-[8cqi] h-[0.35cqi] bg-[var(--m8-navy)] opacity-40"></span>
                                            </div>
                                            <div class="w-[1.3cqi] h-[1.3cqi] bg-[var(--m8-gold)] rotate-45 mx-auto mt-[1.6cqi]"></div>
                                        </div>
                                        <div class="flex-1 w-full flex flex-col items-center justify-center text-center">
                                            <span class="text-[1.35cqi] font-semibold tracking-[0.3em] text-[var(--m8-navy)] uppercase block">PROUDLY PRESENTED TO</span>
                                            <span class="font-signature text-[5.8cqi] text-[var(--m8-navy)] leading-none block mt-[1.2cqi]">Alexandre Montgomery</span>
                                            <p class="text-[1.4cqi] text-[#6f7683] line-clamp-2 w-full mt-[1.6cqi] leading-relaxed">Outstanding dedication and successful completion of the comprehensive curriculum.</p>
                                        </div>
                                    </div>

                                    <!-- Date & signature values over rules (mirrors print view) -->
                                    <div class="absolute bottom-[3.5cqi] left-0 right-0 z-10 flex items-end justify-center gap-[9cqi]">
                                        <div class="text-center w-[16cqi]">
                                            <div class="text-[1.1cqi] text-[#6f7683] leading-none mb-[1cqi] whitespace-nowrap">{{ now()->format('d M Y') }}</div>
                                            <span class="block w-full border-t-[0.35cqi] border-[var(--m8-navy)] opacity-50 mx-auto leading-none">&nbsp;</span>
                                            <div class="text-[1.15cqi] font-bold tracking-[0.25em] text-[var(--m8-navy)] uppercase mt-[0.9cqi]">Date</div>
                                        </div>
                                        <div class="text-center w-[16cqi]">
                                            <div class="font-signature text-[2.4cqi] text-[var(--m8-navy)] leading-none mb-[0.6cqi] whitespace-nowrap">{{ $template->signer_name }}</div>
                                            <span class="block w-full border-t-[0.35cqi] border-[var(--m8-navy)] opacity-50 mx-auto leading-none">&nbsp;</span>
                                            <div class="text-[1.15cqi] font-bold tracking-[0.25em] text-[var(--m8-navy)] uppercase mt-[0.9cqi]">{{ $template->signature_title ?: 'SIGNATURE' }}</div>
                                        </div>
                                    </div>

                                    <!-- Verification QR (mirrors print view placement) -->
                                    <div class="absolute right-[8.5cqi] bottom-[13cqi] z-10 text-center">
                                        <div class="w-[7cqi] h-[7cqi] bg-white border-[0.3cqi] border-[var(--m8-navy)]/30 p-[0.6cqi] mx-auto">
                                            <div class="w-full h-full flex items-center justify-center" style="background-image: repeating-linear-gradient(0deg, #1f2937 0 20%, transparent 20% 40%), repeating-linear-gradient(90deg, #1f2937 0 20%, transparent 20% 40%); background-size: 0.9cqi 0.9cqi; opacity: 0.75;"></div>
                                        </div>
                                        <div class="mt-[0.6cqi] text-[0.85cqi] font-bold tracking-[0.14em] text-[var(--m8-navy)] uppercase whitespace-nowrap">Scan to Verify</div>
                                    </div>
                                </div>
                            @else

                            <!-- Template 1 Miniature Thumbnail: Top Gold Wave, Big Avatar Portrait & Rosette Seal (proportional to the 1060x750 print canvas) -->
                            <div class="@container relative w-full aspect-[16/11] rounded-none shadow-md overflow-hidden select-none group-hover:shadow-lg transition-shadow border border-[#e6e3dd]" style="background: {{ $template->bg_color ?: '#ffffff' }};">
                                <!-- Inset dashed border -->
                                <div class="absolute inset-[1.7cqi] border border-dashed border-[#e6e3dd] pointer-events-none"></div>

                                <!-- Top Wave Ribbon Graphic (hugs the top-left corner like the print view) -->
                                <div class="absolute top-0 left-0 w-[58cqi] h-[14.5cqi] pointer-events-none overflow-hidden -scale-x-100">
                                    <svg viewBox="0 0 400 120" class="w-full h-full" preserveAspectRatio="none">
                                        <path d="M 0,0 C 140,55 260,35 400,0 L 400,120 L 220,120 C 160,80 80,40 0,0 Z" fill="url(#miniWaveGold-{{ $template->id }})" opacity="0.9" />
                                        <defs>
                                            <linearGradient id="miniWaveGold-{{ $template->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#b8862d" />
                                                <stop offset="50%" stop-color="#ffd57a" />
                                                <stop offset="100%" stop-color="#9a6c1e" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>

                                <!-- Left Heading & Info (matches real layout: left column at top ~15.5cqi) -->
                                <div class="absolute top-[15.5cqi] left-[8.7cqi] w-[43.5cqi] z-10">
                                    <span class="font-baskerville text-[1.95cqi] text-[#4a4744] uppercase font-semibold block leading-tight">CERTIFICATE OF</span>
                                    <h4 class="font-baskerville text-[4.1cqi] font-bold text-slate-900 uppercase leading-none mt-[0.5cqi]">ACHIEVEMENT</h4>
                                    <span class="font-['Inter'] text-[1.05cqi] uppercase tracking-[0.2em] text-[#9b9690] font-medium block mt-[3.4cqi]">THIS IS PROUDLY PRESENTED TO</span>
                                    <h5 class="font-signature text-[4.7cqi] text-[#7c5121] leading-none mt-[0.7cqi] whitespace-nowrap">Alexandre Montgomery</h5>
                                    <div class="w-[38.5cqi] h-px bg-[#ebe8e2] mt-[0.6cqi]"></div>
                                    <p class="text-[1.3cqi] text-[#6e6a65] line-clamp-3 mt-[2cqi] leading-relaxed">Executive Full-Stack Engineering & System Architecture, showcasing commitment to excellence and distinguished honors.</p>
                                </div>

                                <!-- Big Portrait Circle with Dashed Ring (right side, like the print view) -->
                                <div class="absolute top-[17.4cqi] right-[13.4cqi] w-[29.8cqi] h-[36cqi] z-[8]">
                                    <div class="absolute left-[-0.75cqi] bottom-[-1cqi] w-[31.3cqi] h-[31.3cqi] rounded-full border border-dashed border-[#e6e1d8]"></div>
                                    <div class="absolute left-[0.4cqi] bottom-[0.1cqi] w-[29.1cqi] h-[29.1cqi] rounded-full" style="background: radial-gradient(circle at 50% 42%, #f5ede4 0%, #ede3d7 70%, #e5d9cb 100%);"></div>
                                    <div class="absolute left-[0.4cqi] bottom-[0.1cqi] w-[29.1cqi] h-[35.1cqi] overflow-hidden rounded-t-[13.2cqi] rounded-b-[14.5cqi]">
                                        <img src="{{ \App\Models\Student::whereNotNull('avatar')->first()?->avatarOrDefault() ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80' }}" class="w-full h-full object-cover object-top" alt="Student">
                                    </div>

                                    <!-- Scalloped Rosette Seal overlapping the portrait's top-left -->
                                    <div class="absolute top-[-1.1cqi] left-[-5.9cqi] w-[18.5cqi] h-[18.5cqi] z-20 drop-shadow-lg">
                                        <div class="w-full h-full rounded-full border-[1.1cqi] border-[#caa04c] bg-[#f6ecd8] flex items-center justify-center">
                                            <div class="w-[76%] h-[76%] rounded-full border border-[#d5a44b] bg-[#fdf8ec] flex flex-col items-center justify-center text-center">
                                                <span class="text-[0.9cqi] text-[#b98a21] leading-none">★</span>
                                                <span class="text-[1.7cqi] font-bold text-[#8a5c17] uppercase leading-none mt-[0.25cqi]" style="font-family: 'Playfair Display', Georgia, serif;">BEST</span>
                                                <span class="text-[1.55cqi] font-bold text-[#8a5c17] uppercase leading-none mt-[0.2cqi]" style="font-family: 'Playfair Display', Georgia, serif;">AWARD</span>
                                                <span class="text-[0.9cqi] text-[#b98a21] leading-none mt-[0.25cqi]">★</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom-Left Signature Block -->
                                <div class="absolute left-[8.7cqi] bottom-[5.5cqi] z-10 text-left">
                                    <span class="font-['Sacramento'] text-[3.2cqi] text-[#1d1b19] leading-none block -rotate-3 whitespace-nowrap">{{ $template->signer_name }}</span>
                                    <div class="w-[12.3cqi] h-px bg-[#dcd8d0] mt-[0.4cqi]"></div>
                                    <span class="text-[0.9cqi] text-[#5a5652] uppercase tracking-[0.1em] font-semibold block mt-[0.4cqi]">{{ $template->signature_title }}</span>
                                </div>

                                <!-- Bottom-Right QR & Presented Date -->
                                <div class="absolute right-[8.1cqi] bottom-[5.5cqi] z-10 flex flex-col items-end text-right">
                                    <div class="w-[5.3cqi] h-[5.3cqi] bg-slate-900 rounded-[0.3cqi] p-[0.4cqi] flex items-center justify-center">
                                        <span class="text-[1.3cqi] text-white font-mono">QR</span>
                                    </div>
                                    <span class="text-[0.8cqi] text-[#8d8882] uppercase tracking-[0.08em] leading-tight mt-[1.2cqi]">Presented On</span>
                                    <span class="text-[1.35cqi] text-[#2c2a28] font-medium leading-none mt-[0.3cqi]">10 Nov 2026</span>
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
                        <option value="template_1" selected>Template 1: Contemporary Prestige Award (Gold Ribbon Wave & Avatar Portrait)</option>
                        <option value="template_8">Template 8: Certificate of Appreciation (Navy & Gold Geometric Frame)</option>
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
                        <option value="template_1" selected>Template 1: Contemporary Prestige Award (Gold Ribbon Wave & Avatar Portrait)</option>
                        <option value="template_8">Template 8: Certificate of Appreciation (Navy & Gold Geometric Frame)</option>
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
    <div id="previewCertModal" class="modal-overlay !p-2 sm:!p-6" role="dialog" aria-modal="true" onclick="if(event.target===this)closePreviewModal()">
        <div id="previewModalBox" style="width: min(94vw, 1120px); height: min(calc(94vw / 1.4141), calc(100vh - 60px)); aspect-ratio: 1.4141 / 1; position: relative; margin: auto; background: #ffffff; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45); border-radius: 4px; overflow: hidden; display: flex; flex-direction: column;">
            <!-- Floating controls top-right -->
            <div class="absolute top-3 right-3 z-30 flex items-center gap-2">
                <a id="preview-fullscreen-link" href="#" target="_blank" class="px-3 py-1.5 rounded-full bg-neutral-900/85 hover:bg-neutral-900 text-neutral-100 text-xs font-semibold backdrop-blur-md shadow inline-flex items-center gap-1.5 transition">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Full A4 Tab</span>
                </a>
                <button type="button" onclick="closePreviewModal()" class="w-8 h-8 rounded-full bg-neutral-900/85 hover:bg-neutral-900 text-white text-base font-bold backdrop-blur-md shadow flex items-center justify-center cursor-pointer transition">
                    &times;
                </button>
            </div>

            <!-- Iframe displaying full certificate -->
            <iframe id="preview-live-iframe" src="" class="w-full h-full border-0 block" style="width: 100%; height: 100%; border: none; display: block;" onload="try{if(this.contentWindow.fitCertificateToScreen)this.contentWindow.fitCertificateToScreen()}catch(e){}"></iframe>
        </div>
    </div>

    <script>
        const baseAdminCertUrl = "{{ url('admin/certificate-templates') }}";

        function openCreateModal() {
            document.getElementById('createCertModal').classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        function openEditModal(template) {
            const form = document.getElementById('editTemplateForm');
            form.action = baseAdminCertUrl + '/' + template.id;

            document.getElementById('edit_title').value = template.title || '';
            document.getElementById('edit_template_type').value = template.template_type || 'template_1';
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
            const baseW = 1060;
            const baseH = 750;
            const ratio = baseW / baseH;

            const modalBox = document.getElementById('previewModalBox');
            if (modalBox) {
                modalBox.style.aspectRatio = ratio.toString();
                modalBox.style.width = `min(94vw, ${baseW}px)`;
                modalBox.style.height = `min(calc(94vw / ${ratio}), calc(100vh - 60px))`;
                modalBox.style.backgroundColor = '#ffffff';
            }

            const url = baseAdminCertUrl + '/' + template.id + '/preview';
            document.getElementById('preview-fullscreen-link').href = url;
            const iframe = document.getElementById('preview-live-iframe');
            iframe.src = url + '?embed=1';

            const modal = document.getElementById('previewCertModal');
            modal.classList.remove('hidden');
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';

            const triggerFit = function() {
                try {
                    if (iframe.contentWindow && iframe.contentWindow.fitCertificateToScreen) {
                        iframe.contentWindow.fitCertificateToScreen();
                    }
                } catch(e) {}
            };
            setTimeout(triggerFit, 50);
            setTimeout(triggerFit, 200);
            setTimeout(triggerFit, 500);
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewCertModal');
            modal.classList.remove('open');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('preview-live-iframe').src = '';
        }
    </script>

</x-layouts.dashboard>
