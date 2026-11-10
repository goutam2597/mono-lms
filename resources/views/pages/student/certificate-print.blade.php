<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $certificate->certificate_number }} - Certificate of Completion</title>
    <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,500;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Alex+Brush&family=Great+Vibes&display=swap" rel="stylesheet">
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
</head>
<body class="bg-slate-950 text-slate-900 min-h-screen py-8 px-4 flex flex-col items-center justify-center font-sans antialiased selection:bg-amber-100 selection:text-amber-900">

    @php
        $layoutType = $template->template_type ?? 'classic';
        $primaryColor = $template->border_color ?: '#344c3d';
        $accentColor = $template->accent_color ?: '#738a6e';
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
                    <span class="px-2 py-0.2 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        {{ ucfirst($layoutType) }} Award Style
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

    @if ($layoutType === 'modern')
        <!-- --------------------------------------------------------------------- -->
        <!-- STYLE 2: MODERN MINIMALIST / TECH EXECUTIVE -->
        <!-- --------------------------------------------------------------------- -->
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-white shadow-2xl relative overflow-hidden flex flex-col justify-between text-slate-900 font-modern"
             style="background-color: {{ $bgColor }};">

            <!-- Architectural Top & Left Color Accents -->
            <div class="absolute top-0 left-0 right-0 h-3" style="background: linear-gradient(90deg, {{ $primaryColor }} 0%, {{ $accentColor }} 70%, #d4af37 100%);"></div>
            <div class="absolute top-3 left-0 bottom-0 w-3" style="background: {{ $primaryColor }};"></div>

            <!-- Modern Technical Corner Crosshairs -->
            <div class="absolute top-6 left-6 font-mono text-[10px] text-slate-300 select-none">+</div>
            <div class="absolute top-6 right-6 font-mono text-[10px] text-slate-300 select-none">+</div>
            <div class="absolute bottom-6 left-6 font-mono text-[10px] text-slate-300 select-none">+</div>
            <div class="absolute bottom-6 right-6 font-mono text-[10px] text-slate-300 select-none">+</div>

            <!-- Certificate Inner Container with Inset Padding -->
            <div class="p-8 sm:p-14 flex-1 flex flex-col justify-between relative z-10 pl-12 sm:pl-16">

                <!-- Header Brand & Badge -->
                <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $primaryColor }};"></span>
                            <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 font-mono">MONO LMS // ACADEMIC REGISTRY</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold uppercase tracking-tight text-slate-900">
                            Verified Certificate of Mastery
                        </h1>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <div class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-right">
                            <span class="text-[9px] uppercase tracking-wider text-slate-400 block font-mono">Credential ID</span>
                            <span class="text-xs font-mono font-bold text-slate-800">{{ $certificate->certificate_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recipient Center Presentation -->
                <div class="my-auto py-6 space-y-4">
                    <p class="text-xs uppercase tracking-widest text-slate-400 font-bold font-mono">
                        This accredited credential is conferred upon
                    </p>

                    <div>
                        <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight" style="color: {{ $primaryColor }};">
                            {{ $certificate->student_name }}
                        </h2>
                        <div class="w-32 h-1 rounded-full mt-2" style="background: linear-gradient(90deg, {{ $primaryColor }}, transparent);"></div>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                        {{ $template->subtitle ?: 'for completing the comprehensive hands-on curriculum, demonstrating practical problem-solving competence, and fulfilling all graduation assessment requirements for:' }}
                    </p>

                    <div class="inline-block bg-slate-50 border border-slate-200/80 px-4 py-2.5 rounded-xl">
                        <h3 class="text-base sm:text-xl font-bold text-slate-900 tracking-tight">
                            {{ $certificate->course_title }}
                        </h3>
                    </div>

                    @if ($certificate->grade)
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-mono font-bold border border-emerald-200">
                                <span>●</span> {{ $certificate->grade }}
                            </span>
                            <span>•</span>
                            <span>Issued {{ $certificate->issue_date->format('F d, Y') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Footer Signatures & QR Tech Strip -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-end justify-between gap-6">
                    <div class="flex items-center gap-8">
                        <div>
                            <div class="font-signature text-2xl sm:text-3xl text-slate-800 select-none h-8">
                                {{ $certificate->instructor_name ?: 'Course Mentor' }}
                            </div>
                            <div class="border-t border-slate-300 pt-1 mt-1 w-44">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $certificate->instructor_name ?: 'Course Instructor' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-mono">Lead Instructor</p>
                            </div>
                        </div>

                        <div>
                            <div class="font-signature text-2xl sm:text-3xl text-slate-800 select-none h-8">
                                {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                            </div>
                            <div class="border-t border-slate-300 pt-1 mt-1 w-44">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $template->signer_name ?? 'Dr. Alexander Wright' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-mono">{{ $template->signature_title ?? 'Director of Studies' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Verification Chip -->
                    <div class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <img src="{{ $certificate->qrCodeUrl() }}" alt="Scan to verify" class="w-12 h-12 object-contain bg-white p-0.5 rounded border border-slate-200">
                        <div class="text-[10px] font-mono leading-tight">
                            <span class="font-bold text-slate-800 block text-[11px]">SECURE VERIFIED</span>
                            <span class="text-slate-400">Scan to view registry</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    @elseif ($layoutType === 'elegant')
        <!-- --------------------------------------------------------------------- -->
        <!-- STYLE 3: ELEGANT ROYAL EXECUTIVE / GOLD EMBOSSED MASTERCLASS -->
        <!-- --------------------------------------------------------------------- -->
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-[#fdfbf7] shadow-2xl relative overflow-hidden flex flex-col justify-between p-8 sm:p-14 border-[12px] border-[#08131e] font-cormorant text-slate-900"
             style="background-color: {{ $bgColor !== '#ffffff' ? $bgColor : '#fdfbf7' }};">

            <!-- Delicate Inner Gold Filigree Inset Border -->
            <div class="absolute inset-3 border-2 pointer-events-none select-none" style="border-color: #d4af37;"></div>
            <div class="absolute inset-4 border border-dashed pointer-events-none select-none opacity-60" style="border-color: #d4af37;"></div>

            <!-- Ornate Golden Corner Embellishments -->
            <svg class="absolute top-5 left-5 w-12 h-12 text-[#d4af37] opacity-80" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/>
                <circle cx="20" cy="20" r="4" fill="#d4af37"/>
            </svg>
            <svg class="absolute top-5 right-5 w-12 h-12 text-[#d4af37] opacity-80 transform rotate-90" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/>
                <circle cx="20" cy="20" r="4" fill="#d4af37"/>
            </svg>
            <svg class="absolute bottom-5 left-5 w-12 h-12 text-[#d4af37] opacity-80 transform -rotate-90" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/>
                <circle cx="20" cy="20" r="4" fill="#d4af37"/>
            </svg>
            <svg class="absolute bottom-5 right-5 w-12 h-12 text-[#d4af37] opacity-80 transform rotate-180" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L40,0 C30,10 20,20 15,35 C10,50 10,70 0,100 L0,0 Z"/>
                <circle cx="20" cy="20" r="4" fill="#d4af37"/>
            </svg>

            <!-- Subtle Background Watermark Crest -->
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.035] pointer-events-none select-none">
                <svg class="w-[420px] h-[420px]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5zm0 2.18l7 3.89v4.93c0 4.54-3.14 8.79-7 9.92-3.86-1.13-7-5.38-7-9.92V8.07l7-3.89z"/>
                </svg>
            </div>

            <!-- Top Header Section -->
            <div class="text-center relative z-10 pt-2">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <span class="w-8 h-px bg-[#d4af37]"></span>
                    <span class="font-cinzel text-xs font-bold uppercase tracking-[0.35em] text-[#854d0e]">
                        THE SENATE OF MONO LMS ACADEMY
                    </span>
                    <span class="w-8 h-px bg-[#d4af37]"></span>
                </div>

                <h1 class="font-cinzel text-2xl sm:text-4xl lg:text-5xl font-black tracking-wide text-slate-900 uppercase">
                    Diploma of Executive Excellence
                </h1>
                <p class="font-playfair text-xs sm:text-sm text-slate-500 italic mt-0.5">
                    Conferred by Academic Authority for Outstanding Professional Accomplishment
                </p>
            </div>

            <!-- Presentation Body -->
            <div class="text-center my-auto py-4 relative z-10">
                <p class="font-cormorant text-sm sm:text-base italic text-slate-600 mb-1">
                    This distinguished credential proudly certifies that
                </p>

                <div class="inline-block relative px-8 sm:px-14 pb-2 border-b-2 border-[#d4af37]">
                    <h2 class="font-playfair text-3xl sm:text-5xl font-extrabold text-[#08131e] tracking-tight">
                        {{ $certificate->student_name }}
                    </h2>
                </div>

                <p class="text-xs sm:text-sm text-slate-700 max-w-2xl mx-auto mt-4 leading-relaxed font-sans">
                    {{ $template->subtitle ?: 'has satisfied all prescribed educational requirements, comprehensive assignments, and professional masterclass milestones for the accredited curriculum of' }}
                </p>

                <h3 class="font-cinzel text-lg sm:text-2xl lg:text-3xl font-extrabold mt-2.5 text-[#1b3b2b]" style="color: {{ $primaryColor }};">
                    {{ $certificate->course_title }}
                </h3>

                @if ($certificate->grade)
                    <div class="mt-2 text-xs font-serif font-bold text-slate-600 tracking-wider uppercase">
                        Standing: <span class="text-[#854d0e] font-mono">{{ $certificate->grade }}</span>
                    </div>
                @endif
            </div>

            <!-- Bottom Signatures & Regal Wax Seal -->
            <div class="relative z-10 pt-4 border-t border-slate-200/90 flex items-end justify-between gap-6">
                <!-- Instructor Signature -->
                <div class="text-center w-48 sm:w-56">
                    <div class="font-signature text-3xl sm:text-4xl text-[#08131e] select-none h-10 flex items-center justify-center">
                        {{ $certificate->instructor_name ?: 'Course Mentor' }}
                    </div>
                    <div class="border-t border-slate-400 pt-1 mt-1">
                        <p class="text-xs sm:text-sm font-bold text-slate-900">{{ $certificate->instructor_name ?: 'Course Instructor' }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-sans">Lead Faculty Instructor</p>
                    </div>
                </div>

                <!-- Center Regal Embossed Gold Seal -->
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="relative w-20 h-20 rounded-full flex items-center justify-center shadow-xl border-4 border-[#b48608] gold-foil-gradient">
                        <div class="w-16 h-16 rounded-full border border-dashed border-amber-900/60 flex flex-col items-center justify-center text-amber-950">
                            <span class="text-xl font-bold leading-none">★</span>
                            <span class="text-[7px] font-black uppercase tracking-widest leading-none mt-0.5">ACCREDITED</span>
                            <span class="text-[6px] font-bold text-amber-900 leading-none">EXCELLENCE</span>
                        </div>
                    </div>
                    <span class="text-[9px] font-mono font-bold text-slate-600 mt-1 block">
                        {{ $certificate->certificate_number }}
                    </span>
                </div>

                <!-- Right Signer & QR Verification -->
                <div class="flex items-end gap-3 text-right">
                    <div class="hidden sm:block shrink-0 bg-white p-1 border border-[#d4af37] shadow-2xs">
                        <img src="{{ $certificate->qrCodeUrl() }}" alt="Scan to verify" class="w-14 h-14 object-contain">
                    </div>
                    <div class="text-center w-48 sm:w-56">
                        <div class="font-signature-alt text-3xl sm:text-4xl text-[#08131e] select-none h-10 flex items-center justify-center">
                            {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                        </div>
                        <div class="border-t border-slate-400 pt-1 mt-1">
                            <p class="text-xs sm:text-sm font-bold text-slate-900">{{ $template->signer_name ?? 'Dr. Alexander Wright' }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-sans">{{ $template->signature_title ?? 'President of Faculty' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @else
        <!-- --------------------------------------------------------------------- -->
        <!-- STYLE 1: CLASSIC ACADEMIC / IVY LEAGUE DIPLOMA -->
        <!-- --------------------------------------------------------------------- -->
        <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-[#fdfbf7] p-8 sm:p-14 shadow-2xl relative overflow-hidden flex flex-col justify-between"
             style="background-color: {{ $bgColor !== '#ffffff' ? $bgColor : '#fdfbf7' }}; border: 12px double {{ $primaryColor }};">

            <!-- Ornate Double Inset Hairlines -->
            <div class="absolute inset-3 border border-slate-300 pointer-events-none select-none"></div>
            <div class="absolute inset-4 border-2 pointer-events-none select-none opacity-40" style="border-color: {{ $accentColor }};"></div>

            <!-- Traditional Ornate Acanthus Leaf Corner Brackets -->
            <svg class="absolute top-5 left-5 w-14 h-14 text-[{{ $primaryColor }}]" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/>
            </svg>
            <svg class="absolute top-5 right-5 w-14 h-14 text-[{{ $primaryColor }}] transform rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/>
            </svg>
            <svg class="absolute bottom-5 left-5 w-14 h-14 text-[{{ $primaryColor }}] transform -rotate-90" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/>
            </svg>
            <svg class="absolute bottom-5 right-5 w-14 h-14 text-[{{ $primaryColor }}] transform rotate-180" style="color: {{ $primaryColor }};" viewBox="0 0 100 100" fill="currentColor">
                <path d="M0,0 L60,0 C45,15 35,25 25,45 C15,65 15,85 0,100 L0,0 Z M12,12 C18,25 28,35 40,40 C35,28 25,18 12,12 Z"/>
            </svg>

            <!-- Subtle Seal Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
                <div class="w-96 h-96 rounded-full border-[18px] border-slate-900 flex items-center justify-center font-cinzel text-7xl font-bold">
                    MONO
                </div>
            </div>

            <!-- Top Header Section -->
            <div class="text-center relative z-10 pt-2">
                <div class="flex items-center justify-center gap-3 mb-1.5">
                    <span class="h-px w-16" style="background-color: {{ $primaryColor }};"></span>
                    <span class="font-cinzel text-xs font-bold uppercase tracking-[0.32em]" style="color: {{ $primaryColor }};">
                        MONO LMS ACADEMIC SENATE & REGISTRY
                    </span>
                    <span class="h-px w-16" style="background-color: {{ $primaryColor }};"></span>
                </div>

                <h1 class="font-cinzel text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-wide text-slate-900 uppercase">
                    Certificate of Achievement
                </h1>
                <p class="text-xs uppercase tracking-widest text-slate-500 font-semibold mt-1">
                    Accredited Recognition of Scholarly Accomplishment
                </p>
            </div>

            <!-- Center Student Recognition -->
            <div class="text-center my-auto py-4 relative z-10">
                <p class="font-cormorant text-sm sm:text-base italic text-slate-600 mb-1.5">
                    To all who shall read these letters, greetings. Be it known that
                </p>

                <div class="inline-block border-b-2 pb-2 px-10 sm:px-20 max-w-2xl" style="border-color: {{ $primaryColor }};">
                    <h2 class="font-playfair text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                        {{ $certificate->student_name }}
                    </h2>
                </div>

                <p class="text-xs sm:text-sm text-slate-600 max-w-2xl mx-auto mt-4 leading-relaxed font-sans">
                    {{ $template->subtitle ?: 'has completed the prescribed course of study and successfully demonstrated mastery in all curriculum assessments for' }}
                </p>

                <h3 class="font-cinzel text-lg sm:text-2xl lg:text-3xl font-extrabold mt-2 tracking-wide" style="color: {{ $primaryColor }};">
                    {{ $certificate->course_title }}
                </h3>

                @if ($certificate->grade)
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-2 font-mono">
                        Conferred with Academic Honors: <span class="text-emerald-800 font-extrabold">{{ $certificate->grade }}</span>
                    </p>
                @endif
            </div>

            <!-- Bottom Signatures & Seal -->
            <div class="relative z-10 pt-4 border-t border-slate-200/90 flex items-end justify-between gap-6">
                <!-- Instructor Signature -->
                <div class="text-center w-48 sm:w-56">
                    <div class="font-signature text-3xl sm:text-4xl text-slate-900 select-none h-10 flex items-center justify-center">
                        {{ $certificate->instructor_name ?: 'Course Mentor' }}
                    </div>
                    <div class="border-t border-slate-400 pt-1 mt-1">
                        <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $certificate->instructor_name ?: 'Course Instructor' }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-sans">Faculty Instructor</p>
                    </div>
                </div>

                <!-- Center 3D Gold Seal -->
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="relative w-20 h-20 rounded-full flex items-center justify-center shadow-xl border-4 border-amber-600 gold-foil-gradient">
                        <div class="w-16 h-16 rounded-full border border-dashed border-amber-900/60 flex flex-col items-center justify-center text-amber-950">
                            <span class="text-xl font-bold leading-none">★</span>
                            <span class="text-[7px] font-black uppercase tracking-widest leading-none mt-0.5">ACCREDITED</span>
                            <span class="text-[6px] font-bold text-amber-900 leading-none">VERIFIED</span>
                        </div>
                    </div>
                    <div class="text-[9px] font-mono font-bold text-slate-700 mt-1">
                        {{ $certificate->certificate_number }}
                    </div>
                    <div class="text-[8px] text-slate-400">
                        {{ $certificate->issue_date->format('M d, Y') }}
                    </div>
                </div>

                <!-- Academic Director & QR -->
                <div class="flex items-end gap-3 text-right">
                    @if ($template->show_qr_code ?? true)
                        <div class="hidden sm:block shrink-0 bg-white p-1 border border-slate-300 shadow-2xs">
                            <img src="{{ $certificate->qrCodeUrl() }}" alt="Scan to Verify" class="w-14 h-14 object-contain">
                        </div>
                    @endif
                    <div class="text-center w-48 sm:w-56">
                        <div class="font-signature text-3xl sm:text-4xl text-slate-900 select-none h-10 flex items-center justify-center">
                            {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                        </div>
                        <div class="border-t border-slate-400 pt-1 mt-1">
                            <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $template->signer_name ?? 'Dr. Alexander Wright' }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-sans">{{ $template->signature_title ?? 'Academic Dean' }}</p>
                        </div>
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
