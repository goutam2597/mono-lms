<x-emails.layout>
    <p style="color:#191919; font-size:15px; margin:0 0 16px;">{{ __('emails.reset.greeting', ['name' => $name]) }}</p>
    <p style="color:#5d5d5d; font-size:14px; margin:0 0 8px;">{{ __('emails.reset.line') }}</p>
    <p style="color:#5d5d5d; font-size:14px; margin:0 0 24px;">{{ __('emails.reset.panel_line', ['panel' => $panelLabel]) }}</p>

    <a href="{{ $url }}" style="display:inline-block; background:#344c3d; color:#ffffff; text-decoration:none; font-weight:600; font-size:14px; padding:12px 28px; border-radius:999px;">
        {{ __('emails.reset.action') }}
    </a>

    <p style="color:#9a9a9a; font-size:12px; margin:24px 0 0;">{{ __('emails.reset.expire', ['minutes' => $lifetime]) }}</p>
    <p style="color:#9a9a9a; font-size:12px; margin:8px 0 0;">{{ __('emails.reset.closing') }}</p>
</x-emails.layout>
