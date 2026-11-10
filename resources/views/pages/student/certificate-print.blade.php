<!DOCTYPE html>
<html lang="en">
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
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .certificate-frame {
                box-shadow: none !important;
                max-width: 100% !important;
                width: 100vw !important;
                height: 100vh !important;
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

        .gold-foil-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #fef3c7 35%, #b48608 70%, #fef08a 100%);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-900 min-h-screen py-8 px-4 flex flex-col items-center justify-center font-sans antialiased selection:bg-amber-100 selection:text-amber-900">

    @php
        $layoutType = $template->template_type ?? 'template_3';
        $primaryColor = $template->border_color ?: '#1a1815';
        $accentColor = $template->accent_color ?: '#835a2a';
        $bgColor = $template->bg_color ?: '#ffffff';
    @endphp

    <!-- Top Action Toolbar (Hidden in Print) -->
    <div class="no-print w-full max-w-5xl mb-6 flex flex-wrap items-center justify-between gap-4 bg-slate-900/90 backdrop-blur-md border border-slate-800 p-4 rounded-xl text-white shadow-2xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.certificates') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold transition-colors border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to My Certificates</span>
            </a>
            <div class="hidden sm:block">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-white font-mono">{{ $certificate->certificate_number }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        {{ $template->title ?? 'Accredited Award' }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Awarded to {{ $certificate->student_name }} on {{ $certificate->issue_date->format('F d, Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button"
                    onclick="copyVerificationLink('{{ $certificate->verificationUrl() }}')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold transition-colors border border-slate-700 cursor-pointer">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span id="copy-btn-text">Share Link</span>
            </button>

            <a href="{{ $certificate->verificationUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold transition-colors border border-slate-700">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Verify Registry</span>
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-bold uppercase tracking-wider shadow-lg transition-transform hover:scale-105 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print / Save PDF</span>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- CERTIFICATE CANVAS: A4 Landscape Aspect Ratio (1.414 / 1) -->
    <!-- ========================================================================= -->

    @if ($layoutType === 'template_7' || $layoutType === 'modern')
        <!-- --------------------------------------------------------------------- -->
        <!-- FIGMA TEMPLATE 7: CONTEMPORARY PRESTIGE AWARD -->
        <!-- --------------------------------------------------------------------- -->
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-white shadow-2xl relative overflow-hidden flex flex-col justify-between p-8 sm:p-14 select-none font-sans"
             style="background-color: {{ $bgColor }};">

            <!-- Dashed Inset Border -->
            <div class="absolute inset-5 sm:inset-8 border-[2.5px] border-[#f0f0ec] border-dashed pointer-events-none select-none"></div>

            <!-- Top Header Accent Curve from Figma -->
            <div class="absolute top-0 right-0 w-[55%] h-auto pointer-events-none select-none">
                <img src="{{ asset('images/certificates/template-7/top-accent.svg') }}" class="w-full h-auto object-contain" alt="">
            </div>

            <!-- Background Guilloché Watermark -->
            <div class="absolute -top-12 -right-12 w-[65%] h-auto opacity-25 pointer-events-none select-none">
                <img src="{{ asset('images/certificates/template-7/watermark.svg') }}" class="w-full h-auto object-contain" alt="">
            </div>

            <!-- Left / Top Header Title -->
            <div class="relative z-10 text-left pt-2">
                <p class="font-baskerville font-normal text-sm sm:text-xl text-[#5a5549] uppercase tracking-wide">
                    CERTIFICATE of
                </p>
                <h1 class="font-baskerville font-bold text-2xl sm:text-5xl text-[#1a1815] uppercase tracking-tight mt-0.5">
                    ACHIEVEMENT
                </h1>
            </div>

            <!-- Center Recipient Details -->
            <div class="relative z-10 my-auto py-4 text-left max-w-2xl">
                <p class="font-['Inter'] font-semibold text-xs sm:text-sm text-[#c1bfb3] uppercase tracking-[2.5px]">
                    This is proudly presented to
                </p>

                <div class="relative inline-flex flex-col items-start mt-1">
                    <span class="font-island text-4xl sm:text-7xl lg:text-8xl text-[#835a2a] leading-[0.8] select-none pr-6">
                        {{ $certificate->student_name }}
                    </span>
                    <img src="{{ asset('images/certificates/template-7/name-underline.svg') }}" class="w-full max-w-sm h-auto mt-1" alt="">
                </div>

                <p class="font-sans text-xs sm:text-base text-[#6f6a5b] leading-relaxed mt-4 max-w-xl">
                    {{ $template->subtitle ?: 'This certificate acknowledges your outstanding dedication and successful completion of all graduation assessments for the conferred curriculum of' }}
                </p>

                <div class="mt-2">
                    <h3 class="font-baskerville font-bold text-base sm:text-xl text-slate-900">
                        {{ $certificate->course_title }}
                    </h3>
                    @if ($certificate->grade)
                        <span class="font-mono text-xs font-bold uppercase text-emerald-800 tracking-wider">
                            Academic Standing: {{ $certificate->grade }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Right Seal Medallion from Figma -->
            <div class="absolute right-8 sm:right-16 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none select-none">
                <img src="{{ asset('images/certificates/template-7/badge-shadow.svg') }}" class="absolute w-28 h-28 sm:w-44 sm:h-44 opacity-80" alt="">
                <img src="{{ asset('images/certificates/template-7/badge-seal.svg') }}" class="relative w-28 h-28 sm:w-44 sm:h-44 object-contain drop-shadow-md" alt="">
            </div>

            <!-- Footer Signatures & QR -->
            <div class="relative z-10 pt-4 border-t border-slate-100 flex items-end justify-between">
                <div>
                    <div class="font-signature text-2xl sm:text-4xl text-slate-900 select-none h-8 sm:h-10 flex items-center">
                        {{ $certificate->instructor_name ?: $template->signer_name }}
                    </div>
                    <img src="{{ asset('images/certificates/template-7/signature-underline.svg') }}" class="w-36 sm:w-48 h-auto my-1" alt="">
                    <p class="font-barlow font-semibold text-xs sm:text-sm text-[#4d493f] uppercase">
                        {{ $certificate->instructor_name ?: $template->signer_name }}
                    </p>
                    <p class="text-[10px] text-slate-400 uppercase font-mono">{{ $template->signature_title ?: 'Manager, CTO' }}</p>
                </div>

                <div class="flex items-end gap-4 text-right">
                    <div class="text-right">
                        <span class="font-barlow font-semibold text-[10px] sm:text-xs text-[#8c8773] uppercase block">Presented on</span>
                        <span class="font-sans font-medium text-xs sm:text-base text-slate-900 block">{{ $certificate->issue_date->format('d M Y') }}</span>
                        <span class="font-mono text-[9px] text-slate-400 block mt-0.5">{{ $certificate->certificate_number }}</span>
                    </div>
                    @if ($template->show_qr_code ?? true)
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white p-1 border border-slate-200 rounded shrink-0">
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
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-white shadow-2xl relative overflow-hidden flex select-none font-sans"
             style="background-color: {{ $bgColor }};">

            <!-- Main Left Content (approx 72%) -->
            <div class="w-[70%] sm:w-[73%] p-8 sm:p-14 flex flex-col justify-between relative z-10">

                <!-- Header -->
                <div class="text-left pt-2">
                    <p class="font-baskerville font-normal text-xs sm:text-lg text-[#5a5549] uppercase tracking-wide">
                        CERTIFICATE of
                    </p>
                    <h1 class="font-baskerville font-bold text-2xl sm:text-5xl text-[#1a1815] uppercase tracking-tight mt-0.5">
                        ACHIEVEMENT
                    </h1>
                </div>

                <!-- Recipient Info -->
                <div class="my-auto py-3 text-left">
                    <p class="font-['Inter'] font-semibold text-xs sm:text-sm text-[#c1bfb3] uppercase tracking-[2.5px]">
                        This is proudly presented to
                    </p>

                    <div class="relative inline-flex flex-col items-start mt-1">
                        <span class="font-island text-4xl sm:text-7xl lg:text-8xl text-[#835a2a] leading-[0.8] select-none pr-6">
                            {{ $certificate->student_name }}
                        </span>
                        <img src="{{ asset('images/certificates/template-8/name-underline.svg') }}" class="w-full max-w-sm h-auto mt-1" alt="">
                    </div>

                    <p class="font-sans text-xs sm:text-sm text-[#6f6a5b] leading-relaxed mt-4 max-w-md">
                        {{ $template->subtitle ?: 'This certificate acknowledges your outstanding contribution, academic excellence, and mastery in the curriculum of' }}
                    </p>

                    <div class="mt-2">
                        <h3 class="font-baskerville font-bold text-sm sm:text-lg text-slate-900">
                            {{ $certificate->course_title }}
                        </h3>
                        @if ($certificate->grade)
                            <span class="font-mono text-[11px] font-bold uppercase text-emerald-800">
                                Honors: {{ $certificate->grade }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Footer Signatures & Date -->
                <div class="pt-4 border-t border-slate-100 flex items-end justify-between pr-4">
                    <div>
                        <div class="font-signature text-2xl sm:text-4xl text-slate-900 select-none h-8 sm:h-10 flex items-center">
                            {{ $certificate->instructor_name ?: $template->signer_name }}
                        </div>
                        <img src="{{ asset('images/certificates/template-8/signature-underline.svg') }}" class="w-32 sm:w-44 h-auto my-1" alt="">
                        <p class="font-barlow font-semibold text-xs sm:text-sm text-[#4d493f] uppercase">
                            {{ $certificate->instructor_name ?: $template->signer_name }}
                        </p>
                        <p class="text-[9px] text-slate-400 uppercase font-mono">{{ $template->signature_title ?: 'Manager, CTO' }}</p>
                    </div>

                    <div class="flex items-end gap-3 text-right">
                        <div class="text-right">
                            <span class="font-barlow font-semibold text-[9px] sm:text-xs text-[#8c8773] uppercase block">Presented on</span>
                            <span class="font-sans font-medium text-xs sm:text-sm text-slate-900 block">{{ $certificate->issue_date->format('d M Y') }}</span>
                            <span class="font-mono text-[8px] text-slate-400 block mt-0.5">{{ $certificate->certificate_number }}</span>
                        </div>
                        @if ($template->show_qr_code ?? true)
                            <div class="w-10 h-10 sm:w-14 sm:h-14 bg-white p-1 border border-slate-200 rounded shrink-0">
                                <img src="{{ $certificate->qrCodeUrl() }}" class="w-full h-full object-contain" alt="QR Verify">
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Decorative Panel with Angled Geometric Pattern (approx 28%) -->
            <div class="w-[30%] sm:w-[27%] h-full absolute right-0 top-0 border-l-2 border-[#e5cfb7] overflow-hidden bg-slate-50">
                <div class="absolute inset-0 flex flex-col justify-around opacity-90 scale-125 -rotate-45 pointer-events-none select-none">
                    <img src="{{ asset('images/certificates/template-8/pattern-1.svg') }}" class="w-full h-auto object-cover" alt="">
                    <img src="{{ asset('images/certificates/template-8/pattern-2.svg') }}" class="w-full h-auto object-cover" alt="">
                    <img src="{{ asset('images/certificates/template-8/pattern-3.svg') }}" class="w-full h-auto object-cover" alt="">
                    <img src="{{ asset('images/certificates/template-8/pattern-4.svg') }}" class="w-full h-auto object-cover" alt="">
                </div>
            </div>

            <!-- Seal Medallion Overlapping Panel Edge -->
            <div class="absolute right-[22%] sm:right-[21%] top-1/2 -translate-y-1/2 z-20 pointer-events-none select-none">
                <img src="{{ asset('images/certificates/template-8/badge-shadow.svg') }}" class="absolute -inset-4 w-28 h-28 sm:w-44 sm:h-44 opacity-70" alt="">
                <img src="{{ asset('images/certificates/template-8/badge-seal.svg') }}" class="relative w-28 h-28 sm:w-44 sm:h-44 object-contain drop-shadow-xl" alt="">
            </div>

        </div>

    @elseif ($layoutType === 'template_9')
        <!-- --------------------------------------------------------------------- -->
        <!-- FIGMA TEMPLATE 9: ROYAL MINIMALIST DISTINCTION -->
        <!-- --------------------------------------------------------------------- -->
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-[#fdfbf7] shadow-2xl relative overflow-hidden flex select-none font-sans"
             style="background-color: {{ $bgColor !== '#ffffff' ? $bgColor : '#fdfbf7' }};">

            <!-- Left Main Content Area (approx 75%) -->
            <div class="w-[72%] sm:w-[75%] p-8 sm:p-14 flex flex-col justify-between relative z-10">

                <!-- Header Title in Libre Baskerville -->
                <div class="text-left pt-2">
                    <p class="font-baskerville font-normal text-sm sm:text-2xl text-[#4d493f] uppercase tracking-wide">
                        Certificate of
                    </p>
                    <h1 class="font-baskerville font-bold text-3xl sm:text-6xl text-[#1a1815] uppercase tracking-tight mt-1">
                        Achievement
                    </h1>
                </div>

                <!-- Recipient Info -->
                <div class="my-auto py-3 text-left">
                    <p class="font-noto font-normal text-xs sm:text-base text-[#1a1815]">
                        This is proudly presented to
                    </p>

                    <div class="relative inline-flex flex-col items-start mt-1">
                        <span class="font-island text-4xl sm:text-7xl lg:text-8xl text-[#835a2a] leading-[0.8] select-none pr-6">
                            {{ $certificate->student_name }}
                        </span>
                        <img src="{{ asset('images/certificates/template-9/line.svg') }}" class="w-full max-w-sm h-auto mt-1" alt="">
                    </div>

                    <p class="font-noto font-normal text-xs sm:text-base text-[#6f6a5b] leading-relaxed mt-4 max-w-lg">
                        {{ $template->subtitle ?: 'This certificate acknowledges your outstanding dedication and successful completion of all graduation assessments for' }}
                    </p>

                    <div class="mt-2">
                        <h3 class="font-baskerville font-bold text-sm sm:text-lg text-slate-900">
                            {{ $certificate->course_title }}
                        </h3>
                        @if ($certificate->grade)
                            <span class="font-mono text-xs font-bold uppercase text-emerald-800">
                                Standing: {{ $certificate->grade }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Footer Signatures & Date -->
                <div class="pt-4 border-t border-slate-200 flex items-end justify-between pr-4">
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="font-noto text-xs sm:text-sm text-slate-700">Faculty Director</p>
                            <div class="font-signature text-2xl sm:text-4xl text-slate-900 select-none h-8 sm:h-10 flex items-center">
                                {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase font-mono">{{ $template->signature_title ?? 'Program Director' }}</p>
                        </div>

                        <div>
                            <p class="font-noto text-xs sm:text-sm text-slate-700">Presented on</p>
                            <p class="font-baskerville text-xs sm:text-base font-semibold text-slate-900 mt-1">{{ $certificate->issue_date->format('d M Y') }}</p>
                            <p class="font-mono text-[9px] text-slate-400">{{ $certificate->certificate_number }}</p>
                        </div>
                    </div>

                    @if ($template->show_qr_code ?? true)
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white p-1 border border-slate-300 rounded shadow-xs shrink-0">
                            <img src="{{ $certificate->qrCodeUrl() }}" class="w-full h-full object-contain" alt="QR Verify">
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Decorative Column (approx 25%) with Vertical Separator and Pattern -->
            <div class="w-[28%] sm:w-[25%] h-full absolute right-0 top-0 overflow-hidden bg-[#faf7f2]">
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
                    <div class="relative w-28 h-28 sm:w-40 sm:h-40 flex items-center justify-center">
                        <img src="{{ asset('images/certificates/template-9/seal-ring.svg') }}" class="absolute inset-0 w-full h-full object-contain" alt="">
                        <img src="{{ asset('images/certificates/template-9/seal-circle.svg') }}" class="relative w-20 h-20 sm:w-28 sm:h-28 object-contain drop-shadow-md" alt="">
                        <img src="{{ asset('images/certificates/template-9/seal-brand.svg') }}" class="absolute w-10 h-10 sm:w-14 sm:h-14 object-contain" alt="">
                    </div>
                </div>
            </div>

        </div>

    @else
        <!-- --------------------------------------------------------------------- -->
        <!-- FIGMA TEMPLATE 3: HERITAGE GRAND ACHIEVEMENT (DEFAULT) -->
        <!-- --------------------------------------------------------------------- -->
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-white shadow-2xl relative overflow-hidden flex flex-col justify-between p-8 sm:p-14 select-none font-sans"
             style="background-color: {{ $bgColor }};">

            <!-- Figma Background Guilloché Security Pattern -->
            <img src="{{ asset('images/certificates/template-3/bg-pattern.svg') }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none opacity-80" alt="">

            <!-- Figma Perimeter Filigree Ornamental Border -->
            <div class="absolute inset-3 sm:inset-6 pointer-events-none select-none">
                <img src="{{ asset('images/certificates/template-3/border-decor.svg') }}" class="w-full h-full object-fill" alt="">
            </div>

            <!-- Header Container -->
            <div class="relative z-10 text-center pt-2 sm:pt-4">
                <h1 class="font-baskerville font-bold uppercase tracking-tight text-[#1a1815] text-3xl sm:text-5xl lg:text-6xl text-center leading-none">
                    CERTIFICATE
                </h1>

                <!-- Subtitle with Ornamental Flourishes -->
                <div class="flex items-center justify-center gap-3 sm:gap-6 mt-1 sm:mt-2">
                    <img src="{{ asset('images/certificates/template-3/flourish-left.svg') }}" class="h-3 sm:h-5 w-auto" alt="">
                    <span class="font-baskerville font-normal uppercase tracking-wider text-xs sm:text-lg lg:text-xl text-[#33312a]">
                        OF ACHIEVEMENT
                    </span>
                    <img src="{{ asset('images/certificates/template-3/flourish-right.svg') }}" class="h-3 sm:h-5 w-auto" alt="">
                </div>

                <!-- Crest Emblem -->
                <img src="{{ asset('images/certificates/template-3/crest.svg') }}" class="h-3 sm:h-4 w-auto mx-auto mt-1.5" alt="">
            </div>

            <!-- Center Recipient Presentation -->
            <div class="relative z-10 my-auto py-3 text-center">
                <p class="font-['Inter'] font-semibold tracking-[0.18em] uppercase text-[#a7a494] text-[10px] sm:text-xs">
                    This is proudly presented to
                </p>

                <!-- Student Name with Authentic Multi-Piece Vector Underline -->
                <div class="relative inline-flex flex-col items-center mt-1">
                    <span class="font-island text-4xl sm:text-7xl lg:text-8xl text-[#835a2a] leading-[0.85] select-none px-6">
                        {{ $certificate->student_name }}
                    </span>

                    <div class="flex items-center justify-center w-full min-w-[260px] sm:min-w-[480px] -mt-1 sm:-mt-2">
                        <img src="{{ asset('images/certificates/template-3/underline-left.svg') }}" class="h-2.5 sm:h-4 w-auto" alt="">
                        <div class="flex-1 h-[2px] bg-no-repeat bg-contain" style="background-image: url('{{ asset('images/certificates/template-3/underline-center.svg') }}'); background-repeat: repeat-x;"></div>
                        <img src="{{ asset('images/certificates/template-3/underline-right.svg') }}" class="h-2.5 sm:h-4 w-auto" alt="">
                    </div>
                </div>

                <p class="font-sans text-xs sm:text-sm text-[#6f6a5b] max-w-xl mx-auto text-center leading-relaxed mt-3 sm:mt-4">
                    {{ $template->subtitle ?: 'This certificate acknowledges your outstanding dedication, mastery, and distinguished accomplishment in successfully completing' }}
                </p>

                <h3 class="font-baskerville font-bold text-base sm:text-xl lg:text-2xl text-slate-900 mt-1.5 tracking-wide">
                    {{ $certificate->course_title }}
                </h3>

                @if ($certificate->grade)
                    <p class="font-mono text-xs font-bold uppercase tracking-wider text-[#835a2a] mt-1">
                        Conferred with Academic Honors: {{ $certificate->grade }}
                    </p>
                @endif
            </div>

            <!-- Footer Signatures & Authentic Medallion Seal -->
            <div class="relative z-10 pt-3 border-t border-slate-200/80 flex items-end justify-between px-2 sm:px-6">
                <!-- Date -->
                <div class="flex flex-col items-center w-36 sm:w-48 text-center">
                    <img src="{{ asset('images/certificates/template-3/divider.svg') }}" class="w-24 sm:w-36 h-auto mb-1" alt="">
                    <span class="font-sans text-xs sm:text-sm font-semibold text-[#5a5549]">{{ $certificate->issue_date->format('d M Y') }}</span>
                    <span class="font-sans text-[9px] sm:text-[10px] uppercase tracking-wider text-slate-400">Award Date</span>
                </div>

                <!-- Center 3D Gold Ribbon Seal Medallion from Figma -->
                <div class="flex flex-col items-center justify-center -mb-2">
                    <img src="{{ asset('images/certificates/template-3/badge.svg') }}" class="w-20 h-20 sm:w-28 sm:h-28 object-contain drop-shadow-xl" alt="Official Seal">
                    <span class="font-mono text-[9px] font-bold text-slate-600 mt-0.5">{{ $certificate->certificate_number }}</span>
                </div>

                <!-- Signature & QR -->
                <div class="flex items-end gap-3 text-right">
                    @if ($template->show_qr_code ?? true)
                        <div class="hidden sm:block w-12 h-12 bg-white p-0.5 border border-slate-200 rounded shadow-xs shrink-0">
                            <img src="{{ $certificate->qrCodeUrl() }}" class="w-full h-full object-contain" alt="QR Verify">
                        </div>
                    @endif
                    <div class="flex flex-col items-center w-36 sm:w-48 text-center">
                        <div class="font-signature text-2xl sm:text-4xl text-[#33312a] leading-none select-none h-7 sm:h-9 flex items-center justify-center">
                            {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                        </div>
                        <img src="{{ asset('images/certificates/template-3/divider.svg') }}" class="w-24 sm:w-36 h-auto my-1" alt="">
                        <span class="font-sans text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $template->signer_name ?? 'Dr. Alexander Wright' }}</span>
                        <span class="font-sans text-[9px] sm:text-[10px] uppercase tracking-wider text-slate-400 truncate">{{ $template->signature_title ?? 'Program Director' }}</span>
                    </div>
                </div>
            </div>

        </div>
    @endif

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
    </script>
</body>
</html>
