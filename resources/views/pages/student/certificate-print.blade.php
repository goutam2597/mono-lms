<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $certificate->certificate_number }} - Certificate of Completion</title>
    <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Inter:wght@400;500;600;700&family=Alex+Brush&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .certificate-frame {
                box-shadow: none !important;
                border: 6px double {{ $template->border_color ?? '#344c3d' }} !important;
                max-width: 100% !important;
                height: 100vh !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
        .font-cinzel { font-family: 'Cinzel', Georgia, serif; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
        .font-signature { font-family: 'Alex Brush', cursive; }
    </style>
</head>
<body class="bg-slate-900 text-slate-800 min-h-screen py-8 px-4 flex flex-col items-center justify-center font-sans antialiased">

    <!-- Top Action Toolbar (Hidden in Print) -->
    <div class="no-print w-full max-w-5xl mb-6 flex flex-wrap items-center justify-between gap-4 bg-slate-800/90 backdrop-blur-md border border-slate-700/60 p-4 rounded-xl text-white shadow-xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.certificates') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-xs font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to My Certificates</span>
            </a>
            <div class="hidden sm:block">
                <p class="text-xs font-bold text-white">{{ $certificate->certificate_number }}</p>
                <p class="text-[11px] text-slate-400">Issued on {{ $certificate->issue_date->format('F d, Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button"
                    onclick="copyVerificationLink('{{ $certificate->verificationUrl() }}')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-xs font-semibold transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span id="copy-btn-text">Share Credential</span>
            </button>

            <a href="{{ $certificate->verificationUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-xs font-semibold transition-colors">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Verify Online</span>
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-bold uppercase tracking-wider shadow-lg transition-transform hover:scale-105 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print / Save PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Certificate Canvas (Standard A4 Landscape aspect ratio 1.414) -->
    <div class="certificate-frame w-full max-w-5xl aspect-[1.414/1] bg-white p-8 sm:p-14 shadow-2xl relative overflow-hidden flex flex-col justify-between"
         style="background-color: {{ $template->bg_color ?? '#ffffff' }}; border: 10px double {{ $template->border_color ?? '#344c3d' }};">

        <!-- Outer Corner Filigree Decorations -->
        <div class="absolute top-2 left-2 w-12 h-12 border-t-2 border-l-2 pointer-events-none" style="border-color: {{ $template->border_color ?? '#344c3d' }};"></div>
        <div class="absolute top-2 right-2 w-12 h-12 border-t-2 border-r-2 pointer-events-none" style="border-color: {{ $template->border_color ?? '#344c3d' }};"></div>
        <div class="absolute bottom-2 left-2 w-12 h-12 border-b-2 border-l-2 pointer-events-none" style="border-color: {{ $template->border_color ?? '#344c3d' }};"></div>
        <div class="absolute bottom-2 right-2 w-12 h-12 border-b-2 border-r-2 pointer-events-none" style="border-color: {{ $template->border_color ?? '#344c3d' }};"></div>

        <!-- Subtle Center Watermark Seal -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
            <div class="w-96 h-96 rounded-full border-[16px] border-slate-900 flex items-center justify-center font-cinzel text-7xl font-bold">
                MONO
            </div>
        </div>

        <!-- Top Header Section -->
        <div class="text-center relative z-10 pt-2">
            <div class="flex items-center justify-center gap-3 mb-2">
                <span class="h-px w-16" style="background-color: {{ $template->border_color ?? '#344c3d' }};"></span>
                <span class="font-cinzel text-xs sm:text-sm font-bold uppercase tracking-[0.28em]" style="color: {{ $template->border_color ?? '#344c3d' }};">
                    Mono LMS · Accredited Academy
                </span>
                <span class="h-px w-16" style="background-color: {{ $template->border_color ?? '#344c3d' }};"></span>
            </div>

            <h1 class="font-cinzel text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-wide text-slate-900 uppercase">
                Certificate of Achievement
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium tracking-wider uppercase mt-1">
                Official Credential of Course Mastery & Completion
            </p>
        </div>

        <!-- Center Presentation Section -->
        <div class="text-center my-auto py-4 relative z-10">
            <p class="font-playfair text-sm sm:text-base italic text-slate-600 mb-2">
                This certifies that
            </p>

            <div class="inline-block border-b-2 pb-2 px-8 sm:px-16 max-w-2xl" style="border-color: {{ $template->border_color ?? '#344c3d' }};">
                <h2 class="font-playfair text-2xl sm:text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight">
                    {{ $certificate->student_name }}
                </h2>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl mx-auto mt-4 leading-relaxed font-sans">
                {{ $template->subtitle ?: 'has successfully completed the comprehensive curriculum and rigorous practical assessments for' }}
            </p>

            <h3 class="font-cinzel text-lg sm:text-2xl lg:text-3xl font-bold mt-2" style="color: {{ $template->border_color ?? '#344c3d' }};">
                {{ $certificate->course_title }}
            </h3>

            @if ($certificate->grade)
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-2">
                    Graduation Standing: <span class="text-emerald-700 font-bold font-mono">{{ $certificate->grade }}</span>
                </p>
            @endif
        </div>

        <!-- Bottom Signatures & Seal Section -->
        <div class="relative z-10 pt-4 border-t border-slate-200/80 flex items-end justify-between gap-6">
            <!-- Left: Signer 1 (Instructor) -->
            <div class="text-center w-48 sm:w-56">
                <div class="font-signature text-2xl sm:text-3xl text-slate-800 h-9 flex items-center justify-center select-none">
                    {{ $certificate->instructor_name ?: 'Course Mentor' }}
                </div>
                <div class="border-t border-slate-400 pt-1 mt-1">
                    <p class="text-xs sm:text-sm font-bold text-slate-900">{{ $certificate->instructor_name ?: 'Course Instructor' }}</p>
                    <p class="text-[10px] uppercase tracking-wider text-slate-500">Lead Faculty Instructor</p>
                </div>
            </div>

            <!-- Center: Gold Foil Visual Seal & QR -->
            <div class="flex flex-col items-center text-center px-2">
                <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 flex items-center justify-center shadow-md mb-1.5"
                     style="border-color: #d97706; background: radial-gradient(circle, #fef3c7 0%, #fde68a 100%);">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full border border-dashed border-amber-600/60 flex flex-col items-center justify-center text-amber-800">
                        <span class="text-base sm:text-xl font-bold leading-none">★</span>
                        <span class="text-[7px] sm:text-[8px] font-bold uppercase tracking-widest leading-none mt-0.5">ACCREDITED</span>
                        <span class="text-[6px] text-amber-700 leading-none">VERIFIED</span>
                    </div>
                </div>

                <div class="text-[9px] font-mono font-bold tracking-wider text-slate-600">
                    ID: {{ $certificate->certificate_number }}
                </div>
                <div class="text-[8px] text-slate-400">
                    Issued: {{ $certificate->issue_date->format('M d, Y') }}
                </div>
            </div>

            <!-- Right: Signer 2 (Academic Director) & Verification QR -->
            <div class="flex items-end gap-3 text-right">
                @if ($template->show_qr_code ?? true)
                    <div class="hidden sm:block shrink-0 bg-white p-1 border border-slate-300 shadow-2xs">
                        <img src="{{ $certificate->qrCodeUrl() }}" alt="Scan to Verify" class="w-14 h-14 object-contain">
                    </div>
                @endif
                <div class="text-center w-48 sm:w-56">
                    <div class="font-signature text-2xl sm:text-3xl text-slate-800 h-9 flex items-center justify-center select-none">
                        {{ $template->signer_name ?? 'Dr. Alexander Wright' }}
                    </div>
                    <div class="border-t border-slate-400 pt-1 mt-1">
                        <p class="text-xs sm:text-sm font-bold text-slate-900">{{ $template->signer_name ?? 'Dr. Alexander Wright' }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500">{{ $template->signature_title ?? 'Program Director' }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function copyVerificationLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                var btn = document.getElementById('copy-btn-text');
                if (btn) {
                    var old = btn.textContent;
                    btn.textContent = 'Link Copied! ✓';
                    setTimeout(function() { btn.textContent = old; }, 2500);
                }
            });
        }
    </script>
</body>
</html>
