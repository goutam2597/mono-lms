<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Code</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #191919; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f6f9; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 560px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .header { background: #08131e; padding: 28px 36px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 20px; font-weight: 700; letter-spacing: -0.02em; }
        .content { padding: 36px; }
        .greeting { font-size: 18px; font-weight: 700; color: #191919; margin-bottom: 12px; }
        .message { font-size: 14px; line-height: 1.6; color: #525252; margin-bottom: 24px; }
        .otp-box { background: #f8fafc; border: 2px dashed #344c3d; border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 24px; }
        .otp-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #64748b; margin-bottom: 8px; }
        .otp-code { font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #344c3d; margin: 0; }
        .note { font-size: 12px; color: #64748b; line-height: 1.5; margin-bottom: 20px; }
        .footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 36px; text-align: center; font-size: 11px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1>{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}</h1>
            </div>
            <div class="content">
                <div class="greeting">Hello {{ $name }},</div>
                <div class="message">
                    Thank you for signing up for <strong>{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}</strong> as a {{ $role === 'instructor' ? 'Teacher / Instructor' : 'Student' }}!
                    Please use the following 6-digit verification code to complete your registration:
                </div>
                <div class="otp-box">
                    <div class="otp-label">Your Verification Code</div>
                    <div class="otp-code">{{ $otp }}</div>
                </div>
                <div class="note">
                    &bull; This verification code will expire in <strong>10 minutes</strong>.<br>
                    &bull; If you did not sign up for an account, please safely ignore this email.
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Mono LMS') }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
