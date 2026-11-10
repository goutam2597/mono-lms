<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $certificate->certificate_number }} - {{ $template->title ?? 'Certificate' }}</title>
  <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">

  <!-- Google Fonts for both Certificate Designs -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Caveat:wght@600;700&family=Cinzel:wght@600;700&family=Cormorant+Garamond:wght@500;600;700&family=Great+Vibes&family=Inter:wght@400;500;600;700&family=Kaushan+Script&family=Libre+Baskerville:wght@400;700&family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sacramento&display=swap" rel="stylesheet">


  <?php $isAppreciation = ($template->template_type ?? 'template_1') === 'template_8'; ?>

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
        background-color: #dde0e3;
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
       STYLES FOR TEMPLATE 1: CONTEMPORARY PRESTIGE AWARD
       ------------------------------------------------------------- */
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

      .certificate-canvas-t1 {
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

      .cert-label-t1 {
        font-family: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;
        font-weight: 600;
        font-size: 21px;
        line-height: 1.1;
        letter-spacing: 0.06em;
        color: var(--sub-charcoal);
        text-transform: uppercase;
        margin: 0;
      }

      .cert-main-heading-t1 {
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

      .presented-label-t1 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 500;
        font-size: 11.5px;
        line-height: 1.2;
        letter-spacing: 0.21em;
        color: var(--muted-grey);
        text-transform: uppercase;
        margin: 38px 0 0 0;
      }

      .recipient-wrapper-t1 {
        position: relative;
        margin-top: 4px;
        width: 412px;
        border-bottom: 1px solid #ebe8e2;
        padding-bottom: 2px;
      }

      .recipient-script-t1 {
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

      .body-copy-t1 {
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 14.2px;
        line-height: 1.66;
        color: var(--body-grey);
        max-width: 440px;
        margin: 22px 0 0 0;
        letter-spacing: 0.003em;
      }

      .signature-block-t1 {
        position: absolute;
        left: 92px;
        bottom: 58px;
        z-index: 10;
        text-align: left;
      }

      .signature-line-wrap-t1 {
        position: relative;
        width: 140px;
        height: 44px;
        display: flex;
        align-items: center;
      }

      .signature-rule-t1 {
        position: absolute;
        left: 0;
        bottom: 11px;
        width: 130px;
        height: 1px;
        background-color: #dcd8d0;
        pointer-events: none;
      }

      .signature-handwriting-t1 {
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

      .signature-role-t1 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 600;
        font-size: 9.5px;
        letter-spacing: 0.1em;
        color: #5a5652;
        text-transform: uppercase;
        margin-top: 2px;
      }

      .portrait-area-t1 {
        position: absolute;
        right: 142px;
        top: 184px;
        width: 316px;
        height: 382px;
        z-index: 8;
      }

      .portrait-dashed-ring-t1 {
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

      .portrait-beige-circle-t1 {
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

      .portrait-popout-mask-t1 {
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

      .portrait-popout-mask-t1 img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
      }

      .gold-badge-wrapper-t1 {
        position: absolute;
        left: -62px;
        top: -12px;
        width: 196px;
        height: 196px;
        z-index: 12;
        filter: drop-shadow(-10px 14px 16px rgba(35, 25, 10, 0.17));
        pointer-events: none;
      }

      .qr-date-block-t1 {
        position: absolute;
        right: 86px;
        bottom: 58px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
      }

      .qr-box-t1 {
        width: 56px;
        height: 56px;
        margin-bottom: 14px;
      }

      .presented-on-label-t1 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 500;
        font-size: 8.5px;
        letter-spacing: 0.08em;
        color: #8d8882;
        text-transform: uppercase;
        line-height: 1.2;
      }

      .presented-date-t1 {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 14.5px;
        color: #2c2a28;
        margin-top: 3px;
        letter-spacing: -0.01em;
      }

    /* -------------------------------------------------------------
       STYLES FOR TEMPLATE 8: CERTIFICATE OF APPRECIATION
       (Navy & gold geometric frame, laurel-free modern layout)
       ------------------------------------------------------------- */
      .t8-canvas {
        position: relative;
        width: 1060px;
        height: 750px;
        overflow: hidden;
        user-select: none;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
      }

      /* --- Geometric corner frame: thick navy bands + gold thread lines --- */
      .t8-band, .t8-line {
        position: absolute;
        pointer-events: none;
        z-index: 4;
      }
      .t8-band.t8-tl-top   { top: 42px; left: 42px; width: 52%; height: 16px; background: var(--t8-navy); }
      .t8-band.t8-tl-side  { top: 42px; left: 42px; width: 16px; height: calc(100% - 84px); background: var(--t8-navy); }
      .t8-band.t8-tr-side  { top: 42px; right: 42px; width: 16px; height: 168px; background: var(--t8-navy); }
      .t8-band.t8-bl-bottom { bottom: 42px; left: 42px; width: 170px; height: 16px; background: var(--t8-navy); }
      .t8-band.t8-br-bottom { bottom: 42px; right: 42px; width: 54%; height: 16px; background: var(--t8-navy); }
      .t8-band.t8-br-side  { bottom: 42px; right: 42px; width: 16px; height: 63%; background: var(--t8-navy); }

      .t8-line { background: var(--t8-gold); z-index: 5; }
      .t8-line.t8-tl-top   { top: 32px; left: 24px; width: calc(52% + 76px); height: 3px; }
      .t8-line.t8-tl-side  { top: 24px; left: 32px; width: 3px; height: calc(100% - 55px); }
      .t8-line.t8-bl-bottom { bottom: 32px; left: 24px; width: 250px; height: 3px; }
      .t8-line.t8-br-bottom { bottom: 32px; right: 24px; width: calc(54% + 76px); height: 3px; }
      .t8-line.t8-br-side  { top: 24px; right: 32px; width: 3px; height: calc(100% - 55px); }

      .t8-line.thin { background: var(--t8-navy); opacity: 0.8; }
      .t8-line.t8-tl-top.thin    { top: 69px; left: 42px; width: 43%; height: 2px; }
      .t8-line.t8-tl-side.thin   { top: 69px; left: 69px; width: 2px; height: 62%; }
      .t8-line.t8-br-bottom.thin { bottom: 69px; right: 42px; width: 45%; height: 2px; }
      .t8-line.t8-br-side.thin   { bottom: 69px; right: 69px; width: 2px; height: 53%; }

      /* --- Anniversary award badge with ribbon tails --- */
      .t8-badge {
        position: absolute;
        left: 80px;
        top: 282px;
        width: 134px;
        z-index: 10;
      }

      /* --- Company brand (top right) --- */
      .t8-brand {
        position: absolute;
        top: 58px;
        right: 92px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }
      .t8-brand-name {
        font-weight: 800;
        font-size: 11.5px;
        letter-spacing: 0.2em;
        color: var(--t8-navy);
        text-transform: uppercase;
        line-height: 1.8;
        max-width: 118px;
        text-align: center;
      }

      /* --- Centered heading --- */
      .t8-heading {
        position: absolute;
        top: 118px;
        left: 0;
        right: 0;
        z-index: 10;
        text-align: center;
      }
      .t8-title {
        font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 54px;
        line-height: 1;
        letter-spacing: 0.16em;
        text-indent: 0.16em;
        color: var(--t8-navy);
        margin: 0;
      }
      .t8-subline {
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
      }
      .t8-subline-rule { width: 66px; height: 1.5px; background: var(--t8-navy); opacity: 0.35; }
      .t8-subline-text {
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.5em;
        text-indent: 0.5em;
        color: var(--t8-navy);
        text-transform: uppercase;
      }
      .t8-heading-diamond {
        width: 9px;
        height: 9px;
        background: var(--t8-gold);
        transform: rotate(45deg);
        margin: 18px auto 0;
      }

      /* --- Recipient & body copy --- */
      .t8-center {
        position: absolute;
        top: 330px;
        left: 176px;
        right: 148px;
        z-index: 10;
        text-align: center;
      }
      .t8-presented {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.34em;
        text-indent: 0.34em;
        color: var(--t8-navy);
        text-transform: uppercase;
        margin: 0 0 2px;
      }
      .t8-name {
        font-family: 'Great Vibes', cursive;
        font-size: 64px;
        line-height: 1.15;
        color: var(--t8-navy);
        font-weight: 400;
        margin: 0;
      }
      .t8-copy {
        font-size: 12.8px;
        line-height: 1.8;
        color: #6f7683;
        max-width: 600px;
        margin: 16px auto 0;
        letter-spacing: 0.01em;
      }
      .t8-copy strong { color: #3b4250; font-weight: 600; }

      /* --- Bottom date & signature columns --- */
      .t8-sign-row {
        position: absolute;
        bottom: 96px;
        left: 0;
        right: 0;
        z-index: 10;
        display: flex;
        justify-content: center;
        gap: 80px;
      }
      .t8-sign-col { width: 200px; text-align: center; }
      .t8-sign-value {
        font-size: 12.5px;
        color: #6f7683;
        height: 30px;
        line-height: 30px;
        margin: 0;
      }
      .t8-sign-script {
        font-family: 'Great Vibes', cursive;
        font-size: 26px;
        color: var(--t8-navy);
        height: 30px;
        line-height: 30px;
        overflow: visible;
        margin: 0;
        white-space: nowrap;
      }
      .t8-sign-rule { border-top: 1.5px solid var(--t8-navy); opacity: 0.5; }
      .t8-sign-label {
        margin-top: 8px;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 0.3em;
        text-indent: 0.3em;
        color: var(--t8-navy);
        text-transform: uppercase;
      }

      /* --- Verification QR --- */
      .t8-qr {
        position: absolute;
        right: 92px;
        bottom: 132px;
        z-index: 10;
        text-align: center;
      }
      .t8-qr-box {
        width: 62px;
        height: 62px;
        padding: 5px;
        background: #ffffff;
        border: 1px solid rgba(46, 74, 143, 0.25);
      }
      .t8-qr-box img { width: 100%; height: 100%; object-fit: contain; display: block; }
      .t8-qr-caption {
        margin-top: 6px;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: 0.18em;
        color: var(--t8-navy);
        text-transform: uppercase;
        white-space: nowrap;
      }

      .t8-brand-logo {
        display: block;
        max-height: 46px;
        max-width: 130px;
        object-fit: contain;
      }
  </style>
</head>
<body>

  <!-- Main Responsive Viewport -->
  <div class="viewport-wrapper">
    <div id="scaler" class="certificate-scaler">

        @if ($isAppreciation)
        <!-- =========================================================
             TEMPLATE 8: CERTIFICATE OF APPRECIATION
             (Navy & gold geometric frame with award medallion)
             ========================================================= -->
        <div class="t8-canvas" id="certificate" style="--t8-navy: {{ $template->border_color ?: '#2e4a8f' }}; --t8-gold: {{ $template->accent_color ?: '#f2b824' }}; background: {{ $template->bg_color ?: '#ffffff' }};">

          <!-- Geometric corner frame: navy bands, gold thread lines, inner navy rules -->
          <div class="t8-band t8-tl-top"></div>
          <div class="t8-band t8-tl-side"></div>
          <div class="t8-band t8-tr-side"></div>
          <div class="t8-band t8-bl-bottom"></div>
          <div class="t8-band t8-br-bottom"></div>
          <div class="t8-band t8-br-side"></div>

          <div class="t8-line t8-tl-top"></div>
          <div class="t8-line t8-tl-side"></div>
          <div class="t8-line t8-bl-bottom"></div>
          <div class="t8-line t8-br-bottom"></div>
          <div class="t8-line t8-br-side"></div>

          <div class="t8-line thin t8-tl-top"></div>
          <div class="t8-line thin t8-tl-side"></div>
          <div class="t8-line thin t8-br-bottom"></div>
          <div class="t8-line thin t8-br-side"></div>

          <!-- Anniversary Award Badge with Ribbon Tails -->
          <div class="t8-badge">
            <svg viewBox="0 0 134 180" width="134" height="180" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient id="t8BadgeGold" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#f9e08a" />
                  <stop offset="45%" stop-color="#e3b64d" />
                  <stop offset="100%" stop-color="#b98a21" />
                </linearGradient>
              </defs>
              <polygon points="44,106 66,106 66,166 55,155.5 44,166" style="fill: var(--t8-navy);" />
              <polygon points="90,106 68,106 68,166 79,155.5 90,166" style="fill: var(--t8-navy);" />
              <circle cx="67" cy="72" r="50" fill="url(#t8BadgeGold)" />
              <circle cx="67" cy="72" r="44" style="fill: var(--t8-navy);" />
              <circle cx="67" cy="72" r="37" fill="none" stroke="#f4d97c" stroke-width="1.3" stroke-dasharray="0.2 5.8" stroke-linecap="round" />
              <polygon points="67,27 70.5,36.5 80.5,37 72.8,43 75.6,52.5 67,47 58.4,52.5 61.2,43 53.5,37 63.5,36.5" fill="url(#t8BadgeGold)" />
              <text x="67" y="80" text-anchor="middle" style="fill: var(--t8-gold);" font-family="'Playfair Display', Georgia, serif" font-weight="700" font-size="21" letter-spacing="1">{{ $certificate->issue_date->format('Y') }}</text>
              <text x="67" y="96" text-anchor="middle" fill="#ffffff" font-family="'Plus Jakarta Sans', 'Inter', sans-serif" font-weight="700" font-size="10" letter-spacing="2.6">AWARD</text>
            </svg>
          </div>

          <!-- Company Brand Mark (top right) — always the site logo from settings -->
          <div class="t8-brand">
            <img class="t8-brand-logo" src="{{ \App\Models\Setting::logoUrl() }}" alt="{{ config('app.name') }}" />
          </div>

          <!-- Heading -->
          <div class="t8-heading">
            <h1 class="t8-title">CERTIFICATE</h1>
            <div class="t8-subline">
              <div class="t8-subline-rule"></div>
              <div class="t8-subline-text">OF APPRECIATION</div>
              <div class="t8-subline-rule"></div>
            </div>
            <div class="t8-heading-diamond"></div>
          </div>

          <!-- Recipient, Course & Body Copy -->
          <div class="t8-center">
            <p class="t8-presented">Proudly Presented To</p>
            <h2 class="t8-name">{{ $certificate->student_name }}</h2>
            <p class="t8-copy">
              {{ $template->subtitle ?: 'This certificate of appreciation is proudly awarded in sincere recognition of the dedication, perseverance, and outstanding spirit demonstrated throughout the successful completion of' }}
              <strong>{{ $certificate->course_title }}</strong>{{ $certificate->grade ? ', achieving ' . $certificate->grade . '.' : '.' }}
            </p>
          </div>

          <!-- Date & Signature -->
          <div class="t8-sign-row">
            <div class="t8-sign-col">
              <div class="t8-sign-value">{{ $certificate->issue_date->format('d M Y') }}</div>
              <div class="t8-sign-rule"></div>
              <div class="t8-sign-label">Date</div>
            </div>
            <div class="t8-sign-col">
              <div class="t8-sign-script">{{ $certificate->instructor_name ?: ($template->signer_name ?? 'Dr. Alexander Wright') }}</div>
              <div class="t8-sign-rule"></div>
              <div class="t8-sign-label">{{ $template->signature_title ?: 'SIGNATURE' }}</div>
            </div>
          </div>

          <!-- Verification QR -->
          @if ($template->show_qr_code ?? true)
            <div class="t8-qr">
              <div class="t8-qr-box"><img src="{{ $certificate->qrCodeUrl() }}" alt="QR Code" /></div>
              <div class="t8-qr-caption">Scan to Verify</div>
            </div>
          @endif
        </div>
        @else

        <!-- =========================================================
             TEMPLATE 1: CONTEMPORARY PRESTIGE AWARD
             ========================================================= -->
        <div class="certificate-canvas-t1" id="certificate">
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
            <h2 class="cert-label-t1">CERTIFICATE OF</h2>
            <h1 class="cert-main-heading-t1">ACHIEVEMENT</h1>

            <p class="presented-label-t1">THIS IS PROUDLY PRESENTED TO</p>

            <div class="recipient-wrapper-t1">
              <div class="recipient-script-t1">{{ $certificate->student_name }}</div>
            </div>

            <p class="body-copy-t1">
              {{ $template->subtitle ?: 'This certificate acknowledges your outstanding contribution and dedication to successfully completing the curriculum of' }}<br />
              <strong>{{ $certificate->course_title }}</strong>@if($certificate->grade), showcasing commitment to excellence and distinguished honors ({{ $certificate->grade }}).@endif
            </p>
          </div>

          <!-- Bottom-Left Signature Block -->
          <div class="signature-block-t1">
            <div class="signature-line-wrap-t1">
              <div class="signature-rule-t1"></div>
              <div class="signature-handwriting-t1">{{ $certificate->instructor_name ?: ($template->signer_name ?? 'Dr. Julian Vance') }}</div>
            </div>
            <div class="signature-role-t1">{{ $template->signature_title ?? 'MANAGER, CTO' }}</div>
          </div>

          <!-- Right Column: Student Avatar Portrait & 3D Gold Rosette Seal -->
          <div class="portrait-area-t1">
            <div class="portrait-dashed-ring-t1"></div>
            <div class="portrait-beige-circle-t1"></div>

            <div class="portrait-popout-mask-t1">
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
            <div class="gold-badge-wrapper-t1">
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
          <div class="qr-date-block-t1">
            <div class="qr-box-t1">
              <img src="{{ $certificate->qrCodeUrl() }}" style="width: 100%; height: 100%; object-fit: contain; display: block;" alt="QR Code" />
            </div>
            <div class="presented-on-label-t1">PRESENTED ON</div>
            <div class="presented-date-t1">{{ $certificate->issue_date->format('d M Y') }}</div>
          </div>
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
     * Top Wave Guilloche Lines for Template 1
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
     * Rosette paths for Template 1
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
     * Smooth viewport scaler that handles both 1060x750 and 1120x792
     */
    function fitCertificateToScreen() {
      const scaler = document.getElementById('scaler');
      if (!scaler) return;

      const certWidth = 1060;
      const certHeight = 750;
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
      fitCertificateToScreen();
    });

    window.addEventListener('load', () => {
      buildTopGuillocheWaves();
      buildScallopedRosette();
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
