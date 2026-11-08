<x-layouts.dashboard title="System & Updates - Mono LMS" active="system" userType="admin">

    <div class="max-w-4xl space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">System & Maintenance</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        System Healthy
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Server environment health, PHP / framework runtime diagnostics, and cache management tools.</p>
            </div>

            <!-- Clear Cache Action Button -->
            <form method="POST" action="{{ route('admin.system.clear-cache') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white transition-colors shadow-xs">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Clear System Cache</span>
                </button>
            </form>
        </div>

        <!-- System Diagnostic Details Table -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-800">Environment Diagnostics</span>
                <span class="text-[11px] text-slate-400 font-mono">PHP {{ $info['php_version'] }}</span>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">PHP Version</span>
                    <span class="font-mono font-bold text-slate-900">{{ $info['php_version'] }} (Latest)</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Framework Engine</span>
                    <span class="font-mono font-bold text-[#344c3d]">Laravel {{ $info['laravel_version'] }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Application Environment</span>
                    <span class="font-mono font-bold uppercase text-slate-800">{{ $info['app_env'] }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Database Driver</span>
                    <span class="font-mono font-bold uppercase text-slate-800">{{ $info['database_connection'] }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Cache Driver</span>
                    <span class="font-mono font-bold text-slate-800">{{ $info['cache_driver'] }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Session Driver</span>
                    <span class="font-mono font-bold text-slate-800">{{ $info['session_driver'] }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Server Software</span>
                    <span class="font-mono text-slate-800 text-[11px]">{{ $info['server_software'] }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-500 font-medium">System Timezone</span>
                    <span class="font-mono font-bold text-slate-800">{{ $info['timezone'] }}</span>
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
