<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Received Your Message</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #191919; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f6f9; padding: 36px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 580px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .header { background: #08131e; padding: 26px 36px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 19px; font-weight: 700; letter-spacing: -0.02em; }
        .content { padding: 32px 36px; }
        .greeting { font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .lead { font-size: 14px; line-height: 1.6; color: #334155; margin-bottom: 20px; }
        .summary-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .summary-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; }
        .field-label { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-top: 10px; margin-bottom: 2px; }
        .field-val { font-size: 13px; color: #0f172a; font-weight: 600; }
        .message-box { background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 14px; margin-top: 6px; font-size: 13px; line-height: 1.6; color: #334155; white-space: pre-wrap; word-break: break-word; }
        .help-note { font-size: 12px; color: #64748b; line-height: 1.5; margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
        .footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 18px 36px; text-align: center; font-size: 11px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1>{{ $siteName }}</h1>
            </div>
            <div class="content">
                <div class="greeting">Hello {{ $contactMessage->name }},</div>
                <div class="lead">
                    Thank you for contacting <strong>{{ $siteName }}</strong>! We have received your inquiry and our support team will review it and get back to you shortly.
                </div>

                <div class="summary-card">
                    <div class="summary-title">Summary of Your Inquiry</div>

                    <div class="field-label">Subject</div>
                    <div class="field-val">{{ $contactMessage->subject }}</div>

                    <div class="field-label">Date Submitted</div>
                    <div class="field-val" style="font-weight: normal; color: #64748b;">
                        {{ $contactMessage->created_at?->format('M d, Y · h:i A') ?? now()->format('M d, Y · h:i A') }}
                    </div>

                    <div class="field-label">Your Message</div>
                    <div class="message-box">{{ $contactMessage->message }}</div>
                </div>

                <div class="help-note">
                    Need to add additional information or have urgent questions? You can reply directly to this email at any time.
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
