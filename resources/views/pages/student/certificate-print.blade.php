<!DOCTYPE html>
<html lang="en" class="h-full w-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $certificate->certificate_number }} - Certificate of Achievement</title>
  <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Google Fonts matching the exact typography of the reference design -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700&family=Cormorant+Garamond:wght@500;600;700&family=Great+Vibes&family=Inter:wght@400;500;600;700&family=Libre+Baskerville:wght@400;700&family=Sacramento&display=swap" rel="stylesheet">

  <style>
    :root {
      --gold: #b68d59;
      --gold-light: #c7a477;
      --gold-dark: #7d5729;
      --charcoal: #1e1e1e;
      --sub-charcoal: #383634;
      --muted-grey: #87817a;
      --body-grey: #6b6763;
      --bg-cream: #fdfcfa;
    }

    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      font-family: 'Inter', sans-serif;
    }

    /* Scaling container so the exact 1120x792 certificate fits any screen seamlessly */
    .viewport-wrapper {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    .certificate-scaler {
      transform-origin: center center;
      transition: transform 0.15s ease;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45), 0 4px 16px rgba(0, 0, 0, 0.25);
    }

    /* Fixed-proportion canvas matching the exact aspect ratio of the reference image (1.414 : 1) */
    .certificate-canvas {
      position: relative;
      width: 1120px;
      height: 792px;
      background-color: var(--bg-cream);
      overflow: hidden;
      user-select: none;
    }

    /* Subtle Guilloche Wavy Background Pattern */
    .guilloche-bg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
      opacity: 0.42;
    }

    /* Ornamental Frame Layer */
    .frame-layer {
      position: absolute;
      inset: 18px;
      pointer-events: none;
      z-index: 2;
    }

    /* Main Certificate Content Layer */
    .content-layer {
      position: relative;
      z-index: 5;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 78px 110px 64px 110px;
      text-align: center;
    }

    /* Typography: CERTIFICATE */
    .cert-title {
      font-family: 'Libre Baskerville', 'Times New Roman', Georgia, serif;
      font-weight: 700;
      font-size: 74px;
      line-height: 1;
      letter-spacing: 0.03em;
      color: var(--charcoal);
      margin: 0;
      transform: scaleY(1.04);
    }

    /* Subtitle: OF ACHIEVEMENT */
    .subtitle-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 24px;
      margin-top: 22px;
      width: 100%;
    }

    .cert-subtitle {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-weight: 600;
      font-size: 30.5px;
      line-height: 1;
      letter-spacing: 0.04em;
      color: var(--sub-charcoal);
      text-transform: uppercase;
      margin: 0;
    }

    /* 4 Stars Row */
    .stars-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 17px;
      margin-top: 18px;
      color: #c29d6f;
    }

    /* THIS IS PROUDLY PRESENTED TO */
    .presented-to {
      font-family: 'Inter', sans-serif;
      font-weight: 500;
      font-size: 15.5px;
      letter-spacing: 0.145em;
      color: var(--muted-grey);
      text-transform: uppercase;
      margin-top: 40px;
    }

    /* Recipient Name in Engraved/Inline Calligraphic Script */
    .recipient-name-wrap {
      position: relative;
      margin-top: 18px;
      margin-bottom: 2px;
      display: inline-block;
    }

    .recipient-name {
      font-family: 'Alex Brush', 'Great Vibes', cursive;
      font-weight: 400;
      font-size: 114px;
      line-height: 0.92;
      color: var(--gold-dark);
      letter-spacing: 0.01em;
      padding: 0 18px;
      text-shadow:
        -1px 0px 0px var(--bg-cream),
        -2.2px 0.6px 0px rgba(125, 87, 41, 0.72);
    }

    /* Name underline divider */
    .name-divider {
      width: 560px;
      height: 18px;
      margin-top: 4px;
    }

    /* Body Paragraph */
    .cert-body {
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 15.8px;
      line-height: 1.72;
      color: var(--body-grey);
      max-width: 580px;
      margin-top: 28px;
      letter-spacing: 0.005em;
    }

    .cert-body strong {
      font-weight: 700;
      color: #54504c;
    }

    /* Footer: Date, Laurel Seal, Signature */
    .cert-footer {
      margin-top: auto;
      width: 100%;
      display: grid;
      grid-template-columns: 1fr 220px 1fr;
      align-items: center;
      padding: 0 42px;
      margin-bottom: 8px;
    }

    .footer-side {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .tapered-line {
      width: 184px;
      height: 2px;
      background: radial-gradient(ellipse at center, #b89160 0%, #cfb089 55%, rgba(207, 176, 137, 0) 100%);
      margin-bottom: 12px;
    }

    .date-text {
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 18.5px;
      color: #55514d;
      letter-spacing: 0.01em;
      min-height: 34px;
      display: flex;
      align-items: center;
    }

    .signature-text {
      font-family: 'Sacramento', cursive;
      font-weight: 400;
      font-size: 40px;
      line-height: 0.85;
      color: #4a4642;
      transform: rotate(-3deg);
      min-height: 34px;
      display: flex;
      align-items: center;
      letter-spacing: 0.01em;
    }

    .seal-container {
      display: flex;
      align-items: center;
      justify-content: center;
      transform: translateY(6px);
    }

    /* Print rules for clean full-page PDF / paper output */
    @media print {
      @page {
        size: A4 landscape;
        margin: 0;
      }
      body {
        background: #fdfcfa !important;
        overflow: visible !important;
      }
      .no-print {
        display: none !important;
      }
      .viewport-wrapper {
        padding: 0 !important;
        display: block !important;
        overflow: visible !important;
      }
      .certificate-scaler {
        transform: none !important;
        box-shadow: none !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
      }
      .certificate-canvas {
        width: 100vw !important;
        height: 100vh !important;
        max-width: 100vw !important;
        max-height: 100vh !important;
        page-break-inside: avoid;
      }
    }
  </style>
</head>
<body class="@if (request('embed')) bg-[#fdfcfa] @else bg-[#262523] @endif w-full h-full flex flex-col antialiased">

  @if (! request('embed'))
    <!-- Top Action Toolbar (Hidden when printing or in modal embed) -->
    <header class="no-print shrink-0 w-full z-30 bg-neutral-900/95 backdrop-blur-md border-b border-neutral-800 px-4 py-2.5 shadow-xl text-white">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <a href="{{ route('student.certificates') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-xs font-semibold transition-colors border border-neutral-700">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to My Certificates</span>
          </a>
          <div class="hidden sm:block">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-white font-mono">{{ $certificate->certificate_number }}</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                Official Credential
              </span>
            </div>
            <p class="text-[10px] text-neutral-400">Awarded to {{ $certificate->student_name }} on {{ $certificate->issue_date->format('F d, Y') }}</p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <button type="button"
                  onclick="copyVerificationLink('{{ $certificate->verificationUrl() }}')"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-xs font-semibold transition-colors border border-neutral-700 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <span id="copy-btn-text">Share Link</span>
          </button>

          <a href="{{ $certificate->verificationUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-xs font-semibold transition-colors border border-neutral-700">
            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Verify Registry</span>
          </a>

          <button type="button"
                  onclick="window.print()"
                  class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold uppercase tracking-wider shadow-lg transition-transform hover:scale-105 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Print / Save PDF</span>
          </button>
        </div>
      </div>
    </header>
  @endif

  <!-- Main Responsive Viewport -->
  <main class="viewport-wrapper flex-1">
    <div id="scaler" class="certificate-scaler">
      <div class="certificate-canvas" id="certificate">

        <!-- 1. SUBTLE DIAGONAL WAVY GUILLOCHE WATERMARK BACKGROUND -->
        <svg class="guilloche-bg" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
          <defs>
            <pattern id="wavyGuilloche" width="28" height="28" patternUnits="userSpaceOnUse" patternTransform="rotate(-35)">
              <path d="M 0 7 Q 7 2, 14 7 T 28 7" fill="none" stroke="#e5ddd0" stroke-width="0.9" />
              <path d="M 0 14 Q 7 9, 14 14 T 28 14" fill="none" stroke="#e5ddd0" stroke-width="0.9" />
              <path d="M 0 21 Q 7 16, 14 21 T 28 21" fill="none" stroke="#e5ddd0" stroke-width="0.9" />
              <path d="M 0 28 Q 7 23, 14 28 T 28 28" fill="none" stroke="#e5ddd0" stroke-width="0.9" />
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#wavyGuilloche)" />
        </svg>

        <!-- 2. ORNAMENTAL GOLD BORDER & CORNERS (1084 x 756 inside 1120 x 792) -->
        <svg class="frame-layer" viewBox="0 0 1084 756" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <!-- High-Precision Symmetrical Baroque Corner Filigree (Top-Left Base) -->
            <g id="cornerFiligree" fill="#b68d59">
              <!-- Outer corner triangular wedge -->
              <path d="M 2,2 L 29,2 C 21,10 10,21 2,29 Z" />

              <!-- Central 45-degree Fleur-de-Lis / Palmette Spine -->
              <path d="M 24,24 C 31,20 40,26 42,35 C 34,39 22,33 24,24 Z" />
              <path d="M 41,41 C 55,34 73,41 85,56 C 73,57 59,54 47,47 C 54,59 57,73 56,85 C 41,73 34,55 41,41 Z" />
              <path d="M 44,44 C 60,48 76,60 88,88 C 60,76 48,60 44,44 Z" />
              <path d="M 73,53 C 83,50 94,55 98,64 C 89,66 78,61 73,53 Z" />
              <path d="M 53,73 C 50,83 55,94 64,98 C 66,89 61,78 53,73 Z" />

              <!-- Top Horizontal Edge Scrollwork -->
              <path d="M 38,2 L 88,2 C 88,12 80,22 68,24 C 77,15 76,8 65,8 C 54,8 48,18 52,28 C 40,24 35,12 38,2 Z" />
              <path d="M 94,2 C 102,2 114,5 123,14 C 115,17 105,19 96,26 C 90,31 82,36 73,34 C 81,28 87,19 89,11 C 91,5 92,2 94,2 Z" />
              <path d="M 58,28 C 68,26 78,32 82,42 C 74,43 65,37 58,28 Z" />
              <path d="M 88,36 C 96,30 107,30 114,37 C 105,41 94,41 88,36 Z" />

              <!-- Left Vertical Edge Scrollwork (Exact reflection across x = y) -->
              <path d="M 2,38 L 2,88 C 12,88 22,80 24,68 C 15,77 8,76 8,65 C 8,54 18,48 28,52 C 24,40 12,35 2,38 Z" />
              <path d="M 2,94 C 2,102 5,114 14,123 C 17,115 19,105 26,96 C 31,90 36,82 34,73 C 28,81 19,87 11,89 C 5,91 2,92 2,94 Z" />
              <path d="M 28,58 C 26,68 32,78 42,82 C 43,74 37,65 28,58 Z" />
              <path d="M 36,88 C 30,96 30,107 37,114 C 41,105 41,94 36,88 Z" />
            </g>

            <!-- Ornamental Bar with Double-Lobe Knot & Diamond Finials (Width: 146px, centered on y=0) -->
            <g id="edgeKnotBar" fill="#b68d59">
              <!-- Left straight bar -->
              <rect x="0" y="-1.3" width="38" height="2.6" />
              <!-- Left outer small diamond -->
              <polygon points="37,0 44,-4.5 51,0 44,4.5" />
              <!-- Left inward flared spear/petal -->
              <path d="M 49,0 L 63,-8.5 C 66,-5 66,5 63,8.5 Z" />
              <!-- Central vertical double-lobed clover knot -->
              <circle cx="73" cy="-7.2" r="7.2" />
              <circle cx="73" cy="7.2" r="7.2" />
              <rect x="67" y="-7" width="12" height="14" />
              <!-- Right inward flared spear/petal -->
              <path d="M 97,0 L 83,-8.5 C 80,-5 80,5 83,8.5 Z" />
              <!-- Right outer small diamond -->
              <polygon points="95,0 102,-4.5 109,0 102,4.5" />
              <!-- Right straight bar -->
              <rect x="108" y="-1.3" width="38" height="2.6" />
            </g>

            <!-- Single 5-pointed star for the 4-star row -->
            <polygon id="smallStar" points="0,-6.5 1.9,-2 6.8,-1.5 3.1,1.7 4.2,6.5 0,3.9 -4.2,6.5 -3.1,1.7 -6.8,-1.5 -1.9,-2" fill="#c29d6f" />
          </defs>

          <!-- FOUR SYMMETRICAL CORNERS -->
          <!-- Top-Left -->
          <use href="#cornerFiligree" x="0" y="0" />
          <!-- Top-Right -->
          <use href="#cornerFiligree" x="0" y="0" transform="translate(1084, 0) scale(-1, 1)" />
          <!-- Bottom-Left -->
          <use href="#cornerFiligree" x="0" y="0" transform="translate(0, 756) scale(1, -1)" />
          <!-- Bottom-Right -->
          <use href="#cornerFiligree" x="0" y="0" transform="translate(1084, 756) scale(-1, -1)" />

          <!-- TOP BORDER: Left Knot Bar + Center Dotted Line + Right Knot Bar -->
          <use href="#edgeKnotBar" transform="translate(122, 16)" />
          <line x1="281" y1="16" x2="808" y2="16" stroke="#c5a173" stroke-width="3.6" stroke-linecap="round" stroke-dasharray="0.1 11.4" />
          <use href="#edgeKnotBar" transform="translate(962, 16) scale(-1, 1)" />

          <!-- BOTTOM BORDER: Left Knot Bar + Center Dotted Line + Right Knot Bar -->
          <use href="#edgeKnotBar" transform="translate(122, 740)" />
          <line x1="281" y1="740" x2="808" y2="740" stroke="#c5a173" stroke-width="3.6" stroke-linecap="round" stroke-dasharray="0.1 11.4" />
          <use href="#edgeKnotBar" transform="translate(962, 740) scale(-1, 1)" />

          <!-- LEFT VERTICAL BORDER: Top Knot + Solid Vertical Line + Bottom Knot -->
          <use href="#edgeKnotBar" transform="translate(14, 120) rotate(90)" />
          <line x1="14" y1="266" x2="14" y2="490" stroke="#b68d59" stroke-width="2.6" />
          <use href="#edgeKnotBar" transform="translate(14, 636) rotate(-90)" />

          <!-- RIGHT VERTICAL BORDER: Top Knot + Solid Vertical Line + Bottom Knot -->
          <use href="#edgeKnotBar" transform="translate(1070, 120) rotate(90)" />
          <line x1="1070" y1="266" x2="1070" y2="490" stroke="#b68d59" stroke-width="2.6" />
          <use href="#edgeKnotBar" transform="translate(1070, 636) rotate(-90)" />
        </svg>

        <!-- 3. CERTIFICATE INNER CONTENT LAYER -->
        <div class="content-layer">

          <!-- Main Title -->
          <h1 class="cert-title">CERTIFICATE</h1>

          <!-- Subtitle with Left & Right Diamond Finial Lines -->
          <div class="subtitle-row">
            <!-- Left Finial Line -->
            <svg width="86" height="14" viewBox="0 0 86 14" xmlns="http://www.w3.org/2000/svg">
              <polygon points="0,7 5,4 10,7 5,10" fill="#c29d6f" />
              <polygon points="8,7 15,2.5 22,7 15,11.5" fill="#c29d6f" />
              <line x1="20" y1="7" x2="86" y2="7" stroke="#c29d6f" stroke-width="1.6" />
            </svg>

            <h2 class="cert-subtitle">OF ACHIEVEMENT</h2>

            <!-- Right Finial Line -->
            <svg width="86" height="14" viewBox="0 0 86 14" xmlns="http://www.w3.org/2000/svg">
              <line x1="0" y1="7" x2="66" y2="7" stroke="#c29d6f" stroke-width="1.6" />
              <polygon points="64,7 71,2.5 78,7 71,11.5" fill="#c29d6f" />
              <polygon points="76,7 81,4 86,7 81,10" fill="#c29d6f" />
            </svg>
          </div>

          <!-- Four Gold Stars -->
          <div class="stars-row" aria-hidden="true">
            <svg width="98" height="16" viewBox="0 0 98 16" xmlns="http://www.w3.org/2000/svg">
              <use href="#smallStar" x="10" y="8" />
              <use href="#smallStar" x="36" y="8" />
              <use href="#smallStar" x="62" y="8" />
              <use href="#smallStar" x="88" y="8" />
            </svg>
          </div>

          <!-- Presented To -->
          <p class="presented-to">THIS IS PROUDLY PRESENTED TO</p>

          <!-- Recipient Name -->
          <div class="recipient-name-wrap">
            <div class="recipient-name">{{ $certificate->student_name }}</div>
          </div>

          <!-- Long Ornamental Divider under Name -->
          <svg class="name-divider" viewBox="0 0 560 18" xmlns="http://www.w3.org/2000/svg">
            <!-- Left double-diamond spear finial -->
            <polygon points="2,9 9,5.5 16,9 9,12.5" fill="#c5a278" />
            <path d="M 13,9 L 25,3.5 L 35,9 L 25,14.5 Z" fill="#c5a278" />
            <!-- Center horizontal line -->
            <line x1="33" y1="9" x2="527" y2="9" stroke="#c5a278" stroke-width="1.5" />
            <!-- Right double-diamond spear finial -->
            <path d="M 525,9 L 535,3.5 L 547,9 L 535,14.5 Z" fill="#c5a278" />
            <polygon points="544,9 551,5.5 558,9 551,12.5" fill="#c5a278" />
          </svg>

          <!-- Certificate Body Text -->
          <p class="cert-body">
            {{ $template->subtitle ?: 'This certificate acknowledges your outstanding contribution and dedication to successfully completing the curriculum of' }}<br />
            <strong>{{ $certificate->course_title }}</strong>@if($certificate->grade), showcasing commitment to excellence and distinguished honors ({{ $certificate->grade }}).@endif
          </p>

          <!-- Footer Row: Date | Best Award Laurel Wreath | Signature -->
          <div class="cert-footer">
            <!-- Left: Date -->
            <div class="footer-side">
              <div class="tapered-line"></div>
              <div class="date-text">{{ $certificate->issue_date->format('d M Y') }}</div>
              <div class="font-mono text-[9px] text-[#87817a] mt-0.5 tracking-wider">{{ $certificate->certificate_number }}</div>
            </div>

            <!-- Center: Best Award Laurel Wreath Emblem -->
            <div class="seal-container">
              <svg id="laurelSeal" width="166" height="166" viewBox="-90 -90 180 180" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <!-- 5-pointed star for inside emblem -->
                  <polygon id="emblemStar" points="0,-10 2.9,-3.1 10.3,-2.3 4.7,2.6 6.4,9.8 0,6 -6.4,9.8 -4.7,2.6 -10.3,-2.3 -2.9,-3.1" fill="#b68d59" />
                  <!-- Diamond for bottom of emblem -->
                  <polygon id="emblemDiamond" points="0,-6 4.6,0 0,6 -4.6,0" fill="#b68d59" />
                </defs>

                <!-- Dynamic Laurel Wreath Leaves rendered by JS for mathematical symmetry -->
                <g id="wreathLeaves" fill="#b68d59"></g>

                <!-- Two small stars at the bottom base of the wreath -->
                <use href="#emblemStar" transform="translate(-8.5, 67) scale(0.55)" />
                <use href="#emblemStar" transform="translate(8.5, 67) scale(0.55)" />

                <!-- Three stars above BEST AWARD -->
                <use href="#emblemStar" transform="translate(-19, -31) scale(0.58)" />
                <use href="#emblemStar" transform="translate(0, -39) scale(0.95)" />
                <use href="#emblemStar" transform="translate(19, -31) scale(0.58)" />

                <!-- BEST AWARD Text -->
                <text x="0" y="-6" text-anchor="middle" fill="#b68d59" font-family="'Cinzel', 'Libre Baskerville', Georgia, serif" font-weight="700" font-size="22" letter-spacing="1.2">BEST</text>
                <text x="0" y="16" text-anchor="middle" fill="#b68d59" font-family="'Cinzel', 'Libre Baskerville', Georgia, serif" font-weight="700" font-size="21" letter-spacing="0.8">AWARD</text>

                <!-- Three diamonds below BEST AWARD -->
                <use href="#emblemDiamond" transform="translate(-19, 32) scale(0.72)" />
                <use href="#emblemDiamond" transform="translate(0, 32) scale(1.05)" />
                <use href="#emblemDiamond" transform="translate(19, 32) scale(0.72)" />
              </svg>
            </div>

            <!-- Right: Signature -->
            <div class="footer-side">
              <div class="tapered-line"></div>
              <div class="signature-text">{{ $certificate->instructor_name ?: ($template->signer_name ?? 'Dr. Alexander Wright') }}</div>
              <div class="font-sans text-[10px] uppercase tracking-wider text-[#87817a] font-medium mt-0.5">{{ $template->signature_title ?? 'Program Director' }}</div>
            </div>
          </div>

        </div>

        <!-- Optional Cryptographic QR Verification Badge -->
        @if ($template->show_qr_code ?? true)
          <div class="absolute bottom-5 right-6 z-10 opacity-70 hover:opacity-100 transition-opacity flex items-center gap-1.5 bg-white/80 backdrop-blur-xs p-1 rounded border border-[#e5ddd0]">
            <img src="{{ $certificate->qrCodeUrl() }}" class="w-8 h-8 object-contain" alt="QR">
            <span class="text-[7px] font-mono text-slate-500 uppercase leading-none">VERIFIED<br>REGISTRY</span>
          </div>
        @endif

      </div>
    </div>
  </main>

  <script>
    /**
     * Generates the exact symmetrical Laurel Wreath leaves around the "BEST AWARD" seal
     */
    function buildLaurelWreath() {
      const container = document.getElementById('wreathLeaves');
      if (!container) return;

      const radius = 66;
      const steps = 11; // 11 leaf pairs per side
      const startAngleDeg = 72;  // Near bottom
      const endAngleDeg = -76;   // Near top

      let svgMarkup = '';

      // Create both Left (-1) and Right (+1) branches symmetrically
      [-1, 1].forEach((side) => {
        for (let i = 0; i <= steps; i++) {
          const t = i / steps;
          const deg = startAngleDeg + t * (endAngleDeg - startAngleDeg);
          const rad = (deg * Math.PI) / 180;

          const x = side * radius * Math.cos(rad);
          const y = radius * Math.sin(rad);

          // Tangent angle pointing upward along the wreath arc
          const tangentDeg = side === 1 ? deg - 90 : 90 - deg;

          // Taper leaf size slightly toward the top tip
          const scale = i === steps ? 0.52 : 1 - t * 0.34;

          if (i < steps) {
            // Outer leaf of the pair
            const outerAngle = tangentDeg + side * 28;
            svgMarkup += `
              <path d="M 0,0 C -3.8,-6 -3.5,-14 0,-20 C 3.5,-14 3.8,-6 0,0 Z"
                    transform="translate(${x.toFixed(2)}, ${y.toFixed(2)}) rotate(${outerAngle.toFixed(2)}) scale(${scale.toFixed(2)})" />
            `;

            // Inner leaf of the pair
            const innerAngle = tangentDeg - side * 26;
            svgMarkup += `
              <path d="M 0,0 C -3.6,-5.5 -3.3,-13 0,-18.5 C 3.3,-13 3.6,-5.5 0,0 Z"
                    transform="translate(${x.toFixed(2)}, ${y.toFixed(2)}) rotate(${innerAngle.toFixed(2)}) scale(${(scale * 0.92).toFixed(2)})" />
            `;
          } else {
            // Topmost single Horizontal/Inward Finial Leaf
            const topAngle = tangentDeg - side * 6;
            svgMarkup += `
              <path d="M 0,0 C -3.2,-5 -3,-12 0,-17 C 3,-12 3.2,-5 0,0 Z"
                    transform="translate(${x.toFixed(2)}, ${y.toFixed(2)}) rotate(${topAngle.toFixed(2)}) scale(${scale.toFixed(2)})" />
            `;
          }
        }
      });

      container.innerHTML = svgMarkup;
    }

    /**
     * Scales the fixed-size 1120x792 certificate cleanly so it fits any viewport
     */
    function fitCertificateToScreen() {
      const scaler = document.getElementById('scaler');
      if (!scaler) return;

      const certWidth = 1120;
      const certHeight = 792;
      const isEmbed = {{ request('embed') ? 'true' : 'false' }};

      if (isEmbed) {
        // Modal embed mode: iframe matches aspect-[1.414/1]
        const availWidth = window.innerWidth;
        const availHeight = window.innerHeight;
        if (availWidth <= 0 || availHeight <= 0) return;

        const scale = Math.min(availWidth / certWidth, availHeight / certHeight);
        scaler.style.transformOrigin = 'top left';
        scaler.style.transform = `scale(${scale})`;
        scaler.style.position = 'absolute';
        scaler.style.top = '0';
        scaler.style.left = '0';
        scaler.style.width = `${certWidth}px`;
        scaler.style.height = `${certHeight}px`;
        scaler.style.boxShadow = 'none';
      } else {
        // Standalone tab view
        const header = document.querySelector('header.no-print');
        const headerH = header ? header.offsetHeight : 0;
        const pad = window.innerWidth < 640 ? 12 : 24;
        const availWidth = Math.max(window.innerWidth - pad, 280);
        const availHeight = Math.max(window.innerHeight - headerH - pad, 200);

        const scale = Math.min(availWidth / certWidth, availHeight / certHeight);
        scaler.style.transformOrigin = 'center center';
        scaler.style.transform = `scale(${scale})`;
        scaler.style.position = 'relative';
        scaler.style.top = 'auto';
        scaler.style.left = 'auto';
        scaler.style.width = `${certWidth}px`;
        scaler.style.height = `${certHeight}px`;
      }
    }

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

    window.addEventListener('DOMContentLoaded', () => {
      buildLaurelWreath();
      fitCertificateToScreen();
    });

    window.addEventListener('load', () => {
      buildLaurelWreath();
      fitCertificateToScreen();
    });

    window.addEventListener('resize', fitCertificateToScreen);

    if (window.ResizeObserver) {
      const ro = new ResizeObserver(() => {
        fitCertificateToScreen();
      });
      ro.observe(document.body);
    }

    setTimeout(fitCertificateToScreen, 20);
    setTimeout(fitCertificateToScreen, 150);
    setTimeout(fitCertificateToScreen, 400);
  </script>
</body>
</html>
