<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $currentDirection ?? 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:32px 16px; background:#f4f6f9; font-family:Arial, Helvetica, sans-serif; direction:{{ $currentDirection ?? 'ltr' }};">
    <div style="max-width:560px; margin:0 auto; background:#ffffff; border-radius:16px; padding:32px; box-shadow:0 4px 24px rgba(0,0,0,0.06);">
        <div style="font-size:22px; font-weight:800; color:#191919; margin-bottom:24px;">
            Dreams<span style="color:#ff4667;">LMS</span>
        </div>
        {{ $slot }}
    </div>
</body>
</html>
