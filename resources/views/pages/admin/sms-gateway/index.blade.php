<x-layouts.dashboard title="SMS Gateway Devices - Mono LMS" active="sms_gateway" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 36px; padding: 0 14px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn-ghost { color: #171717; background: #ffffff; border-color: #e5e5e5; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #f5f5f5; border-color: #d4d4d4; }
    .action-btn-danger { color: #b91c1c; background: #fef2f2; border-color: #fecaca; }
    .action-btn-danger:hover { color: #991b1b; background: #fee2e2; border-color: #fca5a5; }
    .copy-btn { display: inline-flex; align-items: center; gap: 6px; height: 28px; padding: 0 10px; font-size: 10px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #525252; background: #fafafa; border: 1px solid #d4d4d4; cursor: pointer; transition: all .15s; }
    .copy-btn:hover { color: #0a0a0a; border-color: #0a0a0a; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Android SIM Gateway Devices</h1>
                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                    {{ $active ? 'Active: ' . $active->name : 'No Active Device' }}
                </span>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Pair Android phones running the <code>sms_gateway</code> companion app to send SMS using physical SIM cards.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if ($active)
                <form method="POST" action="{{ route('admin.sms-gateway.nudge') }}">
                    @csrf
                    <button type="submit" class="action-btn action-btn-ghost text-xs">
                        <svg class="mr-1.5 h-3.5 w-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        Nudge Active Phone (FCM Wake)
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.sms-gateway.log') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                SMS Outbound/Inbound Logs
            </a>
            <a href="{{ route('admin.settings.sms') }}" class="action-btn action-btn-ghost text-xs" style="text-decoration:none">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                SMS Settings
            </a>
            <button type="button" onclick="document.getElementById('add-device-modal').classList.remove('hidden')" class="action-btn">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Register Device
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="border border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-4 text-sm text-emerald-800 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('gateway_token'))
        <!-- Secret Token Alert - Shown Only Once -->
        <div class="border-2 border-amber-400 bg-amber-50 dark:bg-amber-950/40 p-5 rounded-sm space-y-3">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-amber-200 dark:bg-amber-900 rounded-full text-amber-900 dark:text-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Save Device Token for: {{ session('gateway_token_device') }}</h3>
                    <p class="text-xs text-neutral-600 dark:text-neutral-300 mt-1">
                        This bearer token will <strong>never be shown again</strong>. Enter it into the Android Gateway app if connecting manually, or keep it safe.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <code id="device-token-code" class="flex-1 p-2.5 bg-white dark:bg-[#111111] border border-amber-300 dark:border-amber-800 text-xs font-mono text-neutral-900 dark:text-white break-all select-all">{{ session('gateway_token') }}</code>
                <button type="button" onclick="navigator.clipboard.writeText('{{ session('gateway_token') }}'); this.innerText='COPIED!'" class="copy-btn">Copy</button>
            </div>
        </div>
    @endif

    <!-- Device Cards List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($devices as $device)
            <div class="border {{ $device->is_active ? 'border-emerald-500 dark:border-emerald-600 shadow-sm' : 'border-neutral-200 dark:border-neutral-800' }} bg-white dark:bg-[#111111] p-5 space-y-4 rounded-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full {{ $device->isOnline() ? 'bg-emerald-500 animate-pulse' : 'bg-neutral-300 dark:bg-neutral-700' }}" title="{{ $device->isOnline() ? 'Online (seen recently)' : 'Offline' }}"></span>
                            <div>
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $device->name }}</h3>
                                <p class="text-[11px] text-neutral-400">ID: #{{ $device->id }} &bull; {{ $device->device_id ? Str::limit($device->device_id, 16) : 'Hardware ID unset' }}</p>
                            </div>
                        </div>
                        @if ($device->is_active)
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 rounded">Active Sender</span>
                        @endif
                    </div>

                    <!-- Diagnostics Grid -->
                    <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-neutral-100 dark:border-neutral-800 text-[11px]">
                        <div>
                            <span class="text-neutral-400 block">Carrier</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $device->carrier ?: 'Unknown' }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block">SIM Phone</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200 font-mono">{{ $device->sim_number ?: 'Not reported' }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block">Battery Level</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 {{ ($device->battery_level ?? 0) < 20 ? 'text-red-500' : 'text-emerald-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10.5h.75v3H21M3.75 6.75h15a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 012.25 15.75v-7.5a1.5 1.5 0 011.5-1.5z"/></svg>
                                {{ $device->battery_level !== null ? $device->battery_level . '%' : 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block">App Version</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200">v{{ $device->app_version ?: '1.0.0' }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block">Sent / Failed</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200">
                                <strong class="text-emerald-600">{{ $device->sent_count }}</strong> / <strong class="text-red-500">{{ $device->failed_count }}</strong>
                            </span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block">Last Seen</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $device->last_seen_at ? $device->last_seen_at->diffForHumans() : 'Never' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Device Actions -->
                <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-between gap-1 flex-wrap">
                    <form method="POST" action="{{ route('admin.sms-gateway.active', $device) }}">
                        @csrf
                        <button type="submit" class="copy-btn {{ $device->is_active ? 'bg-neutral-200 text-neutral-800' : '' }}">
                            {{ $device->is_active ? 'Deactivate' : 'Set Active' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.sms-gateway.toggle', $device) }}">
                        @csrf
                        <button type="submit" class="copy-btn">
                            {{ $device->is_enabled ? 'Disable' : 'Enable' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.sms-gateway.revoke', $device) }}" onsubmit="return confirm('Rotate token? The connected phone will stop working until updated with the new token.')">
                        @csrf
                        <button type="submit" class="copy-btn text-amber-700">Rotate Token</button>
                    </form>

                    <form method="POST" action="{{ route('admin.sms-gateway.destroy', $device) }}" onsubmit="return confirm('Delete this device?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="copy-btn text-red-600 hover:text-red-800">&times;</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full border border-dashed border-neutral-300 dark:border-neutral-800 p-12 text-center bg-white dark:bg-[#111111]">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-3 text-neutral-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">No SIM Gateway Devices Registered</h3>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1 max-w-sm mx-auto">
                    Click "Register Device" above to add your first Android phone, or sign in directly from the <code>sms_gateway</code> Flutter app using your admin credentials.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Register Device Modal -->
    <div id="add-device-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs hidden p-4">
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 max-w-md w-full shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Register Gateway Device</h3>
                <button type="button" onclick="document.getElementById('add-device-modal').classList.add('hidden')" class="text-neutral-400 hover:text-neutral-900 text-lg">&times;</button>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                Give this phone a recognizable label (e.g. <em>Office Samsung Galaxy A14</em>). A unique token will be generated upon creation.
            </p>
            <form method="POST" action="{{ route('admin.sms-gateway.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Device Name / Label</label>
                    <input type="text" name="name" required placeholder="e.g. Gateway Pixel 7" class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 focus:outline-none">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('add-device-modal').classList.add('hidden')" class="action-btn action-btn-ghost text-xs">Cancel</button>
                    <button type="submit" class="action-btn text-xs">Register Phone</button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-layouts.dashboard>
