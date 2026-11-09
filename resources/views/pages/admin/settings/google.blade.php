<x-layouts.dashboard title="Google Settings - Mono LMS" active="google_settings" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .copy-btn { display: inline-flex; align-items: center; gap: 6px; height: 30px; padding: 0 10px; font-size: 10px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #525252; background: #fafafa; border: 1px solid #d4d4d4; cursor: pointer; transition: all .15s; }
    .copy-btn:hover { color: #0a0a0a; border-color: #0a0a0a; }
    .copy-btn.copied { color: #067647; background: #ecfdf5; border-color: #6ee7b7; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 bg-white p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900">Google Login Settings</h1>
                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $settings['enabled'] ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-neutral-100 text-neutral-500 border border-neutral-200' }}">
                    {{ $settings['enabled'] ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">Let students and instructors sign in with their Google accounts using OAuth 2.0.</p>
        </div>
        <a href="{{ $consoleUrl }}" target="_blank" rel="noopener" class="action-btn action-btn-ghost" style="text-decoration:none">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Open Google Cloud Console
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-xs font-medium">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <!-- Credentials -->
        <div class="border border-neutral-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">OAuth Credentials</h2>
                    <p class="text-[11px] text-neutral-400">From your Google Cloud Console OAuth 2.0 Client (Web application).</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.google.store') }}" class="space-y-4">
                @csrf

                <label class="flex items-center justify-between border border-neutral-200 bg-neutral-50 px-4 py-3 cursor-pointer">
                    <span>
                        <span class="block text-xs font-semibold text-neutral-900">Enable Google Login</span>
                        <span class="block text-[11px] text-neutral-500 mt-0.5">Shows "Sign in with Google" buttons on the login pages.</span>
                    </span>
                    <input type="checkbox" name="google_login_enabled" value="1" class="control" @checked($settings['enabled'])>
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center justify-between border border-neutral-200 px-4 py-3 cursor-pointer">
                        <span class="text-xs font-semibold text-neutral-900">Students</span>
                        <input type="checkbox" name="google_allow_student" value="1" class="control" @checked($settings['allow_student'])>
                    </label>
                    <label class="flex items-center justify-between border border-neutral-200 px-4 py-3 cursor-pointer">
                        <span class="text-xs font-semibold text-neutral-900">Instructors</span>
                        <input type="checkbox" name="google_allow_instructor" value="1" class="control" @checked($settings['allow_instructor'])>
                    </label>
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Client ID *</label>
                    <input type="text" name="google_client_id" value="{{ $settings['client_id'] }}" placeholder="1234567890-abc123.apps.googleusercontent.com" required
                           class="h-10 w-full font-mono border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Client Secret *</label>
                    <input type="password" name="google_client_secret" value="{{ $settings['client_secret'] }}" placeholder="GOCSPX-••••••••••••••••" required
                           class="h-10 w-full font-mono border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                </div>

                <div class="flex items-center justify-end pt-3 border-t border-neutral-100">
                    <button type="submit" class="action-btn h-10 px-6">Save Google Settings</button>
                </div>
            </form>
        </div>

        <!-- Callback URLs + setup guide -->
        <div class="space-y-6">
            <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-3 border-b border-neutral-100 pb-4 mb-5">
                    <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Authorized Redirect URIs</h2>
                        <p class="text-[11px] text-neutral-400">Add these exact URIs in Google Cloud Console under your OAuth client.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach ($callbacks as $label => $url)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ ucfirst($label) }} Callback</span>
                                <button type="button" class="copy-btn" data-copy="{{ $url }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="h-10 flex items-center border border-neutral-300 bg-neutral-50 px-3.5 text-[11px] font-mono text-neutral-900 break-all">{{ $url }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Setup guide -->
            <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900 mb-4">How to get credentials</h2>
                <ol class="space-y-3 text-xs text-neutral-600 list-decimal list-inside">
                    <li>Open <a href="{{ $consoleUrl }}" target="_blank" rel="noopener" class="font-semibold text-[#344c3d] hover:underline">console.cloud.google.com/apis/credentials</a> and sign in.</li>
                    <li>Create (or select) a project, then click <strong>Create Credentials &rarr; OAuth client ID</strong>.</li>
                    <li>If asked, first configure the <strong>OAuth consent screen</strong> (External is fine for testing).</li>
                    <li>Choose <strong>Web application</strong> as the application type.</li>
                    <li>Under <strong>Authorized redirect URIs</strong>, add both callback URLs on the left.</li>
                    <li>Copy the <strong>Client ID</strong> and <strong>Client Secret</strong> into the form on the left and save.</li>
                </ol>
                <p class="text-[11px] text-neutral-400 mt-4">Tip: for local testing, <code class="font-mono">http://localhost</code> URIs are allowed by Google without HTTPS.</p>
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
