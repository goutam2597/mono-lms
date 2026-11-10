<x-layouts.dashboard title="Google & Firebase Settings - Mono LMS" active="google_settings" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
    .copy-btn { display: inline-flex; align-items: center; gap: 6px; height: 30px; padding: 0 10px; font-size: 10px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #525252; background: #fafafa; border: 1px solid #d4d4d4; cursor: pointer; transition: all .15s; }
    .copy-btn:hover { color: #0a0a0a; border-color: #0a0a0a; }
    .copy-btn.copied { color: #067647; background: #ecfdf5; border-color: #6ee7b7; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Google &amp; Firebase Settings</h1>
                <div class="flex items-center gap-1.5">
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $settings['enabled'] ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }}">
                        Google: {{ $settings['enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $settings['firebase_enabled'] ? 'bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }}">
                        Firebase: {{ $settings['firebase_enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Configure Google OAuth 2.0 social sign-in for web and Firebase credentials for mobile app authentication and push notifications.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $firebaseConsoleUrl }}" target="_blank" rel="noopener" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M3.89 15.672L6.255.47a.5.5 0 01.916-.176l3.52 6.643-6.8 8.735zm16.59-3.793l-2.08-10.74a.5.5 0 00-.89-.214L3.89 15.672l8.835 4.982a1.5 1.5 0 001.464 0l6.29-8.775zM12.9 8.01l2.58-4.912a.5.5 0 01.89.214l1.64 8.472L12.9 8.01z"/></svg>
                Firebase Console
            </a>
            <a href="{{ $consoleUrl }}" target="_blank" rel="noopener" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="w-3.5 h-3.5 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Google Console
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 text-xs font-medium">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.google.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <!-- Google OAuth Credentials -->
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-3 border-b border-neutral-100 dark:border-neutral-800 pb-4 mb-2">
                    <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white dark:bg-neutral-800 rounded-md">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white">Google OAuth (Web &amp; Storefront)</h2>
                        <p class="text-[11px] text-neutral-400">Google Cloud Console OAuth 2.0 Web Application credentials.</p>
                    </div>
                </div>

                <label class="flex items-center justify-between border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/50 px-4 py-3 cursor-pointer rounded-sm">
                    <span>
                        <span class="block text-xs font-semibold text-neutral-900 dark:text-white">Enable Google Login</span>
                        <span class="block text-[11px] text-neutral-500 dark:text-neutral-400 mt-0.5">Displays "Continue with Google" buttons on student and instructor auth screens.</span>
                    </span>
                    <input type="checkbox" name="google_login_enabled" value="1" class="control" @checked($settings['enabled'])>
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center justify-between border border-neutral-200 dark:border-neutral-800 px-4 py-3 cursor-pointer rounded-sm">
                        <span class="text-xs font-semibold text-neutral-900 dark:text-white">Allow for Students</span>
                        <input type="checkbox" name="google_allow_student" value="1" class="control" @checked($settings['allow_student'])>
                    </label>
                    <label class="flex items-center justify-between border border-neutral-200 dark:border-neutral-800 px-4 py-3 cursor-pointer rounded-sm">
                        <span class="text-xs font-semibold text-neutral-900 dark:text-white">Allow for Instructors</span>
                        <input type="checkbox" name="google_allow_instructor" value="1" class="control" @checked($settings['allow_instructor'])>
                    </label>
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Google Client ID *</label>
                    <input type="text" name="google_client_id" value="{{ $settings['client_id'] }}" placeholder="1234567890-abc123.apps.googleusercontent.com"
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Google Client Secret *</label>
                    <input type="password" name="google_client_secret" value="{{ $settings['client_secret'] }}" placeholder="GOCSPX-••••••••••••••••"
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>

                <!-- Callback URLs inside Google card -->
                <div class="pt-3 border-t border-neutral-100 dark:border-neutral-800 space-y-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Authorized Redirect URIs</p>
                    @foreach ($callbacks as $label => $url)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-neutral-400">{{ ucfirst($label) }} Callback</span>
                                <button type="button" class="copy-btn" data-copy="{{ $url }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="h-9 flex items-center border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 px-3 text-[11px] font-mono text-neutral-800 dark:text-neutral-200 break-all select-all">{{ $url }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Firebase Configuration (Mobile App & Push Notifications) -->
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-3 border-b border-neutral-100 dark:border-neutral-800 pb-4 mb-2">
                    <span class="flex h-9 w-9 flex-none items-center justify-center bg-amber-500/10 text-amber-600 border border-amber-500/20 rounded-md">
                        <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.89 15.672L6.255.47a.5.5 0 01.916-.176l3.52 6.643-6.8 8.735zm16.59-3.793l-2.08-10.74a.5.5 0 00-.89-.214L3.89 15.672l8.835 4.982a1.5 1.5 0 001.464 0l6.29-8.775zM12.9 8.01l2.58-4.912a.5.5 0 01.89.214l1.64 8.472L12.9 8.01z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white">Firebase Mobile App &amp; Cloud Messaging</h2>
                        <p class="text-[11px] text-neutral-400">Credentials for Flutter / React Native / iOS / Android mobile applications.</p>
                    </div>
                </div>

                <label class="flex items-center justify-between border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/50 px-4 py-3 cursor-pointer rounded-sm">
                    <span>
                        <span class="block text-xs font-semibold text-neutral-900 dark:text-white">Enable Firebase Services</span>
                        <span class="block text-[11px] text-neutral-500 dark:text-neutral-400 mt-0.5">Enables Firebase Auth bridge and Firebase Cloud Messaging (FCM) API for mobile apps.</span>
                    </span>
                    <input type="checkbox" name="firebase_enabled" value="1" class="control" @checked($settings['firebase_enabled'])>
                </label>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Firebase Project ID</label>
                    <input type="text" name="firebase_project_id" value="{{ $settings['firebase_project_id'] }}" placeholder="mono-lms-app"
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Firebase Web / Mobile API Key</label>
                    <input type="text" name="firebase_api_key" value="{{ $settings['firebase_api_key'] }}" placeholder="AIzaSyA1b2c3d4e5f6g7h8i9j0..."
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Auth Domain</label>
                        <input type="text" name="firebase_auth_domain" value="{{ $settings['firebase_auth_domain'] }}" placeholder="mono-lms-app.firebaseapp.com"
                               class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Messaging Sender ID</label>
                        <input type="text" name="firebase_messaging_sender_id" value="{{ $settings['firebase_messaging_sender_id'] }}" placeholder="123456789012"
                               class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Firebase App ID</label>
                    <input type="text" name="firebase_app_id" value="{{ $settings['firebase_app_id'] }}" placeholder="1:123456789012:web:abcdef123456"
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">FCM Server Key / Service Key</label>
                    <input type="password" name="firebase_fcm_server_key" value="{{ $settings['firebase_fcm_server_key'] }}" placeholder="AAAA... or Private Key string"
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4 flex items-center justify-between shadow-xs">
            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                All parameters are securely stored and available across web and future mobile application APIs.
            </span>
            <button type="submit" class="action-btn h-10 px-6 font-semibold">
                Save Google &amp; Firebase Settings
            </button>
        </div>
    </form>

    <!-- Setup Guide Card -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white mb-3">Mobile &amp; Web Integration Guide</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-neutral-600 dark:text-neutral-400">
            <div>
                <p class="font-semibold text-neutral-900 dark:text-white mb-2">1. Google OAuth Setup (Web Storefront):</p>
                <ol class="space-y-1.5 list-decimal list-inside">
                    <li>Sign in to <a href="{{ $consoleUrl }}" target="_blank" rel="noopener" class="font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">Google Cloud Console</a>.</li>
                    <li>Create an OAuth 2.0 Web Application client under Credentials.</li>
                    <li>Paste the Authorized Redirect URIs shown above.</li>
                    <li>Copy Client ID and Client Secret into the form above and save.</li>
                </ol>
            </div>
            <div>
                <p class="font-semibold text-neutral-900 dark:text-white mb-2">2. Firebase Setup (Future Mobile App &amp; Push):</p>
                <ol class="space-y-1.5 list-decimal list-inside">
                    <li>Open <a href="{{ $firebaseConsoleUrl }}" target="_blank" rel="noopener" class="font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">Firebase Console</a> and link your Google Cloud project.</li>
                    <li>Add iOS/Android apps in Project Settings to download <code class="font-mono">google-services.json</code> / <code class="font-mono">GoogleService-Info.plist</code>.</li>
                    <li>Copy your Project ID, API Key, and App ID into the Firebase fields above.</li>
                    <li>Mobile apps will authenticate and receive push notifications seamlessly using these credentials.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.copy-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.getAttribute('data-copy');
            var done = function () {
                var label = btn.querySelector('span');
                var old = label.textContent;
                label.textContent = 'Copied';
                btn.classList.add('copied');
                setTimeout(function () { label.textContent = old; btn.classList.remove('copied'); }, 1600);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done);
            } else {
                var ta = document.createElement('textarea');
                ta.value = text; document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta); done();
            }
        });
    });
</script>

</x-layouts.dashboard>
