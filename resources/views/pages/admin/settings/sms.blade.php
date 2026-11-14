<x-layouts.dashboard title="SMS Gateway & Transport Settings - Mono LMS" active="sms_settings" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
    .action-btn-danger { color: #b91c1c; background: #fef2f2; border-color: #fecaca; }
    .action-btn-danger:hover { color: #991b1b; background: #fee2e2; border-color: #fca5a5; }

    /* Bulletproof Toggle Switch */
    .toggle-switch-card { cursor: pointer; user-select: none; transition: border-color .15s ease, background-color .15s ease; }
    .toggle-switch-card:hover { border-color: #a3a3a3; }
    .toggle-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0; }
    .toggle-track { position: relative; display: inline-block; width: 44px; height: 24px; background-color: #e5e7eb; border: 1px solid #d1d5db; border-radius: 9999px; transition: background-color .2s ease, border-color .2s ease; flex-shrink: 0; }
    .dark .toggle-track { background-color: #262626; border-color: #404040; }
    .toggle-knob { position: absolute; top: 2px; left: 2px; width: 18px; height: 18px; background-color: #ffffff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25); transition: transform .2s cubic-bezier(0.4, 0, 0.2, 1); display: block; }
    .toggle-input:checked + .toggle-track { background-color: #0a0a0a; border-color: #0a0a0a; }
    .dark .toggle-input:checked + .toggle-track { background-color: #059669; border-color: #059669; }
    .toggle-input:checked + .toggle-track .toggle-knob { transform: translateX(20px); }
    .toggle-input:focus-visible + .toggle-track { outline: 2px solid #0a0a0a; outline-offset: 2px; }
    .dark .toggle-input:focus-visible + .toggle-track { outline-color: #10b981; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">SMS Gateway &amp; Transport</h1>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $settings['sms_enabled'] ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }}">
                        SMS: {{ $settings['sms_enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                        Active Provider: {{ strtoupper($settings['sms_provider']) }}
                    </span>
                    @if ($settings['sms_provider'] === 'gateway')
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $gatewayConfigured ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                            SIM Device: {{ $activeDevice ? $activeDevice->name : 'No Active Device' }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $twilioConfigured ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                            Twilio: {{ $twilioConfigured ? 'Configured' : 'Credentials Incomplete' }}
                        </span>
                    @endif
                </div>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Configure Twilio Cloud SMS or Self-Hosted Android SIM Gateway, feature toggles, and test message delivery.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.sms-gateway.index') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                Manage SIM Gateway Devices
            </a>
            <a href="{{ route('admin.sms-gateway.log') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                SMS Logs
            </a>
            <button type="submit" form="sms-settings-form" class="action-btn">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="border border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-4 text-sm text-emerald-800 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="border border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-800 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Main Settings Form -->
    <form id="sms-settings-form" method="POST" action="{{ route('admin.settings.sms.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Service Toggles -->
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">Feature Switches</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="toggle-switch-card border border-neutral-200 dark:border-neutral-800 p-4 rounded flex items-start justify-between gap-4 bg-white dark:bg-[#111111] dark:hover:border-neutral-700">
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Enable SMS System</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Master switch for all SMS transmissions across the platform.</p>
                    </div>
                    <div class="relative flex-none mt-0.5">
                        <input type="checkbox" name="sms_enabled" value="1" @checked($settings['sms_enabled']) class="toggle-input">
                        <span class="toggle-track">
                            <span class="toggle-knob"></span>
                        </span>
                    </div>
                </label>

                <label class="toggle-switch-card border border-neutral-200 dark:border-neutral-800 p-4 rounded flex items-start justify-between gap-4 bg-white dark:bg-[#111111] dark:hover:border-neutral-700">
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">SMS OTP Verification</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Allow students &amp; instructors to receive 6-digit one-time login/registration codes via SMS.</p>
                    </div>
                    <div class="relative flex-none mt-0.5">
                        <input type="checkbox" name="sms_otp_enabled" value="1" @checked($settings['sms_otp_enabled']) class="toggle-input">
                        <span class="toggle-track">
                            <span class="toggle-knob"></span>
                        </span>
                    </div>
                </label>

                <label class="toggle-switch-card border border-neutral-200 dark:border-neutral-800 p-4 rounded flex items-start justify-between gap-4 bg-white dark:bg-[#111111] dark:hover:border-neutral-700">
                    <div>
                        <p class="text-sm font-medium text-neutral-900 dark:text-white">Course &amp; Enrollment Alerts</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Send transactional notifications on student enrollments and announcements.</p>
                    </div>
                    <div class="relative flex-none mt-0.5">
                        <input type="checkbox" name="sms_notification_enabled" value="1" @checked($settings['sms_notification_enabled']) class="toggle-input">
                        <span class="toggle-track">
                            <span class="toggle-knob"></span>
                        </span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Provider Selector -->
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">Active Transport Provider</h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">Choose which mechanism delivers platform SMS. Both transports share identical queue and security logic.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Gateway option -->
                <label class="relative flex items-start gap-4 p-5 border rounded cursor-pointer transition {{ $settings['sms_provider'] === 'gateway' ? 'border-neutral-900 dark:border-white bg-neutral-50 dark:bg-[#161616]' : 'border-neutral-200 dark:border-neutral-800 hover:border-neutral-400' }}">
                    <input type="radio" name="sms_provider" value="gateway" @checked($settings['sms_provider'] === 'gateway') class="mt-1 accent-neutral-900">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-neutral-900 dark:text-white">Self-Hosted Android SIM Gateway</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-300 rounded border border-neutral-200 dark:border-neutral-700">Local Carrier Plan</span>
                        </div>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1.5 leading-relaxed">
                            Uses a dedicated Android phone running the <code>sms_gateway</code> companion app. SMS are dispatched through your SIM card using your carrier's local plan or SMS bundle. No public IP or port forwarding required.
                        </p>
                        @if ($activeDevice)
                            <div class="mt-3 flex items-center gap-2 text-xs text-neutral-700 dark:text-neutral-300">
                                <span class="w-2 h-2 rounded-full {{ $activeDevice->isOnline() ? 'bg-emerald-500 animate-pulse' : 'bg-neutral-400' }}"></span>
                                <span>Active Device: <strong>{{ $activeDevice->name }}</strong> ({{ $activeDevice->carrier ?? 'Carrier unknown' }}, {{ $activeDevice->battery_level ?? '?' }}% battery)</span>
                            </div>
                        @else
                            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">⚠️ No device is currently active. Register or activate a phone in Gateway Devices.</p>
                        @endif
                    </div>
                </label>

                <!-- Twilio option -->
                <label class="relative flex items-start gap-4 p-5 border rounded cursor-pointer transition {{ $settings['sms_provider'] === 'twilio' ? 'border-neutral-900 dark:border-white bg-neutral-50 dark:bg-[#161616]' : 'border-neutral-200 dark:border-neutral-800 hover:border-neutral-400' }}">
                    <input type="radio" name="sms_provider" value="twilio" @checked($settings['sms_provider'] === 'twilio') class="mt-1 accent-neutral-900">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-neutral-900 dark:text-white">Twilio Cloud API</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 rounded">Global Carrier Delivery</span>
                        </div>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1.5 leading-relaxed">
                            Sends SMS through Twilio's international REST API. Requires a Twilio account with Account SID, Auth Token, and a verified From number or alphanumeric Sender ID.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Twilio API Credentials -->
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">Twilio Configuration</h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">Stored safely in database settings, never in code or frontend payloads.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Twilio Account SID</label>
                    <input type="text" name="twilio_sid" value="{{ old('twilio_sid', $settings['twilio_sid']) }}" placeholder="ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Twilio Auth Token</label>
                    <input type="password" name="twilio_token" value="{{ old('twilio_token', $settings['twilio_token']) }}" placeholder="••••••••••••••••••••••••••••••••" class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Twilio From Number / Sender ID</label>
                    <input type="text" name="twilio_from" value="{{ old('twilio_from', $settings['twilio_from']) }}" placeholder="+12015550123" class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Phone Dial Codes -->
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">Country Dial Codes</h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                Dial codes are configured per country in
                <a href="{{ route('admin.settings.countries') }}" class="font-semibold text-neutral-900 dark:text-white underline">Locations → Countries</a>.
                Phone parsing matches numbers against the enabled countries listed there.
            </p>
        </div>
    </form>

    <!-- Test SMS Dispatch Card -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">Dispatch Live Test SMS</h2>
        <p class="text-xs text-neutral-500 dark:text-neutral-400">Test deliverability through the currently active provider (<strong>{{ strtoupper($settings['sms_provider']) }}</strong>).</p>

        <form method="POST" action="{{ route('admin.settings.sms.test') }}" class="flex flex-col sm:flex-row items-center gap-3 max-w-xl">
            @csrf
            <div class="flex-1 w-full">
                <x-phone-input
                    name="test_phone"
                    :value="old('test_phone')"
                    inputClass="w-full min-w-0 flex-1 text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 focus:outline-none"
                    triggerClass="text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 focus:outline-none whitespace-nowrap"
                />
                @error('test_phone')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="action-btn w-full sm:w-auto">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                Send Test SMS
            </button>
        </form>
    </div>
</div>

</x-layouts.dashboard>
