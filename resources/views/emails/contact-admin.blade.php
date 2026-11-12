<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message Inquiry</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #191919; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f6f9; padding: 36px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .header { background: #08131e; padding: 26px 36px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 19px; font-weight: 700; letter-spacing: -0.02em; }
        .badge { display: inline-block; background: #344c3d; color: #ffffff; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 4px 10px; border-radius: 4px; margin-top: 8px; }
        .content { padding: 32px 36px; }
        .greeting { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .intro { font-size: 13px; line-height: 1.5; color: #64748b; margin-bottom: 24px; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 20px; }
        .info-row { display: flex; margin-bottom: 8px; font-size: 13px; }
        .info-label { width: 110px; font-weight: 700; color: #64748b; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; shrink: 0; }
        .info-val { color: #0f172a; font-weight: 600; word-break: break-word; }
        .message-box { background: #ffffff; border-left: 4px solid #344c3d; border: 1px solid #e2e8f0; border-left-width: 4px; border-radius: 6px; padding: 18px; margin-bottom: 24px; }
        .message-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 8px; }
        .message-body { font-size: 14px; line-height: 1.6; color: #1e293b; white-space: pre-wrap; word-break: break-word; }
        .btn-wrapper { text-align: center; margin: 28px 0 16px; }
        .btn { display: inline-block; background-color: #344c3d; color: #ffffff !important; text-decoration: none; font-size: 13px; font-weight: 700; padding: 12px 28px; border-radius: 6px; letter-spacing: 0.02em; }
        .reply-hint { font-size: 12px; color: #64748b; text-align: center; margin-top: 8px; }
        .footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 18px 36px; text-align: center; font-size: 11px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1>{{ $siteName }}</h1>
                <span class="badge">Inbound Contact Inquiry</span>
            </div>
            <div class="content">
                <div class="greeting">New Message Received</div>
                <div class="intro">
                    A visitor submitted an inquiry through the public contact form. Details are provided below:
                </div>

                <div class="info-card">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <tr>
                            <td style="width: 100px; padding: 5px 0; font-weight: bold; color: #64748b; font-size: 11px; text-transform: uppercase;">From:</td>
                            <td style="padding: 5px 0; color: #0f172a; font-weight: 600;">{{ $contactMessage->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; font-weight: bold; color: #64748b; font-size: 11px; text-transform: uppercase;">Email:</td>
                            <td style="padding: 5px 0; color: #0f172a;">
                                <a href="mailto:{{ $contactMessage->email }}" style="color: #344c3d; text-decoration: none; font-weight: 600;">{{ $contactMessage->email }}</a>
                            </td>
                        </tr>
                        @if ($contactMessage->phone)
                            <tr>
                                <td style="padding: 5px 0; font-weight: bold; color: #64748b; font-size: 11px; text-transform: uppercase;">Phone:</td>
                                <td style="padding: 5px 0; color: #0f172a;">{{ $contactMessage->phone }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding: 5px 0; font-weight: bold; color: #64748b; font-size: 11px; text-transform: uppercase;">Subject:</td>
                            <td style="padding: 5px 0; color: #0f172a; font-weight: bold;">{{ $contactMessage->subject }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; font-weight: bold; color: #64748b; font-size: 11px; text-transform: uppercase;">Received:</td>
                            <td style="padding: 5px 0; color: #64748b;">{{ $contactMessage->created_at?->format('M d, Y · h:i A') ?? now()->format('M d, Y · h:i A') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="message-box">
                    <div class="message-title">Message Body</div>
                    <div class="message-body">{{ $contactMessage->message }}</div>
                </div>

                <div class="btn-wrapper">
                    <a href="{{ $adminUrl }}" class="btn">View &amp; Reply in Admin Desk &rarr;</a>
                </div>

                <div class="reply-hint">
                    💡 You can also reply directly to this email to contact <strong>{{ $contactMessage->name }}</strong> directly.
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ $siteName }}. Internal Administrator Notification.
            </div>
        </div>
    </div>
</body>
</html>
