<x-layouts.dashboard title="Mail & Storage - Mono LMS" active="services" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn:disabled { opacity: .5; cursor: wait; }
    .action-btn-ghost { color: #525252; background: #fff; border-color: #d4d4d4; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #fafafa; border-color: #0a0a0a; }
</style>

<div class="space-y-6">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 bg-white p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900">Mail & Storage Services</h1>
                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Active
                </span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">Configure SMTP email delivery (student receipts, password resets, alerts) and cloud or local file storage.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs text-neutral-600">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Mail: <strong class="font-mono">{{ $mail['host'] ?: 'Unset' }}</strong></span>
            </div>
            <div class="flex items-center gap-2 border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs text-neutral-600">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                <span>Storage: <strong class="font-mono uppercase">{{ $storage['driver'] ?? 'public' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Main 2-Column Full Width Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <!-- 1. Outgoing Mail (SMTP) -->
        <div class="space-y-6">
            <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Outgoing Mail (SMTP)</h2>
                            <p class="text-[11px] text-neutral-400">Essential for student receipts, password resets, and notifications.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.services.mail') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">SMTP Server Host *</label>
                            <input type="text" name="mail_host" value="{{ $mail['host'] }}" placeholder="e.g. smtp.mailgun.org, smtp.sendgrid.net" required
                                   class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Port *</label>
                            <input type="text" name="mail_port" value="{{ $mail['port'] }}" placeholder="587" required
                                   class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">SMTP Username</label>
                            <input type="text" name="mail_username" value="{{ $mail['username'] }}" placeholder="SMTP username / API key user"
                                   class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">SMTP Password</label>
                            <div class="relative">
                                <input type="password" name="mail_password" id="input-mail-pass" value="{{ $mail['password'] }}" placeholder="••••••••"
                                       class="h-10 w-full font-mono border border-neutral-300 rounded-lg pl-3.5 pr-10 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                <button type="button" id="btn-toggle-mail-pass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-neutral-400 hover:text-neutral-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Encryption *</label>
                            <select name="mail_encryption" data-mselect data-mselect-no-search class="h-10 w-full cursor-pointer border border-neutral-300 rounded-lg bg-white px-3 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                                <option value="tls" @selected($mail['encryption'] === 'tls')>TLS (Recommended)</option>
                                <option value="ssl" @selected($mail['encryption'] === 'ssl')>SSL</option>
                                <option value="none" @selected($mail['encryption'] === 'none')>None (Plain)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">From Email Address *</label>
                            <input type="email" name="mail_from_address" value="{{ $mail['from_address'] }}" required
                                   class="h-10 w-full border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">From Sender Name *</label>
                            <input type="text" name="mail_from_name" value="{{ $mail['from_name'] }}" required
                                   class="h-10 w-full border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                        </div>
                    </div>

                    <!-- Email OTP Verification Toggle -->
                    <div class="border-t border-neutral-100 pt-4">
                        <input type="hidden" name="otp_setting_present" value="1">
                        <label class="flex items-start justify-between gap-3 p-3 border border-neutral-200 bg-neutral-50/70 hover:border-neutral-900 transition-colors cursor-pointer select-none">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="otp_signup_enabled" value="1" @checked($otp_signup_enabled ?? false) class="control mt-0.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-neutral-900 block">Require Email OTP for Sign Up</span>
                                        <span class="text-[9px] font-semibold bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded">Student &amp; Instructor</span>
                                    </div>
                                    <span class="text-[11px] text-neutral-500">Sends 6-digit OTP code to verifying email via SMTP before creating student or instructor accounts.</span>
                                </div>
                            </div>
                            <span class="inline-flex h-5 items-center px-2 text-[9px] font-bold uppercase tracking-wider {{ ($otp_signup_enabled ?? false) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-100 text-neutral-500 border border-transparent' }}">
                                {{ ($otp_signup_enabled ?? false) ? 'Active' : 'Disabled' }}
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-neutral-100">
                        <button type="submit" class="action-btn h-10 px-6">
                            Save Mail Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Test Email Dispatch Card -->
            <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-3 mb-3">
                    <span class="flex h-8 w-8 flex-none items-center justify-center bg-neutral-100 text-neutral-700">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Send Test Email</h3>
                        <p class="text-[11px] text-neutral-400">Verify your SMTP credentials by dispatching a test message.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.services.mail.test') }}" class="flex flex-col sm:flex-row items-center gap-3">
                    @csrf
                    <input type="email" name="test_email" required value="{{ auth('admin')->user()?->email ?? 'admin@learnify.test' }}" placeholder="Enter recipient email..."
                           class="h-10 w-full sm:flex-1 border border-neutral-300 px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                    <button type="submit" class="action-btn action-btn-ghost h-10 px-5 shrink-0 w-full sm:w-auto">
                        Dispatch Test Email
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. File & Cloud Storage -->
        <div class="space-y-6">
            <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Media & File Storage</h2>
                            <p class="text-[11px] text-neutral-400">Configure file storage disk for course videos, PDFs, and certificates.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.services.storage') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Storage Driver *</label>
                        <select name="storage_driver" id="storage-driver-select" data-mselect data-mselect-no-search
                                class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            <option value="public" @selected(($storage['driver'] ?? 'public') === 'public')>Local Disk Storage (public/storage)</option>
                            <option value="s3" @selected(($storage['driver'] ?? '') === 's3')>Amazon S3 / DigitalOcean Spaces / MinIO</option>
                        </select>
                    </div>

                    <div id="s3-config-fields" class="{{ ($storage['driver'] ?? 'public') === 's3' ? '' : 'hidden' }} space-y-4 border border-neutral-200 bg-neutral-50/60 p-4">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500 block">S3 / S3-Compatible Bucket Settings</span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Bucket Name</label>
                                <input type="text" name="storage_s3_bucket" value="{{ $storage['s3_bucket'] }}" placeholder="e.g. mono-lms-assets"
                                       class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Region</label>
                                <input type="text" name="storage_s3_region" value="{{ $storage['s3_region'] }}" placeholder="e.g. us-east-1"
                                       class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Access Key ID</label>
                                <input type="text" name="storage_s3_key" value="{{ $storage['s3_key'] }}" placeholder="AKIA..."
                                       class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-500">Secret Access Key</label>
                                <input type="password" name="storage_s3_secret" value="{{ $storage['s3_secret'] }}" placeholder="••••••••••••••••••••••••"
                                       class="h-10 w-full font-mono border border-neutral-300 rounded-lg px-3.5 text-xs text-neutral-900 focus:border-neutral-900 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Storage Paths Overview -->
                    <div class="border border-neutral-200 bg-white p-4">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 block mb-2.5">Asset Directory Structure</span>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="font-mono text-[11px] text-neutral-600">/courses</span>
                                <span class="text-[10px] text-neutral-400">— Lessons & Attachments</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="font-mono text-[11px] text-neutral-600">/avatars</span>
                                <span class="text-[10px] text-neutral-400">— Profile Photos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="font-mono text-[11px] text-neutral-600">/certificates</span>
                                <span class="text-[10px] text-neutral-400">— Generated Diplomas</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="font-mono text-[11px] text-neutral-600">/media</span>
                                <span class="text-[10px] text-neutral-400">— Media Library Assets</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-neutral-100">
                        <button type="submit" class="action-btn h-10 px-6">
                            Save Storage Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var passInput = document.getElementById('input-mail-pass');
    var toggleBtn = document.getElementById('btn-toggle-mail-pass');
    if (toggleBtn && passInput) {
        toggleBtn.addEventListener('click', function () {
            var isPass = passInput.type === 'password';
            passInput.type = isPass ? 'text' : 'password';
        });
    }

    var driverSelect = document.getElementById('storage-driver-select');
    var s3Fields = document.getElementById('s3-config-fields');
    if (driverSelect && s3Fields) {
        driverSelect.addEventListener('change', function () {
            if (driverSelect.value === 's3') {
                s3Fields.classList.remove('hidden');
            } else {
                s3Fields.classList.add('hidden');
            }
        });
    }
})();
</script>

</x-layouts.dashboard>
