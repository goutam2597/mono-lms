<x-layouts.dashboard title="Google & Firebase Settings - Mono LMS" active="google_settings" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
    .action-btn-danger { color: #b91c1c; background: #fef2f2; border-color: #fecaca; }
    .action-btn-danger:hover { color: #991b1b; background: #fee2e2; border-color: #fca5a5; }
    .dark .action-btn-danger { color: #f87171; background: #450a0a; border-color: #7f1d1d; }
    .dark .action-btn-danger:hover { color: #fca5a5; background: #7f1d1d; }
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
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $settings['enabled'] ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }}">
                        Google: {{ $settings['enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $settings['firebase_enabled'] ? 'bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }}">
                        Firebase: {{ $settings['firebase_enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $serviceAccount['has_file'] ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700' }}">
                        Admin SDK: {{ $serviceAccount['has_file'] ? 'Active' : 'Missing' }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Configure Google OAuth 2.0 social sign-in, Firebase credentials, and upload the Firebase Admin SDK private key for push notifications.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ $firebaseServiceAccountsUrl }}" target="_blank" rel="noopener" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                Service Accounts
            </a>
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

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 text-xs font-medium space-y-1">
            @foreach ($errors->all() as $error)
                <p>&bull; {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Standalone Action Forms -->
    <form id="firebase-test-form" method="POST" action="{{ route('admin.settings.google.firebase_test') }}" class="hidden">
        @csrf
    </form>
    <form id="firebase-destroy-form" method="POST" action="{{ route('admin.settings.google.firebase_service_account.destroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form method="POST" action="{{ route('admin.settings.google.store') }}" enctype="multipart/form-data" class="space-y-6">
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
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs space-y-5">
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

                <!-- SECTION: Firebase Admin SDK Service Account Upload for Push Notifications -->
                <div class="border border-neutral-200 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-900/40 p-4.5 rounded-sm space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-7 w-7 items-center justify-center rounded-sm bg-neutral-900 text-white dark:bg-neutral-800 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900 dark:text-white">Firebase Admin SDK Private Key</h3>
                                <p class="text-[10px] text-neutral-500 dark:text-neutral-400">Required for FCM v1 push notifications to mobile devices.</p>
                            </div>
                        </div>

                        @if ($serviceAccount['has_file'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Configured
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-sm">
                                Not Uploaded
                            </span>
                        @endif
                    </div>

                    @if ($serviceAccount['has_file'])
                        <!-- Active Service Account Details -->
                        <div class="border border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/40 dark:bg-emerald-950/20 p-3.5 rounded-sm space-y-2.5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Service Account Email</span>
                                    <span class="font-mono text-[11px] text-neutral-900 dark:text-white break-all">{{ $serviceAccount['client_email'] }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Project ID</span>
                                    <span class="font-mono text-[11px] text-neutral-900 dark:text-white">{{ $serviceAccount['project_id'] }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Stored Key File</span>
                                    <span class="font-mono text-[11px] text-neutral-900 dark:text-white">{{ $serviceAccount['filename'] }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Uploaded At</span>
                                    <span class="text-[11px] text-neutral-700 dark:text-neutral-300">
                                        {{ $serviceAccount['uploaded_at'] ? \Carbon\Carbon::parse($serviceAccount['uploaded_at'])->toDayDateTimeString() : 'Active' }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-between gap-2 flex-wrap">
                                <button type="button" onclick="document.getElementById('firebase-test-form').submit();" class="action-btn action-btn-ghost text-[10px] h-7 px-2.5">
                                    <svg class="w-3 h-3 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Test Push Auth
                                </button>
                                <button type="button" onclick="if(confirm('Are you sure you want to remove the Firebase Admin SDK private key? Server push notifications will be disabled.')) document.getElementById('firebase-destroy-form').submit();" class="action-btn action-btn-danger text-[10px] h-7 px-2.5">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Remove Key
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Dropzone for Uploading JSON -->
                    <div>
                        <div id="sdk-dropzone" class="relative border-2 border-dashed border-neutral-300 dark:border-neutral-700 hover:border-neutral-400 dark:hover:border-neutral-500 bg-white dark:bg-[#151515] p-5 text-center cursor-pointer transition-colors rounded-sm">
                            <input type="file" name="firebase_service_account_file" id="firebase_service_account_file" accept=".json,application/json" class="hidden">

                            <div id="dropzone-empty" class="space-y-1.5">
                                <div class="mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-neutral-900 dark:text-white">
                                        {{ $serviceAccount['has_file'] ? 'Upload replacement Service Account JSON' : 'Upload Firebase Admin SDK JSON' }}
                                    </p>
                                    <p class="text-[10px] text-neutral-500 dark:text-neutral-400">
                                        Click to browse or drag &amp; drop (.json file, max 2MB). Stored safely in private storage.
                                    </p>
                                </div>
                            </div>

                            <div id="dropzone-preview" class="hidden items-center justify-between bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-2.5 rounded-sm">
                                <div class="flex items-center gap-2.5 text-left truncate">
                                    <span class="flex h-7 w-7 flex-none items-center justify-center bg-amber-500/10 text-amber-600 border border-amber-500/20 rounded">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm1 7V3.5L18.5 9H15z"/></svg>
                                    </span>
                                    <div class="truncate">
                                        <p id="preview-filename" class="text-xs font-mono font-semibold text-neutral-900 dark:text-white truncate">service-account.json</p>
                                        <p id="preview-filesize" class="text-[10px] text-neutral-500 dark:text-neutral-400">0 KB - Ready to save</p>
                                    </div>
                                </div>
                                <button type="button" id="clear-selected-file" class="flex-none text-[11px] font-semibold text-red-600 hover:text-red-700 px-2 py-1">
                                    Cancel
                                </button>
                            </div>
                        </div>

                        <p class="text-[10px] text-neutral-400 dark:text-neutral-500 mt-1.5 flex items-center justify-between">
                            <span>Download via: <a href="{{ $firebaseServiceAccountsUrl }}" target="_blank" rel="noopener" class="underline text-[var(--theme-primary,#344c3d)] font-medium">Firebase Console &rarr; Service Accounts &rarr; Generate new private key</a></span>
                            <span class="text-neutral-400">FCM HTTP v1</span>
                        </p>
                    </div>
                </div>

                <!-- Basic Client Credentials -->
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
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">FCM Server Key (Legacy HTTP Fallback)</label>
                    <input type="password" name="firebase_fcm_server_key" value="{{ $settings['firebase_fcm_server_key'] }}" placeholder="AAAA... or Private Key string"
                           class="h-10 w-full font-mono border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#151515] px-3.5 text-xs text-neutral-900 dark:text-white focus:border-neutral-900 dark:focus:border-neutral-400 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-4 flex items-center justify-between shadow-xs">
            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                All credentials are saved securely. Private keys are never exposed in public web assets.
            </span>
            <button type="submit" class="action-btn h-10 px-6 font-semibold">
                Save Google &amp; Firebase Settings
            </button>
        </div>
    </form>

    <!-- Setup Guide Card -->
    <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white mb-3">Mobile App &amp; FCM Push Notification Integration Guide</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-neutral-600 dark:text-neutral-400">
            <div>
                <p class="font-semibold text-neutral-900 dark:text-white mb-2">1. Google OAuth Setup (Web Storefront):</p>
                <ol class="space-y-1.5 list-decimal list-inside">
                    <li>Sign in to <a href="{{ $consoleUrl }}" target="_blank" rel="noopener" class="font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">Google Cloud Console</a>.</li>
                    <li>Create an OAuth 2.0 Web Application client under Credentials.</li>
                    <li>Paste the Authorized Redirect URIs shown above into Authorized redirect URIs.</li>
                    <li>Copy Client ID and Client Secret into the form above and save.</li>
                </ol>
            </div>
            <div>
                <p class="font-semibold text-neutral-900 dark:text-white mb-2">2. Firebase Admin SDK &amp; Push Notifications:</p>
                <ol class="space-y-1.5 list-decimal list-inside">
                    <li>Open <a href="{{ $firebaseServiceAccountsUrl }}" target="_blank" rel="noopener" class="font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">Firebase Console Service Accounts</a> tab.</li>
                    <li>Select **Firebase Admin SDK** and click **Generate new private key**.</li>
                    <li>Upload the downloaded `.json` file in the **Firebase Admin SDK Private Key** box above.</li>
                    <li>The system will automatically authenticate with FCM v1 HTTP API and dispatch real-time push notifications to your mobile app users.</li>
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

    // Dropzone interaction
    (function () {
        var dropzone = document.getElementById('sdk-dropzone');
        var input = document.getElementById('firebase_service_account_file');
        var emptyState = document.getElementById('dropzone-empty');
        var previewState = document.getElementById('dropzone-preview');
        var filenameEl = document.getElementById('preview-filename');
        var filesizeEl = document.getElementById('preview-filesize');
        var clearBtn = document.getElementById('clear-selected-file');

        if (!dropzone || !input) return;

        dropzone.addEventListener('click', function (e) {
            if (e.target === clearBtn || (clearBtn && clearBtn.contains(e.target))) {
                return;
            }
            input.click();
        });

        input.addEventListener('change', function () {
            handleFiles(this.files);
        });

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('border-neutral-900', 'dark:border-neutral-400');
        });

        dropzone.addEventListener('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('border-neutral-900', 'dark:border-neutral-400');
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('border-neutral-900', 'dark:border-neutral-400');
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                handleFiles(e.dataTransfer.files);
            }
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                input.value = '';
                previewState.classList.add('hidden');
                previewState.classList.remove('flex');
                emptyState.classList.remove('hidden');
            });
        }

        function handleFiles(files) {
            if (!files || !files.length) return;
            var file = files[0];
            if (!file.name.endsWith('.json') && file.type !== 'application/json') {
                alert('Please select a valid .json Firebase service account file.');
                input.value = '';
                return;
            }
            filenameEl.textContent = file.name;
            var sizeKb = (file.size / 1024).toFixed(1);
            filesizeEl.textContent = sizeKb + ' KB - Ready to save';
            emptyState.classList.add('hidden');
            previewState.classList.remove('hidden');
            previewState.classList.add('flex');
        }
    })();
</script>

</x-layouts.dashboard>
