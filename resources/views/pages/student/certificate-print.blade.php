<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $certificate->certificate_number }} - Certificate of Achievement</title>
  <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">

  <!-- Google Fonts for both Certificate Designs -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Caveat:wght@600;700&family=Cinzel:wght@600;700&family=Cormorant+Garamond:wght@500;600;700&family=Great+Vibes&family=Inter:wght@400;500;600;700&family=Kaushan+Script&family=Libre+Baskerville:wght@400;700&family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sacramento&display=swap" rel="stylesheet">

  @php
    $layout = $template->template_type ?? 'template_3';
    $isTemplate7 = $layout === 'template_7';
  @endphp

  <style>
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
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      @if (request('embed'))
        background: transparent !important;
        overflow: hidden !important;
      @else
        background-color: {{ $isTemplate7 ? '#dde0e3' : '#262523' }};
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
      @endif
    }

    .viewport-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      @if (request('embed'))
        height: 100%;
        padding: 0;
        overflow: hidden;
      @else
        padding: 24px 16px 80px 16px;
        overflow: hidden;
      @endif
    }

    .certificate-scaler {
      transform-origin: center center;
      transition: transform 0.15s ease;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2), 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    /* Common Floating Action Control Bar */
    .floating-bar {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 100;
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(23, 23, 23, 0.92);
      backdrop-filter: blur(8px);
      padding: 8px 16px;
      border-radius: 9999px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: #e5e5e5;
      font-size: 12px;
    }

    .floating-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 9999px;
      background: #262626;
      color: #e5e5e5;
      text-decoration: none;
      font-weight: 500;
      font-size: 12px;
      border: none;
      cursor: pointer;
      transition: background-color 0.15s ease;
    }

    .floating-btn:hover {
      background: #333333;
    }

    .floating-btn-primary {
      background: #d97706;
      color: #ffffff;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(217, 119, 6, 0.35);
    }

    .floating-btn-primary:hover {
      background: #b45309;
    }

    .divider-dot {
      width: 3px;
      height: 3px;
      border-radius: 50%;
      background: #525252;
    }

    @media print {
      @page {
        size: landscape;
        margin: 0;
      }
      body {
        background: #ffffff !important;
      }
      .no-print {
        display: none !important;
      }
      .viewport-wrapper {
        padding: 0 !important;
      }
      .certificate-scaler {
        transform: none !important;
        box-shadow: none !important;
      }
    }

    /* -------------------------------------------------------------
       STYLES FOR TEMPLATE 7: CONTEMPORARY PRESTIGE AWARD
       ------------------------------------------------------------- */
    @if ($isTemplate7)
      :root {
        --outer-bg: #dde0e3;
        --card-bg: #ffffff;
        --dashed-border: #e6e3dd;
        --charcoal: #1e1c1a;
        --sub-charcoal: #4a4744;
        --muted-grey: #9b9690;
        --body-grey: #6e6a65;
        --script-bronze: #7c5121;
        --portrait-beige: #efe6dc;
      }

      .certificate-canvas-t7 {
        position: relative;
        width: 1060px;
        height: 750px;
        background-color: var(--card-bg);
        overflow: hidden;
        user-select: none;
      }

      .inset-dashed-frame {
        position: absolute;
        inset: 18px;
        border: 1.2px dashed var(--dashed-border);
        pointer-events: none;
        z-index: 20;
      }

      .top-wave-svg {
        position: absolute;
        top: 0;
        left: 0;
        width: 1060px;
        height: 230px;
        pointer-events: none;
        z-index: 4;
      }

      .left-column {
        position: absolute;
        left: 92px;
        top: 164px;
        width: 460px;
        z-index: 10;
        text-align: left;
      }

      .cert-label-t7 {
        font-family: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;
        font-weight: 600;
        font-size: 21px;
        line-height: 1.1;
        letter-spacing: 0.06em;
        color: var(--sub-charcoal);
        text-transform: uppercase;
        margin: 0;
      }

      .cert-main-heading-t7 {
        font-family: 'Libre Baskerville', 'Playfair Display', Georgia, serif;
        font-weight: 700;
        font-size: 45px;
        line-height: 1.08;
        letter-spacing: 0.03em;
        color: var(--charcoal);
        text-transform: uppercase;
        margin: 5px 0 0 0;
        transform: scaleY(1.03);
      }

      .presented-label-t7 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 500;
        font-size: 11.5px;
        line-height: 1.2;
        letter-spacing: 0.21em;
        color: var(--muted-grey);
        text-transform: uppercase;
        margin: 38px 0 0 0;
      }

      .recipient-wrapper-t7 {
        position: relative;
        margin-top: 4px;
        width: 412px;
        border-bottom: 1px solid #ebe8e2;
        padding-bottom: 2px;
      }

      .recipient-script-t7 {
        font-family: 'Caveat', 'Kaushan Script', cursive;
        font-weight: 700;
        font-size: 58px;
        line-height: 0.95;
        color: var(--script-bronze);
        letter-spacing: -0.01em;
        display: inline-block;
        transform: skewX(-6deg) translateY(3px);
        transform-origin: left bottom;
        white-space: nowrap;
      }

      .body-copy-t7 {
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 14.2px;
        line-height: 1.66;
        color: var(--body-grey);
        max-width: 440px;
        margin: 22px 0 0 0;
        letter-spacing: 0.003em;
      }

      .signature-block-t7 {
        position: absolute;
        left: 92px;
        bottom: 58px;
        z-index: 10;
        text-align: left;
      }

      .signature-line-wrap-t7 {
        position: relative;
        width: 140px;
        height: 44px;
        display: flex;
        align-items: center;
      }

      .signature-rule-t7 {
        position: absolute;
        left: 0;
        bottom: 11px;
        width: 130px;
        height: 1px;
        background-color: #dcd8d0;
        pointer-events: none;
      }

      .signature-handwriting-t7 {
        position: relative;
        z-index: 2;
        font-family: 'Sacramento', cursive;
        font-weight: 400;
        font-size: 34px;
        line-height: 1;
        color: #1d1b19;
        transform: rotate(-3deg) translateY(1px);
        white-space: nowrap;
      }

      .signature-role-t7 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 600;
        font-size: 9.5px;
        letter-spacing: 0.1em;
        color: #5a5652;
        text-transform: uppercase;
        margin-top: 2px;
      }

      .portrait-area-t7 {
        position: absolute;
        right: 142px;
        top: 184px;
        width: 316px;
        height: 382px;
        z-index: 8;
      }

      .portrait-dashed-ring-t7 {
        position: absolute;
        left: -8px;
        bottom: -11px;
        width: 332px;
        height: 332px;
        border-radius: 50%;
        border: 1.3px dashed #e6e1d8;
        pointer-events: none;
        z-index: 1;
        clip-path: polygon(0% 18%, 100% 18%, 100% 100%, 0% 100%);
      }

      .portrait-beige-circle-t7 {
        position: absolute;
        left: 4px;
        bottom: 1px;
        width: 308px;
        height: 308px;
        border-radius: 50%;
        background: radial-gradient(circle at 50% 42%, #f5ede4 0%, #ede3d7 70%, #e5d9cb 100%);
        overflow: hidden;
        z-index: 2;
      }

      .portrait-popout-mask-t7 {
        position: absolute;
        left: 4px;
        bottom: 1px;
        width: 308px;
        height: 372px;
        z-index: 3;
        border-bottom-left-radius: 154px;
        border-bottom-right-radius: 154px;
        border-top-left-radius: 140px;
        border-top-right-radius: 140px;
        overflow: hidden;
      }

      .portrait-popout-mask-t7 img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
      }

      .gold-badge-wrapper-t7 {
        position: absolute;
        left: -62px;
        top: -12px;
        width: 196px;
        height: 196px;
        z-index: 12;
        filter: drop-shadow(-10px 14px 16px rgba(35, 25, 10, 0.17));
        pointer-events: none;
      }

      .qr-date-block-t7 {
        position: absolute;
        right: 86px;
        bottom: 58px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
      }

      .qr-box-t7 {
        width: 56px;
        height: 56px;
        margin-bottom: 14px;
      }

      .presented-on-label-t7 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 500;
        font-size: 8.5px;
        letter-spacing: 0.08em;
        color: #8d8882;
        text-transform: uppercase;
        line-height: 1.2;
      }

      .presented-date-t7 {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 14.5px;
        color: #2c2a28;
        margin-top: 3px;
        letter-spacing: -0.01em;
      }
    @else
      /* -------------------------------------------------------------
         STYLES FOR TEMPLATE 3: HERITAGE GRAND ACHIEVEMENT
         ------------------------------------------------------------- */
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

      .certificate-canvas-t3 {
        position: relative;
        width: 1120px;
        height: 792px;
        background-color: var(--bg-cream);
        overflow: hidden;
        user-select: none;
      }

      .guilloche-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
        opacity: 0.42;
      }

      .frame-layer {
        position: absolute;
        inset: 18px;
        pointer-events: none;
        z-index: 2;
      }

      .content-layer {
        position: relative;
        z-index: 5;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 72px 100px 58px 100px;
        text-align: center;
      }

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

      .subtitle-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        margin-top: 18px;
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

      .stars-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 17px;
        margin-top: 14px;
        color: #c29d6f;
      }

      .presented-to {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 15.5px;
        letter-spacing: 0.145em;
        color: var(--muted-grey);
        text-transform: uppercase;
        margin-top: 24px;
        margin-bottom: 0;
      }

      .recipient-name-wrap {
        position: relative;
        margin-top: 12px;
        margin-bottom: 2px;
        display: inline-block;
        max-width: 820px;
      }

      .recipient-name {
        font-family: 'Alex Brush', 'Great Vibes', cursive;
        font-weight: 400;
        font-size: 76px;
        line-height: 1;
        color: var(--gold-dark);
        letter-spacing: 0.01em;
        padding: 0 18px;
        white-space: nowrap;
        text-shadow:
          -1px 0px 0px var(--bg-cream),
          -2.2px 0.6px 0px rgba(125, 87, 41, 0.72);
      }

      .name-divider {
        width: 560px;
        height: 18px;
        margin-top: 4px;
      }

      .cert-body {
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 15.2px;
        line-height: 1.65;
        color: var(--body-grey);
        max-width: 660px;
        margin-top: 16px;
        margin-bottom: 0;
        letter-spacing: 0.005em;
      }

      .cert-body strong {
        font-weight: 700;
        color: #54504c;
      }

      .cert-footer {
        margin-top: auto;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 220px 1fr;
        align-items: center;
        padding: 0 42px;
        margin-bottom: 6px;
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
        margin-bottom: 10px;
      }

      .date-text {
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 18.5px;
        color: #55514d;
        letter-spacing: 0.01em;
        min-height: 30px;
        display: flex;
        align-items: center;
      }

      .signature-text {
        font-family: 'Sacramento', cursive;
        font-weight: 400;
        font-size: 34px;
        line-height: 1;
        color: #4a4642;
        transform: rotate(-3deg);
        min-height: 30px;
        display: flex;
        align-items: center;
        letter-spacing: 0.01em;
        white-space: nowrap;
      }

      .seal-container {
        display: flex;
        align-items: center;
        justify-content: center;
        transform: translateY(4px);
      }
    @endif
  </style>
</head>
<body>

  <!-- Main Responsive Viewport -->
  <div class="viewport-wrapper">
    <div id="scaler" class="certificate-scaler">

      @if ($isTemplate7)
        <!-- =========================================================
             TEMPLATE 7: CONTEMPORARY PRESTIGE AWARD
             ========================================================= -->
        <div class="certificate-canvas-t7" id="certificate">
          <!-- Subtle inset dashed border -->
          <div class="inset-dashed-frame"></div>

          <!-- Top Metallic Gold Ribbon Wave & Guilloche Lines -->
          <svg class="top-wave-svg" viewBox="0 0 1060 230" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="topLeftIvoryGrad" x1="0%" y1="0%" x2="85%" y2="85%">
                <stop offset="0%" stop-color="#e5e1d8" />
                <stop offset="45%" stop-color="#efece4" />
                <stop offset="100%" stop-color="#fbfaf7" />
              </linearGradient>

              <linearGradient id="goldRibbonMain" x1="0%" y1="80%" x2="95%" y2="0%">
                <stop offset="0%" stop-color="#693e0a" />
                <stop offset="14%" stop-color="#9e681c" />
                <stop offset="32%" stop-color="#e2b457" />
                <stop offset="48%" stop-color="#fae08c" />
                <stop offset="63%" stop-color="#eab95a" />
                <stop offset="79%" stop-color="#b27922" />
                <stop offset="92%" stop-color="#7c4b0e" />
                <stop offset="100%" stop-color="#9b671e" />
              </linearGradient>

              <linearGradient id="goldRibbonShadowFold" x1="0%" y1="100%" x2="80%" y2="0%">
                <stop offset="0%" stop-color="#5d3607" />
                <stop offset="30%" stop-color="#8b5612" />
                <stop offset="62%" stop-color="#cfa044" />
                <stop offset="82%" stop-color="#925c16" />
                <stop offset="100%" stop-color="#653b08" />
              </linearGradient>

              <linearGradient id="goldRibbonHighlight" x1="5%" y1="70%" x2="95%" y2="0%">
                <stop offset="0%" stop-color="#855110" />
                <stop offset="22%" stop-color="#d8a444" />
                <stop offset="45%" stop-color="#fdf0b4" />
                <stop offset="68%" stop-color="#f3cd73" />
                <stop offset="88%" stop-color="#d29c3c" />
                <stop offset="100%" stop-color="#a56e1d" />
              </linearGradient>

              <linearGradient id="contourLineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#b87e22" stop-opacity="0.15" />
                <stop offset="14%" stop-color="#d39e42" stop-opacity="0.75" />
                <stop offset="50%" stop-color="#deb15d" stop-opacity="0.88" />
                <stop offset="82%" stop-color="#e4be73" stop-opacity="0.65" />
                <stop offset="100%" stop-color="#edd296" stop-opacity="0.18" />
              </linearGradient>
            </defs>

            <path d="M 0,0 L 560,0 C 420,40 165,42 0,152 Z" fill="url(#topLeftIvoryGrad)" />
            <g id="guillocheWaveLines"></g>

            <path d="M 0,152 C 68,82 178,34 338,0 L 548,0 C 462,36 376,58 242,66 C 122,74 48,106 0,152 Z" fill="url(#goldRibbonShadowFold)" />
            <path d="M 32,126 C 118,58 248,18 378,0 L 614,0 C 554,48 476,72 342,76 C 204,80 96,88 32,126 Z" fill="url(#goldRibbonMain)" />
            <path d="M 86,98 C 178,56 306,42 436,24 C 506,14 558,6 614,0 C 556,48 482,73 348,77 C 224,81 142,82 86,98 Z" fill="url(#goldRibbonHighlight)" opacity="0.92" />
            <path d="M 412,0 L 542,0 C 488,24 434,36 368,38 C 386,22 398,10 412,0 Z" fill="#6e4109" opacity="0.55" />
          </svg>

          <!-- Left Column: Headings, Recipient Name, Body Text -->
          <div class="left-column">
            <h2 class="cert-label-t7">CERTIFICATE OF</h2>
            <h1 class="cert-main-heading-t7">ACHIEVEMENT</h1>

            <p class="presented-label-t7">THIS IS PROUDLY PRESENTED TO</p>

            <div class="recipient-wrapper-t7">
              <div class="recipient-script-t7">{{ $certificate->student_name }}</div>
            </div>

            <p class="body-copy-t7">
              {{ $template->subtitle ?: 'This certificate acknowledges your outstanding contribution and dedication to successfully completing the curriculum of' }}<br />
              <strong>{{ $certificate->course_title }}</strong>@if($certificate->grade), showcasing commitment to excellence and distinguished honors ({{ $certificate->grade }}).@endif
            </p>
          </div>

          <!-- Bottom-Left Signature Block -->
          <div class="signature-block-t7">
            <div class="signature-line-wrap-t7">
              <div class="signature-rule-t7"></div>
              <div class="signature-handwriting-t7">{{ $certificate->instructor_name ?: ($template->signer_name ?? 'Dr. Julian Vance') }}</div>
            </div>
            <div class="signature-role-t7">{{ $template->signature_title ?? 'MANAGER, CTO' }}</div>
          </div>

          <!-- Right Column: Student Avatar Portrait & 3D Gold Rosette Seal -->
          <div class="portrait-area-t7">
            <div class="portrait-dashed-ring-t7"></div>
            <div class="portrait-beige-circle-t7"></div>

            <div class="portrait-popout-mask-t7">
              <img
                id="portraitImg"
                src="{{ $certificate->studentAvatarUrl() }}"
                alt="{{ $certificate->student_name }}"
                onerror="this.style.display='none'; document.getElementById('defaultPortraitSvg').style.display='block';"
              />

              <!-- High-detail studio portrait SVG fallback -->
              <svg id="defaultPortraitSvg" class="portrait-svg-render" viewBox="0 0 308 372" xmlns="http://www.w3.org/2000/svg" style="display: none; width: 100%; height: 100%;">
                <defs>
                  <radialGradient id="faceSkinGrad" cx="48%" cy="44%" r="56%">
                    <stop offset="0%" stop-color="#fce7dc" />
                    <stop offset="55%" stop-color="#f3d1c0" />
                    <stop offset="88%" stop-color="#e3b8a3" />
                    <stop offset="100%" stop-color="#d2a18b" />
                  </radialGradient>
                  <linearGradient id="neckSkinGrad" x1="20%" y1="0%" x2="80%" y2="100%">
                    <stop offset="0%" stop-color="#deb09b" />
                    <stop offset="35%" stop-color="#edd0c0" />
                    <stop offset="75%" stop-color="#e2bda9" />
                    <stop offset="100%" stop-color="#c99c86" />
                  </linearGradient>
                  <radialGradient id="cheekBlush" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#e89587" stop-opacity="0.42" />
                    <stop offset="100%" stop-color="#e89587" stop-opacity="0" />
                  </radialGradient>
                  <linearGradient id="hairBaseGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#2b1b14" />
                    <stop offset="45%" stop-color="#1e130e" />
                    <stop offset="100%" stop-color="#140c09" />
                  </linearGradient>
                  <linearGradient id="hairHighlightGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#5a3827" />
                    <stop offset="50%" stop-color="#3d2418" />
                    <stop offset="100%" stop-color="#23140e" />
                  </linearGradient>
                  <linearGradient id="blackSweaterGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#16181c" />
                    <stop offset="50%" stop-color="#0d0e11" />
                    <stop offset="100%" stop-color="#070809" />
                  </linearGradient>
                </defs>
                <path d="M 88,195 C 64,175 56,138 66,102 C 74,72 86,42 114,24 C 142,8 182,8 210,26 C 238,44 252,82 256,118 C 262,158 254,194 234,216 Z" fill="url(#hairBaseGrad)" />
                <path d="M 118,192 L 114,248 C 114,274 196,274 196,248 L 190,192 Z" fill="url(#neckSkinGrad)" />
                <path d="M -10,256 C 22,226 68,212 104,208 C 116,248 136,264 156,264 C 178,264 196,246 206,210 C 242,216 286,232 318,262 L 318,380 L -10,380 Z" fill="url(#blackSweaterGrad)" />
                <g transform="rotate(3, 156, 126)">
                  <path d="M 104,96 C 104,54 128,36 156,36 C 184,36 208,54 208,96 C 208,142 192,182 158,196 C 124,182 104,142 104,96 Z" fill="url(#faceSkinGrad)" />
                  <circle cx="124" cy="134" r="20" fill="url(#cheekBlush)" />
                  <circle cx="188" cy="134" r="20" fill="url(#cheekBlush)" />
                  <path d="M 155,98 C 154,112 153,124 151,132 C 151,137 156,140 160,138 C 164,137 166,134 165,131" fill="none" stroke="#cfa08c" stroke-width="2.2" stroke-linecap="round" />
                  <path d="M 115,94 C 123,88 136,88 145,93" fill="none" stroke="#332018" stroke-width="3.8" stroke-linecap="round" />
                  <path d="M 168,93 C 177,88 190,88 198,95" fill="none" stroke="#332018" stroke-width="3.8" stroke-linecap="round" />
                  <circle cx="131" cy="105" r="4.6" fill="#2b1b14" />
                  <circle cx="181.5" cy="105" r="4.6" fill="#2b1b14" />
                  <path d="M 129,151 C 138,148 148,149 157,150 C 166,149 176,148 185,151 C 179,169 169,176 157,176 C 145,176 135,169 129,151 Z" fill="#c9655d" />
                  <path d="M 133,153 C 145,152 169,152 181,153 C 177,163 168,167 157,167 C 146,167 137,163 133,153 Z" fill="#fcfaf7" />
                </g>
              </svg>
            </div>

            <!-- 3D Metallic Gold Rosette Seal Badge ("BEST AWARD") -->
            <div class="gold-badge-wrapper-t7">
              <svg viewBox="-100 -100 200 200" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <linearGradient id="rosetteOuterGrad" x1="-80%" y1="-80%" x2="80%" y2="80%">
                    <stop offset="0%" stop-color="#8b5814" />
                    <stop offset="18%" stop-color="#d29b39" />
                    <stop offset="36%" stop-color="#fce494" />
                    <stop offset="52%" stop-color="#cfa349" />
                    <stop offset="70%" stop-color="#8f5b15" />
                    <stop offset="86%" stop-color="#e5b85c" />
                    <stop offset="100%" stop-color="#76470c" />
                  </linearGradient>

                  <linearGradient id="rosetteInnerBevel" x1="80%" y1="-80%" x2="-80%" y2="80%">
                    <stop offset="0%" stop-color="#7b4b0e" />
                    <stop offset="25%" stop-color="#f7dd86" />
                    <stop offset="50%" stop-color="#b88025" />
                    <stop offset="78%" stop-color="#fae599" />
                    <stop offset="100%" stop-color="#865311" />
                  </linearGradient>

                  <linearGradient id="goldRimGrad" x1="0%" y1="-100%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#fce699" />
                    <stop offset="30%" stop-color="#c68e30" />
                    <stop offset="70%" stop-color="#855110" />
                    <stop offset="100%" stop-color="#e7ba60" />
                  </linearGradient>

                  <radialGradient id="ivoryMedallionGrad" cx="38%" cy="32%" r="70%">
                    <stop offset="0%" stop-color="#ffffff" />
                    <stop offset="55%" stop-color="#fdf8ec" />
                    <stop offset="85%" stop-color="#f4e6ca" />
                    <stop offset="100%" stop-color="#e7d2aa" />
                  </radialGradient>

                  <linearGradient id="badgeTextGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#9b661b" />
                    <stop offset="45%" stop-color="#d49c3d" />
                    <stop offset="100%" stop-color="#875412" />
                  </linearGradient>

                  <polygon id="sealStar" points="0,-6.2 1.8,-1.9 6.4,-1.4 2.9,1.6 3.9,6.1 0,3.7 -3.9,6.1 -2.9,1.6 -6.4,-1.4 -1.8,-1.9" fill="url(#badgeTextGrad)" />
                </defs>

                <path id="rosetteOuterPath" fill="url(#rosetteOuterGrad)" />
                <path id="rosetteMiddlePath" fill="url(#rosetteInnerBevel)" opacity="0.92" />
                <path id="rosetteRidgePath" fill="none" stroke="#fef0b8" stroke-width="1.1" opacity="0.65" />

                <circle cx="0" cy="0" r="65" fill="url(#goldRimGrad)" />
                <circle cx="0" cy="0" r="61.5" fill="url(#rosetteOuterGrad)" />
                <circle cx="0" cy="0" r="57.5" fill="url(#ivoryMedallionGrad)" />
                <circle cx="0" cy="0" r="51.5" fill="none" stroke="#d5a44b" stroke-width="1" />
                <circle cx="0" cy="0" r="47.2" fill="none" stroke="#c9963e" stroke-width="1.8" stroke-linecap="round" stroke-dasharray="0.1 6.74" />

                <use href="#sealStar" transform="translate(-16, -27.5) rotate(-18) scale(0.62)" />
                <use href="#sealStar" transform="translate(0, -31.5) scale(1.05)" />
                <use href="#sealStar" transform="translate(16, -27.5) rotate(18) scale(0.62)" />

                <text x="0" y="-4" text-anchor="middle" fill="url(#badgeTextGrad)" font-family="'Playfair Display', 'Cinzel', Georgia, serif" font-weight="700" font-size="19.5" letter-spacing="1.2">BEST</text>
                <text x="0" y="15" text-anchor="middle" fill="url(#badgeTextGrad)" font-family="'Playfair Display', 'Cinzel', Georgia, serif" font-weight="700" font-size="18.5" letter-spacing="0.8">AWARD</text>

                <use href="#sealStar" transform="translate(-16, 30) rotate(18) scale(0.58)" />
                <use href="#sealStar" transform="translate(0, 33.5) scale(0.88)" />
                <use href="#sealStar" transform="translate(16, 30) rotate(-18) scale(0.58)" />
              </svg>
            </div>
          </div>

          <!-- Bottom-Right QR Code & Presented Date Block -->
          <div class="qr-date-block-t7">
            <div class="qr-box-t7">
              <img src="{{ $certificate->qrCodeUrl() }}" style="width: 100%; height: 100%; object-fit: contain; display: block;" alt="QR Code" />
            </div>
            <div class="presented-on-label-t7">PRESENTED ON</div>
            <div class="presented-date-t7">{{ $certificate->issue_date->format('d M Y') }}</div>
          </div>
        </div>

      @else
        <!-- =========================================================
             TEMPLATE 3: HERITAGE GRAND ACHIEVEMENT
             ========================================================= -->
        <div class="certificate-canvas-t3" id="certificate">
          <!-- Subtle diagonal wavy guilloche watermark background -->
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

          <!-- Ornamental gold border & corners -->
          <svg class="frame-layer" viewBox="0 0 1084 756" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <g id="cornerFiligree" fill="#b68d59">
                <path d="M 2,2 L 29,2 C 21,10 10,21 2,29 Z" />
                <path d="M 24,24 C 31,20 40,26 42,35 C 34,39 22,33 24,24 Z" />
                <path d="M 41,41 C 55,34 73,41 85,56 C 73,57 59,54 47,47 C 54,59 57,73 56,85 C 41,73 34,55 41,41 Z" />
                <path d="M 44,44 C 60,48 76,60 88,88 C 60,76 48,60 44,44 Z" />
                <path d="M 73,53 C 83,50 94,55 98,64 C 89,66 78,61 73,53 Z" />
                <path d="M 53,73 C 50,83 55,94 64,98 C 66,89 61,78 53,73 Z" />
                <path d="M 38,2 L 88,2 C 88,12 80,22 68,24 C 77,15 76,8 65,8 C 54,8 48,18 52,28 C 40,24 35,12 38,2 Z" />
                <path d="M 94,2 C 102,2 114,5 123,14 C 115,17 105,19 96,26 C 90,31 82,36 73,34 C 81,28 87,19 89,11 C 91,5 92,2 94,2 Z" />
                <path d="M 58,28 C 68,26 78,32 82,42 C 74,43 65,37 58,28 Z" />
                <path d="M 88,36 C 96,30 107,30 114,37 C 105,41 94,41 88,36 Z" />
                <path d="M 2,38 L 2,88 C 12,88 22,80 24,68 C 15,77 8,76 8,65 C 8,54 18,48 28,52 C 24,40 12,35 2,38 Z" />
                <path d="M 2,94 C 2,102 5,114 14,123 C 17,115 19,105 26,96 C 31,90 36,82 34,73 C 28,81 19,87 11,89 C 5,91 2,92 2,94 Z" />
                <path d="M 28,58 C 26,68 32,78 42,82 C 43,74 37,65 28,58 Z" />
                <path d="M 36,88 C 30,96 30,107 37,114 C 41,105 41,94 36,88 Z" />
              </g>

              <g id="edgeKnotBar" fill="#b68d59">
                <rect x="0" y="-1.3" width="38" height="2.6" />
                <polygon points="37,0 44,-4.5 51,0 44,4.5" />
                <path d="M 49,0 L 63,-8.5 C 66,-5 66,5 63,8.5 Z" />
                <circle cx="73" cy="-7.2" r="7.2" />
                <circle cx="73" cy="7.2" r="7.2" />
                <rect x="67" y="-7" width="12" height="14" />
                <path d="M 97,0 L 83,-8.5 C 80,-5 80,5 83,8.5 Z" />
                <polygon points="95,0 102,-4.5 109,0 102,4.5" />
                <rect x="108" y="-1.3" width="38" height="2.6" />
              </g>

              <polygon id="smallStar" points="0,-6.5 1.9,-2 6.8,-1.5 3.1,1.7 4.2,6.5 0,3.9 -4.2,6.5 -3.1,1.7 -6.8,-1.5 -1.9,-2" fill="#c29d6f" />
            </defs>

            <use href="#cornerFiligree" x="0" y="0" />
            <use href="#cornerFiligree" x="0" y="0" transform="translate(1084, 0) scale(-1, 1)" />
            <use href="#cornerFiligree" x="0" y="0" transform="translate(0, 756) scale(1, -1)" />
            <use href="#cornerFiligree" x="0" y="0" transform="translate(1084, 756) scale(-1, -1)" />

            <use href="#edgeKnotBar" transform="translate(122, 16)" />
            <line x1="281" y1="16" x2="808" y2="16" stroke="#c5a173" stroke-width="3.6" stroke-linecap="round" stroke-dasharray="0.1 11.4" />
            <use href="#edgeKnotBar" transform="translate(962, 16) scale(-1, 1)" />

            <use href="#edgeKnotBar" transform="translate(122, 740)" />
            <line x1="281" y1="740" x2="808" y2="740" stroke="#c5a173" stroke-width="3.6" stroke-linecap="round" stroke-dasharray="0.1 11.4" />
            <use href="#edgeKnotBar" transform="translate(962, 740) scale(-1, 1)" />

            <use href="#edgeKnotBar" transform="translate(14, 120) rotate(90)" />
            <line x1="14" y1="266" x2="14" y2="490" stroke="#b68d59" stroke-width="2.6" />
            <use href="#edgeKnotBar" transform="translate(14, 636) rotate(-90)" />

            <use href="#edgeKnotBar" transform="translate(1070, 120) rotate(90)" />
            <line x1="1070" y1="266" x2="1070" y2="490" stroke="#b68d59" stroke-width="2.6" />
            <use href="#edgeKnotBar" transform="translate(1070, 636) rotate(-90)" />
          </svg>

          <!-- Inner Content Layer -->
          <div class="content-layer">
            <h1 class="cert-title">CERTIFICATE</h1>

            <div class="subtitle-row">
              <svg width="86" height="14" viewBox="0 0 86 14" xmlns="http://www.w3.org/2000/svg">
                <polygon points="0,7 5,4 10,7 5,10" fill="#c29d6f" />
                <polygon points="8,7 15,2.5 22,7 15,11.5" fill="#c29d6f" />
                <line x1="20" y1="7" x2="86" y2="7" stroke="#c29d6f" stroke-width="1.6" />
              </svg>

              <h2 class="cert-subtitle">OF ACHIEVEMENT</h2>

              <svg width="86" height="14" viewBox="0 0 86 14" xmlns="http://www.w3.org/2000/svg">
                <line x1="0" y1="7" x2="66" y2="7" stroke="#c29d6f" stroke-width="1.6" />
                <polygon points="64,7 71,2.5 78,7 71,11.5" fill="#c29d6f" />
                <polygon points="76,7 81,4 86,7 81,10" fill="#c29d6f" />
              </svg>
            </div>

            <div class="stars-row" aria-hidden="true">
              <svg width="98" height="16" viewBox="0 0 98 16" xmlns="http://www.w3.org/2000/svg">
                <use href="#smallStar" x="10" y="8" />
                <use href="#smallStar" x="36" y="8" />
                <use href="#smallStar" x="62" y="8" />
                <use href="#smallStar" x="88" y="8" />
              </svg>
            </div>

            <p class="presented-to">THIS IS PROUDLY PRESENTED TO</p>

            <div class="recipient-name-wrap">
              <div class="recipient-name">{{ $certificate->student_name }}</div>
            </div>

            <svg class="name-divider" viewBox="0 0 560 18" xmlns="http://www.w3.org/2000/svg">
              <polygon points="2,9 9,5.5 16,9 9,12.5" fill="#c5a278" />
              <path d="M 13,9 L 25,3.5 L 35,9 L 25,14.5 Z" fill="#c5a278" />
              <line x1="33" y1="9" x2="527" y2="9" stroke="#c5a278" stroke-width="1.5" />
              <path d="M 525,9 L 535,3.5 L 547,9 L 535,14.5 Z" fill="#c5a278" />
              <polygon points="544,9 551,5.5 558,9 551,12.5" fill="#c5a278" />
            </svg>

            <p class="cert-body">
              {{ $template->subtitle ?: 'This certificate acknowledges your outstanding contribution and dedication to successfully completing the curriculum of' }}<br />
              <strong>{{ $certificate->course_title }}</strong>@if($certificate->grade), showcasing commitment to excellence and distinguished honors ({{ $certificate->grade }}).@endif
            </p>

            <div class="cert-footer">
              <div class="footer-side">
                <div class="tapered-line"></div>
                <div class="date-text">{{ $certificate->issue_date->format('d M Y') }}</div>
              </div>

              <div class="seal-container">
                <svg id="laurelSeal" width="166" height="166" viewBox="-90 -90 180 180" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <polygon id="emblemStar" points="0,-10 2.9,-3.1 10.3,-2.3 4.7,2.6 6.4,9.8 0,6 -6.4,9.8 -4.7,2.6 -10.3,-2.3 -2.9,-3.1" fill="#b68d59" />
                    <polygon id="emblemDiamond" points="0,-6 4.6,0 0,6 -4.6,0" fill="#b68d59" />
                  </defs>

                  <g id="wreathLeaves" fill="#b68d59"></g>

                  <use href="#emblemStar" transform="translate(-8.5, 67) scale(0.55)" />
                  <use href="#emblemStar" transform="translate(8.5, 67) scale(0.55)" />

                  <use href="#emblemStar" transform="translate(-19, -31) scale(0.58)" />
                  <use href="#emblemStar" transform="translate(0, -39) scale(0.95)" />
                  <use href="#emblemStar" transform="translate(19, -31) scale(0.58)" />

                  <text x="0" y="-6" text-anchor="middle" fill="#b68d59" font-family="'Cinzel', 'Libre Baskerville', Georgia, serif" font-weight="700" font-size="22" letter-spacing="1.2">BEST</text>
                  <text x="0" y="16" text-anchor="middle" fill="#b68d59" font-family="'Cinzel', 'Libre Baskerville', Georgia, serif" font-weight="700" font-size="21" letter-spacing="0.8">AWARD</text>

                  <use href="#emblemDiamond" transform="translate(-19, 32) scale(0.72)" />
                  <use href="#emblemDiamond" transform="translate(0, 32) scale(1.05)" />
                  <use href="#emblemDiamond" transform="translate(19, 32) scale(0.72)" />
                </svg>
              </div>

              <div class="footer-side">
                <div class="tapered-line"></div>
                <div class="signature-text">{{ $certificate->instructor_name ?: ($template->signer_name ?? 'Dr. Alexander Wright') }}</div>
              </div>
            </div>
          </div>

          @if ($template->show_qr_code ?? true)
            <div class="no-print" style="position: absolute; bottom: 22px; right: 28px; z-index: 10; display: flex; align-items: center; gap: 6px; background: rgba(253, 252, 250, 0.95); padding: 3px 6px; border: 1px solid #e5ddd0; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
              <img src="{{ $certificate->qrCodeUrl() }}" style="width: 26px; height: 26px; object-fit: contain; display: block;" alt="QR">
              <span style="font-family: monospace; font-size: 7px; color: #87817a; text-transform: uppercase; line-height: 1.1; letter-spacing: 0.05em;">VERIFIED<br>REGISTRY</span>
            </div>
          @endif
        </div>
      @endif

    </div>
  </div>

  @if (! request('embed'))
    <!-- Minimal Floating Action Control Bar (Hidden when printing) -->
    <div class="no-print floating-bar">
      <a href="{{ route('student.certificates') }}" class="floating-btn">
        <svg width="14" height="14" style="width:14px;height:14px;display:inline-block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to My Certificates</span>
      </a>

      <span class="divider-dot"></span>

      <button type="button" onclick="copyVerificationLink('{{ route('certificates.verify.show', $certificate->certificate_number) }}')" class="floating-btn">
        <svg width="14" height="14" style="width:14px;height:14px;display:inline-block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        <span id="copy-btn-text">Share Link</span>
      </button>

      <a href="{{ route('certificates.verify.show', $certificate->certificate_number) }}" target="_blank" class="floating-btn" style="color: #34d399;">
        <svg width="14" height="14" style="width:14px;height:14px;display:inline-block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Verify Registry</span>
      </a>

      <button type="button" id="printBtn" onclick="window.print()" class="floating-btn floating-btn-primary">
        <svg width="15" height="15" style="width:15px;height:15px;display:inline-block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v4H6v-4z" />
        </svg>
        <span>Print / Save PDF</span>
      </button>
    </div>
  @endif

  <script>
    /**
     * Top Wave Guilloche Lines for Template 7
     */
    function buildTopGuillocheWaves() {
      const group = document.getElementById('guillocheWaveLines');
      if (!group) return;

      const totalLines = 14;
      let markup = '';

      for (let i = 0; i < totalLines; i++) {
        const startX = 468 + i * 9.5;
        const startY = -2;
        const cp1X = 585 + i * 6.5;
        const cp1Y = 62 + i * 7.8;
        const cp2X = 765 + i * 4.5;
        const cp2Y = 92 + i * 6.2;
        const endX = 1056;
        const endY = 4 + i * 3.8;

        const d = `M ${startX.toFixed(1)},${startY} C ${cp1X.toFixed(1)},${cp1Y.toFixed(1)} ${cp2X.toFixed(1)},${cp2Y.toFixed(1)} ${endX},${endY.toFixed(1)}`;
        markup += `<path d="${d}" fill="none" stroke="url(#contourLineGrad)" stroke-width="1.15" />`;
        markup += `<path d="${d}" fill="none" stroke="#c9933b" stroke-width="1.35" stroke-linecap="round" stroke-dasharray="0.2 5.5" opacity="0.55" />`;
      }

      group.innerHTML = markup;
    }

    /**
     * Rosette paths for Template 7
     */
    function buildScallopedRosette() {
      const makeRosettePath = (baseR, amp, lobes, phaseRad = 0) => {
        const steps = 160;
        let d = '';
        for (let i = 0; i <= steps; i++) {
          const theta = (i / steps) * Math.PI * 2;
          const r = baseR + amp * Math.cos(lobes * theta + phaseRad);
          const x = r * Math.cos(theta - Math.PI / 2);
          const y = r * Math.sin(theta - Math.PI / 2);
          d += (i === 0 ? 'M ' : 'L ') + `${x.toFixed(2)},${y.toFixed(2)} `;
        }
        return d + 'Z';
      };

      const outerEl = document.getElementById('rosetteOuterPath');
      const middleEl = document.getElementById('rosetteMiddlePath');
      const ridgeEl = document.getElementById('rosetteRidgePath');

      if (outerEl) outerEl.setAttribute('d', makeRosettePath(86.5, 6.2, 8, 0));
      if (middleEl) middleEl.setAttribute('d', makeRosettePath(78.5, 4.2, 8, 0));
      if (ridgeEl) ridgeEl.setAttribute('d', makeRosettePath(82.2, 5.1, 8, 0));
    }

    /**
     * Laurel Wreath leaves for Template 3
     */
    function buildLaurelWreath() {
      const container = document.getElementById('wreathLeaves');
      if (!container) return;

      const radius = 66;
      const steps = 11;
      const startAngleDeg = 72;
      const endAngleDeg = -76;

      let svgMarkup = '';

      [-1, 1].forEach((side) => {
        for (let i = 0; i <= steps; i++) {
          const t = i / steps;
          const deg = startAngleDeg + t * (endAngleDeg - startAngleDeg);
          const rad = (deg * Math.PI) / 180;
          const x = side * radius * Math.cos(rad);
          const y = radius * Math.sin(rad);
          const tangentDeg = side === 1 ? deg - 90 : 90 - deg;
          const scale = i === steps ? 0.52 : 1 - t * 0.34;

          if (i < steps) {
            const outerAngle = tangentDeg + side * 28;
            svgMarkup += `
              <path d="M 0,0 C -3.8,-6 -3.5,-14 0,-20 C 3.5,-14 3.8,-6 0,0 Z"
                    transform="translate(${x.toFixed(2)}, ${y.toFixed(2)}) rotate(${outerAngle.toFixed(2)}) scale(${scale.toFixed(2)})" />
            `;
            const innerAngle = tangentDeg - side * 26;
            svgMarkup += `
              <path d="M 0,0 C -3.6,-5.5 -3.3,-13 0,-18.5 C 3.3,-13 3.6,-5.5 0,0 Z"
                    transform="translate(${x.toFixed(2)}, ${y.toFixed(2)}) rotate(${innerAngle.toFixed(2)}) scale(${(scale * 0.92).toFixed(2)})" />
            `;
          } else {
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
     * Smooth viewport scaler that handles both 1060x750 and 1120x792
     */
    function fitCertificateToScreen() {
      const scaler = document.getElementById('scaler');
      if (!scaler) return;

      const isT7 = {{ $isTemplate7 ? 'true' : 'false' }};
      const certWidth = isT7 ? 1060 : 1120;
      const certHeight = isT7 ? 750 : 792;
      const isEmbed = {{ request('embed') ? 'true' : 'false' }};

      if (isEmbed) {
        const availWidth = window.innerWidth;
        const availHeight = window.innerHeight;
        if (availWidth <= 0 || availHeight <= 0) return;

        const scale = Math.min(availWidth / certWidth, availHeight / certHeight);
        scaler.style.transformOrigin = 'center center';
        scaler.style.transform = `scale(${scale})`;
        scaler.style.position = 'relative';
        scaler.style.top = 'auto';
        scaler.style.left = 'auto';
        scaler.style.width = `${certWidth}px`;
        scaler.style.height = `${certHeight}px`;
        scaler.style.boxShadow = 'none';

        const wrapper = scaler.parentElement;
        if (wrapper) {
          wrapper.style.display = 'flex';
          wrapper.style.alignItems = 'center';
          wrapper.style.justifyContent = 'center';
          wrapper.style.width = '100%';
          wrapper.style.height = '100%';
          wrapper.style.overflow = 'hidden';
        }
      } else {
        const availWidth = window.innerWidth - 32;
        const availHeight = window.innerHeight - 100;

        const scale = Math.min(1, availWidth / certWidth, availHeight / certHeight);
        scaler.style.transformOrigin = 'center center';
        scaler.style.transform = `scale(${scale})`;
        scaler.style.position = 'relative';
        scaler.style.top = 'auto';
        scaler.style.left = 'auto';
        scaler.style.width = `${certWidth}px`;
        scaler.style.height = `${certHeight}px`;

        const wrapper = scaler.parentElement;
        if (wrapper) {
          wrapper.style.height = `${Math.ceil(certHeight * scale + 60)}px`;
        }
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
      buildTopGuillocheWaves();
      buildScallopedRosette();
      buildLaurelWreath();
      fitCertificateToScreen();
    });

    window.addEventListener('load', () => {
      buildTopGuillocheWaves();
      buildScallopedRosette();
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
