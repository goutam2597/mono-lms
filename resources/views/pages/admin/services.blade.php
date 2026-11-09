<x-layouts.dashboard title="Mail & Storage - Mono LMS" active="services" userType="admin">

    <div class="space-y-6 max-w-4xl">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 p-6 shadow-xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Mail & Storage Services</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure SMTP email delivery (for password resets, receipts, notifications) and cloud file storage.</p>
            </div>
        </div>

        <!-- Outgoing Mail (SMTP) Card -->
        <div class="bg-white border border-slate-200 p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Outgoing Mail (SMTP)</h3>
                        <p class="text-[11px] text-slate-400">Essential for student receipts, password resets, and admin alerts.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.services.mail') }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SMTP Server Host</label>
                        <input type="text" name="mail_host" value="{{ $mail['host'] }}" placeholder="smtp.mailgun.org" class="w-full font-mono text-[11px] px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SMTP Port</label>
                        <input type="text" name="mail_port" value="{{ $mail['port'] }}" placeholder="587" class="w-full font-mono text-[11px] px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SMTP Username</label>
                        <input type="text" name="mail_username" value="{{ $mail['username'] }}" class="w-full font-mono text-[11px] px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SMTP Password</label>
                        <input type="password" name="mail_password" value="{{ $mail['password'] }}" class="w-full font-mono text-[11px] px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Encryption Protocol</label>
                        <select name="mail_encryption" class="w-full px-3.5 py-2.5 border border-slate-200 bg-white focus:outline-none focus:border-[#344c3d]">
                            <option value="tls" {{ $mail['encryption'] === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $mail['encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ $mail['encryption'] === 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">From Email Address</label>
                        <input type="email" name="mail_from_address" value="{{ $mail['from_address'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">From Sender Name</label>
                        <input type="text" name="mail_from_name" value="{{ $mail['from_name'] }}" class="w-full px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-[11px] font-mono text-slate-400">Settings are saved in system key-value registry.</span>
                    <button type="submit" class="action-btn action-btn-primary cursor-pointer">
                        Save Mail Settings
                    </button>
                </div>
            </form>

            <!-- Test Email Bar -->
            <div class="pt-4 border-t border-slate-200">
                <form method="POST" action="{{ route('admin.services.mail.test') }}" class="flex flex-col sm:flex-row items-center gap-2.5">
                    @csrf
                    <input type="email" name="test_email" required value="{{ auth('admin')->user()?->email ?? 'admin@learnify.test' }}" placeholder="Enter recipient email..." class="text-xs flex-1 px-3.5 py-2 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    <button type="submit" class="action-btn action-btn-ghost cursor-pointer shrink-0">
                        Send Test Email
                    </button>
                </form>
            </div>
        </div>

        <!-- File Storage Configuration Card -->
        <div class="bg-white border border-slate-200 p-6 shadow-xs space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200">
                <div class="w-8 h-8 bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Media & File Storage</h3>
                    <p class="text-[11px] text-slate-400">Configure disk storage driver for course videos, PDF resources, and certificates.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.services.storage') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Storage Driver</label>
                    <select name="storage_driver" class="w-full px-3.5 py-2.5 border border-slate-200 bg-white focus:outline-none focus:border-[#344c3d]">
                        <option value="public" {{ $storage['driver'] === 'public' ? 'selected' : '' }}>Local Disk Storage (public/storage)</option>
                        <option value="s3" {{ $storage['driver'] === 's3' ? 'selected' : '' }}>Amazon S3 / DigitalOcean Spaces Cloud</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">S3 / Spaces Bucket Name</label>
                        <input type="text" name="storage_s3_bucket" value="{{ $storage['s3_bucket'] }}" class="w-full font-mono text-[11px] px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">S3 Region</label>
                        <input type="text" name="storage_s3_region" value="{{ $storage['s3_region'] }}" class="w-full font-mono text-[11px] px-3.5 py-2.5 border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="action-btn action-btn-primary cursor-pointer">
                        Save Storage Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.dashboard>