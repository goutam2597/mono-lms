<!DOCTYPE html>
<html lang="en" class="h-full w-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $certificate->certificate_number }} - Certificate of Achievement</title>
    <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@500;600;700&family=Cinzel:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Island+Moments&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Alex+Brush&family=Great+Vibes&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        @media print {
            html, body {
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .cert-viewport-container {
                display: block !important;
                width: 100% !important;
                height: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                overflow: visible !important;
            }
            .cert-scale-wrapper {
                transform: none !important;
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }
            .certificate-frame {
                width: 100vw !important;
                height: 100vh !important;
                max-width: 100vw !important;
                max-height: 100vh !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 0;
            }
        }

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
</head>
<body class="@if (request('embed')) bg-slate-950 @else bg-slate-950 text-slate-900 @endif w-full h-full flex flex-col font-sans antialiased selection:bg-amber-100 selection:text-amber-900">

    @php
        $layoutType = $template->template_type ?? 'template_3';
        $primaryColor = $template->border_color ?: '#1a1815';
        $accentColor = $template->accent_color ?: '#835a2a';
        $bgColor = $template->bg_color ?: '#ffffff';
    @endphp

    @if (! request('embed'))
        <!-- Top Action Toolbar (Fixed header, hidden during print) -->
        <header class="no-print shrink-0 w-full z-30 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 px-4 py-2.5 shadow-xl text-white">
            <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('student.certificates') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold transition-colors border border-slate-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Back to My Certificates</span>
                    </a>
                    <div class="hidden sm:block">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-white font-mono">{{ $certificate->certificate_number }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                {{ $template->title ?? 'Accredited Award' }}
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400">Awarded to {{ $certificate->student_name }} on {{ $certificate->issue_date->format('F d, Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                            onclick="copyVerificationLink('{{ $certificate->verificationUrl() }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold transition-colors border border-slate-700 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span id="copy-btn-text">Share Link</span>
                    </button>

                    <a href="{{ $certificate->verificationUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold transition-colors border border-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Verify Registry</span>
                    </a>

                    <button type="button"
                            onclick="window.print()"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider shadow-lg transition-transform hover:scale-105 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print / Save PDF</span>
                    </button>
                </div>
            </div>
        </header>
    @endif

    <!-- ========================================================================= -->
    <!-- CERTIFICATE STAGE: Auto-scales Canonical 1120x792 Canvas into Available Viewport -->
    <!-- ========================================================================= -->
    <main class="cert-viewport-container flex-1 w-full flex items-center justify-center overflow-hidden p-2 sm:p-4">
        <div id="certScaleWrapper" class="cert-scale-wrapper origin-center transition-transform duration-100 flex items-center justify-center">

            @if ($layoutType === 'template_7' || $layoutType === 'modern')
                <!-- --------------------------------------------------------------------- -->
                <!-- FIGMA TEMPLATE 7: CONTEMPORARY PRESTIGE AWARD -->
                <!-- --------------------------------------------------------------------- -->
                <div id="certificateCanvas"
                     class="certificate-frame w-[1120px] h-[792px] shrink-0 bg-white shadow-2xl relative overflow-hidden flex flex-col justify-between p-12 select-none font-sans"
                     style="background-color: {{ $bgColor }};">

                    <!-- Dashed Inset Border -->
                    <div class="absolute inset-7 border-[2px] border-[#e8e8e4] border-dashed pointer-events-none select-none"></div>

                    <!-- Top Header Accent Curve from Figma -->
                    <div class="absolute top-0 right-0 w-[55%] h-auto pointer-events-none select-none">
                        <img src="{{ asset('images/certificates/template-7/top-accent.svg') }}" class="w-full h-auto object-contain" alt="">
                    </div>

                    <!-- Background Guilloché Watermark -->
                    <div class="absolute -top-10 -right-10 w-[60%] h-auto opacity-20 pointer-events-none select-none">
                        <img src="{{ asset('images/certificates/template-7/watermark.svg') }}" class="w-full h-auto object-contain" alt="">
                    </div>

                    <!-- Top Left Header Title -->
                    <div class="relative z-10 text-left pt-3">
                        <p class="font-baskerville font-normal text-[17px] text-[#5a5549] uppercase tracking-wide">
                            CERTIFICATE of
                        </p>
                        <h1 class="font-baskerville font-bold text-[46px] text-[#1a1815] uppercase tracking-tight leading-none mt-1">
                            ACHIEVEMENT
                        </h1>
                    </div>

                    <!-- Center Recipient Details -->
                    <div class="relative z-10 my-auto py-2 text-left max-w-[640px]">
                        <p class="font-['Inter'] font-semibold text-[13px] text-[#c1bfb3] uppercase tracking-[2.5px]">
                            This is proudly presented to
                        </p>

                        <div class="relative inline-flex flex-col items-start mt-2">
                            <span class="font-island text-[74px] text-[#835a2a] leading-[1.05] select-none pr-6 block">
                                {{ $certificate->student_name }}
                            </span>
                            <img src="{{ asset('images/certificates/template-7/name-underline.svg') }}" class="w-[360px] h-auto mt-1" alt="">
                        </div>

                        <p class="font-sans text-[13px] text-[#6f6a5b] leading-[1.6] mt-4 max-w-[560px]">
                            {{ $template->subtitle ?: 'This certificate acknowledges your outstanding dedication and successful completion of all graduation assessments for the conferred curriculum of' }}
                        </p>

                        <div class="mt-2.5">
                            <h3 class="font-baskerville font-bold text-[21px] text-slate-900">
                                {{ $certificate->course_title }}
                            </h3>
                            @if ($certificate->grade)
                                <span class="font-mono text-xs font-bold uppercase text-emerald-800 tracking-wider inline-block mt-0.5">
                                    Academic Standing: {{ $certificate->grade }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Right Seal Medallion from Figma -->
                    <div class="absolute right-14 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none select-none">
                        <img src="{{ asset('images/certificates/template-7/badge-shadow.svg') }}" class="absolute w-44 h-44 opacity-80" alt="">
                        <img src="{{ asset('images/certificates/template-7/badge-seal.svg') }}" class="relative w-44 h-44 object-contain drop-shadow-md" alt="">
                    </div>

                    <!-- Footer Signatures & QR -->
                    <div class="relative z-10 pt-4 border-t border-slate-200/80 flex items-end justify-between">
                        <div>
                            <div class="font-signature text-[34px] text-slate-900 select-none h-[44px] flex items-end pb-1">
                                {{ $certificate->instructor_name ?: $template->signer_name }}
                            </div>
                            <img src="{{ asset('images/certificates/template-7/signature-underline.svg') }}" class="w-44 h-auto my-1.5" alt="">
                            <p class="font-barlow font-semibold text-[13px] text-[#4d493f] uppercase tracking-wide">
                                {{ $certificate->instructor_name ?: $template->signer_name }}
                            </p>
                            <p class="text-[10px] text-slate-400 uppercase font-mono mt-0.5">{{ $template->signature_title ?: 'Manager, CTO' }}</p>
                        </div>

                        <div class="flex items-end gap-5 text-right">
                            <div class="text-right">
                                <span class="font-barlow font-semibold text-[11px] text-[#8c8773] uppercase block">Presented on</span>
                                <span class="font-sans font-medium text-[15px] text-slate-900 block mt-0.5">{{ $certificate->issue_date->format('d M Y') }}</span>
                                <span class="font-mono text-[9px] text-slate-400 block mt-0.5">{{ $certificate->certificate_number }}</span>
                            </div>
                            @if ($template->show_qr_code ?? true)
                                <div class="w-14 h-14 bg-white p-1 border border-slate-300 rounded shadow-xs shrink-0">
                                    <img src="{{ $certificate->qrCodeUrl() }}" class="w-full h-full object-contain" alt="QR Verify">
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            @elseif ($layoutType === 'template_8' || $layoutType === 'elegant')
                <!-- --------------------------------------------------------------------- -->
                <!-- FIGMA TEMPLATE 8: EXECUTIVE DIAGONAL MONOGRAM -->
                <!-- --------------------------------------------------------------------- -->
                <div id="certificateCanvas"
                     class="certificate-frame w-[1120px] h-[792px] shrink-0 bg-white shadow-2xl relative overflow-hidden flex select-none font-sans"
                     style="background-color: {{ $bgColor }};">

                    <!-- Main Left Content (approx 73%) -->
                    <div class="w-[73%] p-12 flex flex-col justify-between relative z-10">

                        <!-- Header -->
                        <div class="text-left pt-3">
                            <p class="font-baskerville font-normal text-[17px] text-[#5a5549] uppercase tracking-wide">
                                CERTIFICATE of
                            </p>
                            <h1 class="font-baskerville font-bold text-[46px] text-[#1a1815] uppercase tracking-tight leading-none mt-1">
                                ACHIEVEMENT
                            </h1>
                        </div>

                        <!-- Recipient Info -->
                        <div class="my-auto py-2 text-left max-w-[580px]">
                            <p class="font-['Inter'] font-semibold text-[13px] text-[#c1bfb3] uppercase tracking-[2.5px]">
                                This is proudly presented to
                            </p>

                            <div class="relative inline-flex flex-col items-start mt-2">
                                <span class="font-island text-[74px] text-[#835a2a] leading-[1.05] select-none pr-6 block">
                                    {{ $certificate->student_name }}
                                </span>
                                <img src="{{ asset('images/certificates/template-8/name-underline.svg') }}" class="w-[360px] h-auto mt-1" alt="">
                            </div>

                            <p class="font-sans text-[13px] text-[#6f6a5b] leading-[1.6] mt-4 max-w-[500px]">
                                {{ $template->subtitle ?: 'This certificate acknowledges your outstanding contribution, academic excellence, and mastery in the curriculum of' }}
                            </p>

                            <div class="mt-2.5">
                                <h3 class="font-baskerville font-bold text-[20px] text-slate-900">
                                    {{ $certificate->course_title }}
                                </h3>
                                @if ($certificate->grade)
                                    <span class="font-mono text-xs font-bold uppercase text-emerald-800 tracking-wider inline-block mt-0.5">
                                        Honors: {{ $certificate->grade }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Signatures & Date -->
                        <div class="pt-4 border-t border-slate-200/80 flex items-end justify-between pr-6">
                            <div>
                                <div class="font-signature text-[34px] text-slate-900 select-none h-[44px] flex items-end pb-1">
                                    {{ $certificate->instructor_name ?: $template->signer_name }}
                                </div>
                                <img src="{{ asset('images/certificates/template-8/signature-underline.svg') }}" class="w-44 h-auto my-1.5" alt="">
                                <p class="font-barlow font-semibold text-[13px] text-[#4d493f] uppercase tracking-wide">
                                    {{ $certificate->instructor_name ?: $template->signer_name }}
                                </p>
                                <p class="text-[10px] text-slate-400 uppercase font-mono mt-0.5">{{ $template->signature_title ?: 'Manager, CTO' }}</p>
                            </div>

                            <div class="flex items-end gap-5 text-right">
                                <div class="text-right">
                                    <span class="font-barlow font-semibold text-[11px] text-[#8c8773] uppercase block">Presented on</span>
                                    <span class="font-sans font-medium text-[15px] text-slate-900 block mt-0.5">{{ $certificate->issue_date->format('d M Y') }}</span>
                                    <span class="font-mono text-[9px] text-slate-400 block mt-0.5">{{ $certificate->certificate_number }}</span>
                                </div>
                                @if ($template->show_qr_code ?? true)
                                    <div class="w-14 h-14 bg-white p-1 border border-slate-300 rounded shadow-xs shrink-0">
                                        <img src="{{ $certificate->qrCodeUrl() }}" class="w-full h-full object-contain" alt="QR Verify">
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Right Decorative Panel with Angled Geometric Pattern (approx 27%) -->
                    <div class="w-[27%] h-full absolute right-0 top-0 border-l-2 border-[#e5cfb7] overflow-hidden bg-slate-50">
                        <div class="absolute inset-0 flex flex-col justify-around opacity-90 scale-125 -rotate-45 pointer-events-none select-none">
                            <img src="{{ asset('images/certificates/template-8/pattern-1.svg') }}" class="w-full h-auto object-cover" alt="">
                            <img src="{{ asset('images/certificates/template-8/pattern-2.svg') }}" class="w-full h-auto object-cover" alt="">
                            <img src="{{ asset('images/certificates/template-8/pattern-3.svg') }}" class="w-full h-auto object-cover" alt="">
                            <img src="{{ asset('images/certificates/template-8/pattern-4.svg') }}" class="w-full h-auto object-cover" alt="">
                        </div>
                    </div>

                    <!-- Seal Medallion Overlapping Panel Edge -->
                    <div class="absolute right-[20%] top-1/2 -translate-y-1/2 z-20 pointer-events-none select-none">
                        <img src="{{ asset('images/certificates/template-8/badge-shadow.svg') }}" class="absolute -inset-4 w-40 h-40 opacity-70" alt="">
                        <img src="{{ asset('images/certificates/template-8/badge-seal.svg') }}" class="relative w-40 h-40 object-contain drop-shadow-xl" alt="">
                    </div>

                </div>

            @elseif ($layoutType === 'template_9')
                <!-- --------------------------------------------------------------------- -->
                <!-- FIGMA TEMPLATE 9: ROYAL MINIMALIST DISTINCTION -->
                <!-- --------------------------------------------------------------------- -->
                <div id="certificateCanvas"
                     class="certificate-frame w-[1120px] h-[792px] shrink-0 bg-[#fdfbf7] shadow-2xl relative overflow-hidden flex select-none font-sans"
                     style="background-color: {{ $bgColor !== '#ffffff' ? $bgColor : '#fdfbf7' }};">

                    <!-- Left Main Content Area (approx 75%) -->
                    <div class="w-[75%] p-12 flex flex-col justify-between relative z-10">

                        <!-- Header Title in Libre Baskerville -->
                        <div class="text-left pt-3">
                            <p class="font-baskerville font-normal text-[20px] text-[#4d493f] uppercase tracking-wide">
                                Certificate of
                            </p>
                            <h1 class="font-baskerville font-bold text-[50px] text-[#1a1815] uppercase tracking-tight leading-none mt-1">
                                Achievement
                            </h1>
                        </div>

                        <!-- Recipient Info -->
                        <div class="my-auto py-2 text-left max-w-[600px]">
                            <p class="font-noto font-normal text-[14px] text-[#1a1815]">
                                This is proudly presented to
                            </p>

                            <div class="relative inline-flex flex-col items-start mt-2">
                                <span class="font-island text-[74px] text-[#835a2a] leading-[1.05] select-none pr-6 block">
                                    {{ $certificate->student_name }}
                                </span>
                                <img src="{{ asset('images/certificates/template-9/line.svg') }}" class="w-[360px] h-auto mt-1" alt="">
                            </div>

                            <p class="font-noto font-normal text-[13px] text-[#6f6a5b] leading-[1.6] mt-4 max-w-[540px]">
                                {{ $template->subtitle ?: 'This certificate acknowledges your outstanding dedication and successful completion of all graduation assessments for' }}
                            </p>

                            <div class="mt-2.5">
                                <h3 class="font-baskerville font-bold text-[20px] text-slate-900">
                                    {{ $certificate->course_title }}
                                </h3>
                                @if ($certificate->grade)
                                    <span class="font-mono text-xs font-bold uppercase text-emerald-800 tracking-wider inline-block mt-0.5">
                                        Standing: {{ $certificate->grade }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Signatures & Date -->
                        <div class="pt-4 border-t border-slate-200/80 flex items-end justify-between pr-6">
                            <div class="flex items-end gap-10">
                                <div>
                                    <p class="font-noto text-xs text-slate-600 mb-0.5">Faculty Director</p>
                                    <div class="font-signature text-[34px] text-slate-900 select-none h-[44px] flex items-end pb-1">
                                        {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                                    </div>
                                    <div class="w-40 h-[1.5px] bg-[#835a2a]/40 my-1"></div>
                                    <p class="text-[10px] text-slate-500 uppercase font-mono">{{ $template->signature_title ?? 'Program Director' }}</p>
                                </div>

                                <div>
                                    <p class="font-noto text-xs text-slate-600 mb-0.5">Presented on</p>
                                    <p class="font-baskerville text-[16px] font-semibold text-slate-900 mt-2">{{ $certificate->issue_date->format('d M Y') }}</p>
                                    <p class="font-mono text-[9px] text-slate-400 mt-0.5">{{ $certificate->certificate_number }}</p>
                                </div>
                            </div>

                            @if ($template->show_qr_code ?? true)
                                <div class="w-14 h-14 bg-white p-1 border border-slate-300 rounded shadow-xs shrink-0">
                                    <img src="{{ $certificate->qrCodeUrl() }}" class="w-full h-full object-contain" alt="QR Verify">
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Right Decorative Column (approx 25%) with Vertical Separator and Pattern -->
                    <div class="w-[25%] h-full absolute right-0 top-0 overflow-hidden bg-[#faf7f2]">
                        <!-- Vertical Divider Rule -->
                        <div class="absolute left-0 top-0 bottom-0 w-[2px]">
                            <img src="{{ asset('images/certificates/template-9/vertical-line.svg') }}" class="w-full h-full object-cover" alt="">
                        </div>

                        <!-- Geometric Lattice Pattern -->
                        <div class="absolute inset-0 opacity-80 pointer-events-none select-none">
                            <img src="{{ asset('images/certificates/template-9/pattern.svg') }}" class="w-full h-full object-cover scale-150 rotate-12" alt="">
                        </div>

                        <!-- Authentic Brand Seal Medallion from Figma -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none">
                            <div class="relative w-36 h-36 flex items-center justify-center">
                                <img src="{{ asset('images/certificates/template-9/seal-ring.svg') }}" class="absolute inset-0 w-full h-full object-contain" alt="">
                                <img src="{{ asset('images/certificates/template-9/seal-circle.svg') }}" class="relative w-24 h-24 object-contain drop-shadow-md" alt="">
                                <img src="{{ asset('images/certificates/template-9/seal-brand.svg') }}" class="absolute w-12 h-12 object-contain" alt="">
                            </div>
                        </div>
                    </div>

                </div>

            @else
                <!-- --------------------------------------------------------------------- -->
                <!-- FIGMA TEMPLATE 3: HERITAGE GRAND ACHIEVEMENT (DEFAULT) -->
                <!-- --------------------------------------------------------------------- -->
                <div id="certificateCanvas"
                     class="certificate-frame w-[1120px] h-[792px] shrink-0 bg-white shadow-2xl relative overflow-hidden flex flex-col justify-between py-10 px-14 select-none font-sans"
                     style="background-color: {{ $bgColor }};">

                    <!-- Figma Background Guilloché Security Pattern -->
                    <img src="{{ asset('images/certificates/template-3/bg-pattern.svg') }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none opacity-85" alt="">

                    <!-- Figma Perimeter Filigree Ornamental Border -->
                    <div class="absolute inset-5 pointer-events-none select-none">
                        <img src="{{ asset('images/certificates/template-3/border-decor.svg') }}" class="w-full h-full object-fill" alt="">
                    </div>

                    <!-- Header Container -->
                    <div class="relative z-10 text-center pt-2">
                        <h1 class="font-baskerville font-bold uppercase tracking-[0.03em] text-[#1a1815] text-[52px] text-center leading-none">
                            CERTIFICATE
                        </h1>

                        <!-- Subtitle with Ornamental Flourishes -->
                        <div class="flex items-center justify-center gap-4 mt-2">
                            <img src="{{ asset('images/certificates/template-3/flourish-left.svg') }}" class="h-[18px] w-auto" alt="">
                            <span class="font-baskerville font-normal uppercase tracking-[0.2em] text-[17px] text-[#33312a]">
                                OF ACHIEVEMENT
                            </span>
                            <img src="{{ asset('images/certificates/template-3/flourish-right.svg') }}" class="h-[18px] w-auto" alt="">
                        </div>

                        <!-- Crest Emblem -->
                        <img src="{{ asset('images/certificates/template-3/crest.svg') }}" class="h-[16px] w-auto mx-auto mt-2.5" alt="">
                    </div>

                    <!-- Center Recipient Presentation -->
                    <div class="relative z-10 my-auto py-2 text-center flex flex-col items-center">
                        <p class="font-['Inter'] font-semibold tracking-[0.2em] uppercase text-[#a7a494] text-[12px]">
                            This is proudly presented to
                        </p>

                        <!-- Student Name with Authentic Vector Underline -->
                        <div class="relative inline-flex flex-col items-center mt-1">
                            <span class="font-island text-[76px] text-[#835a2a] leading-[1.05] select-none px-8 block">
                                {{ $certificate->student_name }}
                            </span>

                            <div class="flex items-center justify-center w-[580px] -mt-1">
                                <img src="{{ asset('images/certificates/template-3/underline-left.svg') }}" class="h-[16px] w-auto shrink-0" alt="">
                                <div class="flex-1 h-[2.5px] bg-gradient-to-r from-[#835a2a] via-[#e5cfb7] to-[#835a2a] rounded-full mx-0.5"></div>
                                <img src="{{ asset('images/certificates/template-3/underline-right.svg') }}" class="h-[16px] w-auto shrink-0" alt="">
                            </div>
                        </div>

                        <p class="font-sans text-[13px] text-[#6f6a5b] max-w-[700px] mx-auto text-center leading-[1.6] mt-4">
                            {{ $template->subtitle ?: 'This certificate acknowledges your outstanding dedication, mastery, and distinguished accomplishment in successfully completing' }}
                        </p>

                        <h3 class="font-baskerville font-bold text-[22px] text-slate-900 mt-2 tracking-wide text-center">
                            {{ $certificate->course_title }}
                        </h3>

                        @if ($certificate->grade)
                            <div class="mt-1.5">
                                <span class="inline-block px-3 py-0.5 rounded-full bg-amber-500/10 border border-amber-600/20 font-mono text-[11px] font-bold uppercase tracking-wider text-[#835a2a]">
                                    Conferred with Academic Honors: {{ $certificate->grade }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Signatures & Authentic Medallion Seal (Balanced 3 Columns) -->
                    <div class="relative z-10 pt-3 border-t border-slate-200/80 flex items-end justify-between px-6 pb-1">
                        <!-- Date Column -->
                        <div class="flex flex-col items-center w-56 text-center">
                            <img src="{{ asset('images/certificates/template-3/divider.svg') }}" class="w-36 h-auto mb-1.5" alt="">
                            <span class="font-sans text-[14px] font-semibold text-[#5a5549]">{{ $certificate->issue_date->format('d M Y') }}</span>
                            <span class="font-sans text-[10px] uppercase tracking-widest text-slate-400 font-medium">Award Date</span>
                            <span class="font-mono text-[9px] text-slate-400 mt-0.5">{{ $certificate->certificate_number }}</span>
                        </div>

                        <!-- Center 3D Gold Ribbon Seal Medallion from Figma -->
                        <div class="flex flex-col items-center justify-center w-48 text-center -mb-2">
                            <img src="{{ asset('images/certificates/template-3/badge.svg') }}" class="w-24 h-24 object-contain drop-shadow-xl" alt="Official Seal">
                            <span class="font-mono text-[10px] font-bold text-slate-600 mt-0.5">ACCREDITED REGISTRY</span>
                        </div>

                        <!-- Signature Column -->
                        <div class="flex flex-col items-center w-56 text-center">
                            <div class="font-signature text-[34px] text-[#33312a] leading-none select-none h-[42px] flex items-end justify-center pb-1">
                                {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                            </div>
                            <img src="{{ asset('images/certificates/template-3/divider.svg') }}" class="w-36 h-auto my-1.5" alt="">
                            <span class="font-sans text-[13px] font-bold text-slate-900 uppercase tracking-wider block">{{ $template->signer_name ?? 'Dr. Alexander Wright' }}</span>
                            <span class="font-sans text-[10px] uppercase tracking-widest text-slate-500 font-medium block mt-0.5">{{ $template->signature_title ?? 'Program Director' }}</span>
                        </div>

                        <!-- Floating Corner QR Badge (Unobtrusive) -->
                        @if ($template->show_qr_code ?? true)
                            <div class="absolute bottom-5 right-8 bg-white/95 p-1 border border-slate-300 shadow-sm rounded flex items-center gap-1.5">
                                <img src="{{ $certificate->qrCodeUrl() }}" class="w-9 h-9 object-contain" alt="QR Verify">
                                <div class="text-[7.5px] font-mono leading-tight text-slate-500 text-left pr-1">
                                    <span class="font-bold text-slate-800 block">VERIFIED</span>REGISTRY
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            @endif

        </div>
    </main>

    <script>
        function copyVerificationLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                var btn = document.getElementById('copy-btn-text');
                if (btn) {
                    var old = btn.textContent;
                    btn.textContent = 'Copied! ✓';
                    setTimeout(function() { btn.textContent = old; }, 2500);
                }
            });
        }

        // Rock-solid auto-scaling engine: fits canonical 1120x792 canvas perfectly inside available stage
        function fitCertificate() {
            var container = document.querySelector('.cert-viewport-container');
            var wrapper = document.getElementById('certScaleWrapper');
            var canvas = document.getElementById('certificateCanvas');
            if (!container || !wrapper || !canvas) return;

            var pad = window.innerWidth < 640 ? 12 : 24;
            var availW = Math.max(container.clientWidth - pad, 260);
            var availH = Math.max(container.clientHeight - pad, 180);

            var baseW = 1120;
            var baseH = 792;

            var scale = Math.min(availW / baseW, availH / baseH);
            wrapper.style.transform = 'scale(' + scale + ')';
            wrapper.style.transformOrigin = 'center center';
            wrapper.style.width = baseW + 'px';
            wrapper.style.height = baseH + 'px';
        }

        window.addEventListener('resize', fitCertificate);
        window.addEventListener('DOMContentLoaded', fitCertificate);
        window.addEventListener('load', fitCertificate);
        setTimeout(fitCertificate, 20);
        setTimeout(fitCertificate, 150);
        setTimeout(fitCertificate, 400);
    </script>
</body>
</html>
