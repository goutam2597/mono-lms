<x-layouts.dashboard title="System & Updates - Mono LMS" active="system" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn:disabled { opacity: .5; cursor: wait; }
</style>

<div class="space-y-6">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 bg-white p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900">System & Maintenance</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    System Operational
                </span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">Server environment health, PHP and framework runtime diagnostics, and system cache controls.</p>
        </div>

        <form method="POST" action="{{ route('admin.system.clear-cache') }}">
            @csrf
            <button type="submit" class="action-btn h-10 px-6">
                <svg class="mr-2 h-3.5 w-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Clear All System Cache
            </button>
        </form>
    </div>

    <!-- Diagnostic KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-400">PHP Runtime</p>
                    <p class="mt-2 font-mono text-xl font-bold tracking-tight text-neutral-900">PHP {{ $info['php_version'] }}</p>
                    <p class="text-[11px] text-neutral-400 mt-0.5 font-medium">{{ PHP_OS_FAMILY }} · {{ php_sapi_name() }}</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-100 text-neutral-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </span>
            </div>
        </div>

        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-400">Framework Engine</p>
                    <p class="mt-2 font-mono text-xl font-bold tracking-tight text-neutral-900">Laravel {{ $info['laravel_version'] }}</p>
                    <p class="text-[11px] text-neutral-400 mt-0.5 font-medium uppercase">{{ $info['app_env'] }} mode</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-red-50 text-red-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </span>
            </div>
        </div>

        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-400">Database Engine</p>
                    <p class="mt-2 font-mono text-xl font-bold tracking-tight text-neutral-900 uppercase">{{ $info['database_connection'] }}</p>
                    <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">● Connected</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-blue-50 text-blue-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                </span>
            </div>
        </div>

        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-400">Cache & Session</p>
                    <p class="mt-2 font-mono text-xl font-bold tracking-tight text-neutral-900 capitalize">{{ $info['cache_driver'] }}</p>
                    <p class="text-[11px] text-neutral-400 mt-0.5 font-medium">Session: {{ ucfirst($info['session_driver']) }}</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-amber-50 text-amber-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Full-Width 2-Column Diagnostic Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <!-- 1. Environment & Framework Diagnostics -->
        <div class="border border-neutral-200 bg-white shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-7 w-7 items-center justify-center bg-neutral-900 text-white text-xs">01</span>
                    <h2 class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-900">Environment Diagnostics</h2>
                </div>
                <span class="text-[11px] font-mono text-neutral-400">Laravel v{{ $info['laravel_version'] }}</span>
            </div>

            <div class="divide-y divide-neutral-100 text-xs">
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">PHP Version</span>
                    <span class="font-mono font-bold text-neutral-900 bg-neutral-100 px-2.5 py-0.5 border border-neutral-200">{{ $info['php_version'] }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Framework Engine</span>
                    <span class="font-mono font-bold text-neutral-900">Laravel {{ $info['laravel_version'] }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Application Environment</span>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $info['app_env'] === 'production' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                        {{ $info['app_env'] }}
                    </span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Debug Mode</span>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $info['app_debug'] === 'Enabled' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-neutral-100 text-neutral-600' }}">
                        {{ $info['app_debug'] }}
                    </span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Database Driver</span>
                    <span class="font-mono font-bold uppercase text-neutral-900">{{ $info['database_connection'] }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Cache Driver</span>
                    <span class="font-mono text-neutral-900">{{ $info['cache_driver'] }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Session Driver</span>
                    <span class="font-mono text-neutral-900">{{ $info['session_driver'] }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Queue Connection</span>
                    <span class="font-mono text-neutral-900">{{ $info['queue_connection'] ?? 'sync' }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">System Timezone</span>
                    <span class="font-mono font-bold text-neutral-900">{{ $info['timezone'] }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Server & PHP Host Environment Details -->
        <div class="border border-neutral-200 bg-white shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-7 w-7 items-center justify-center bg-neutral-900 text-white text-xs">02</span>
                    <h2 class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-900">Server & Host Platform</h2>
                </div>
                <span class="text-[11px] font-mono text-neutral-400">{{ PHP_OS_FAMILY }}</span>
            </div>

            <div class="divide-y divide-neutral-100 text-xs">
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Web Server Software</span>
                    <span class="font-mono text-neutral-900 text-[11px] truncate max-w-xs">{{ $info['server_software'] }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">PHP SAPI Interface</span>
                    <span class="font-mono text-neutral-900 uppercase">{{ php_sapi_name() }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">PHP Memory Limit</span>
                    <span class="font-mono font-bold text-neutral-900">{{ ini_get('memory_limit') ?: 'N/A' }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Max Upload File Size</span>
                    <span class="font-mono font-bold text-neutral-900">{{ ini_get('upload_max_filesize') ?: 'N/A' }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">POST Max Size</span>
                    <span class="font-mono font-bold text-neutral-900">{{ ini_get('post_max_size') ?: 'N/A' }}</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Max Execution Time</span>
                    <span class="font-mono text-neutral-900">{{ ini_get('max_execution_time') }} seconds</span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Zend OPcache</span>
                    @php $opcacheEnabled = extension_loaded('Zend OPcache') && ini_get('opcache.enable'); @endphp
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $opcacheEnabled ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-neutral-100 text-neutral-600' }}">
                        {{ $opcacheEnabled ? 'Active' : 'Disabled' }}
                    </span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">Public Storage Symlink</span>
                    @php $symlinkValid = is_link(public_path('storage')) || is_dir(public_path('storage')); @endphp
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $symlinkValid ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                        {{ $symlinkValid ? 'Linked' : 'Missing' }}
                    </span>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-neutral-50/60 transition-colors">
                    <span class="font-medium text-neutral-600">App URL</span>
                    <span class="font-mono text-neutral-900 text-[11px] truncate max-w-xs">{{ config('app.url') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Maintenance Controls Card -->
    <div class="border border-neutral-200 bg-white p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">Application Cache & Maintenance</h3>
                <p class="text-xs text-neutral-500 mt-0.5">Flush cached configurations, compiled blade view templates, and framework cache keys to reflect recent changes immediately.</p>
            </div>
            <form method="POST" action="{{ route('admin.system.clear-cache') }}">
                @csrf
                <button type="submit" class="action-btn h-10 px-6">
                    Flush & Rebuild Cache
                </button>
            </form>
        </div>
    </div>
</div>

</x-layouts.dashboard>
