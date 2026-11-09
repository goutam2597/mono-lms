<x-layouts.dashboard :title="__('admin.settings.ai_heading') . ' - Mono LMS'" active="ai_settings" userType="admin">

<style>
    /* ---------------- Action buttons ---------------- */
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 38px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn:disabled { opacity: .5; cursor: wait; }
    .action-btn-danger { color: #fff; background: #0a0a0a; border-color: #0a0a0a; }
    .action-btn-danger:hover { color: #fff; background: #dc2626; border-color: #dc2626; }

    /* ---------------- Model multi-select dropdown ---------------- */
    .ai-ms { position: relative; flex: 1 1 auto; min-width: 0; }
    .ai-ms-btn { display: flex; align-items: center; justify-content: space-between; gap: 8px; width: 100%; height: 42px; padding: 0 14px; border: 1px solid #d4d4d4; background: #fff; font-size: 13px; color: #0a0a0a; cursor: pointer; font-family: inherit; text-align: left; transition: border-color .15s; }
    .ai-ms-btn:hover, .ai-ms.open .ai-ms-btn { border-color: #0a0a0a; }
    .ai-ms-label { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ai-ms-label.empty { color: #a3a3a3; }
    .ai-ms-btn svg { flex: none; width: 12px; height: 12px; transition: transform .15s; }
    .ai-ms.open .ai-ms-btn svg { transform: rotate(180deg); }
    .ai-ms-panel { display: none; position: absolute; z-index: 40; top: calc(100% + 4px); left: 0; right: 0; max-height: 260px; overflow-y: auto; border: 1px solid #0a0a0a; background: #fff; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .ai-ms.open .ai-ms-panel { display: block; }
    .ai-ms.up .ai-ms-panel { top: auto; bottom: calc(100% + 4px); }
    .ai-ms-search { position: sticky; top: 0; z-index: 1; padding: 8px; background: #fff; border-bottom: 1px solid #e5e5e5; }
    .ai-ms-search-input { width: 100%; height: 32px; padding: 0 10px; border: 1px solid #d4d4d4; font-size: 12px; font-family: inherit; }
    .ai-ms-search-input:focus { outline: none; border-color: #0a0a0a; }
    .ai-ms-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; font-size: 12px; color: #404040; cursor: pointer; user-select: none; }
    .ai-ms-item:hover { background: #f5f5f5; }
    .ai-ms-item input { flex: none; width: 14px; height: 14px; accent-color: #0a0a0a; }
    .ai-ms-empty { padding: 12px 14px; font-size: 12px; color: #a3a3a3; }

    /* ---------------- Chat playground ---------------- */
    .ai-pg-msg { max-width: 85%; padding: 10px 14px; border: 1px solid transparent; }
    .ai-pg-msg.user { align-self: flex-end; background: #0a0a0a; color: #fff; }
    .ai-pg-msg.ai { align-self: flex-start; background: #fff; border-color: #e5e5e5; color: #171717; }
    .ai-pg-msg.err { align-self: flex-start; background: #fef2f2; border-color: #fecaca; color: #991b1b; }
    .ai-pg-who { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; }
    .ai-pg-msg.user .ai-pg-who { color: #a3a3a3; }
    .ai-pg-msg.ai .ai-pg-who { color: #525252; }
    .ai-pg-body { margin-top: 4px; font-size: 13px; line-height: 1.55; white-space: pre-wrap; word-break: break-word; }
    .ai-pg-meta { margin-top: 6px; font-size: 10.5px; opacity: .7; font-variant-numeric: tabular-nums; }
    .ai-pg-thinking { animation: ai-pg-pulse 1.2s ease-in-out infinite; }
    @keyframes ai-pg-pulse { 0%, 100% { opacity: .35; } 50% { opacity: 1; } }
</style>

<div class="space-y-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-medium tracking-tight text-neutral-900">{{ __('admin.settings.ai_heading') }}</h2>
            <p class="mt-1 text-sm text-neutral-500">{{ __('admin.settings.ai_sub') }}</p>
        </div>
        <button type="button" id="ai-add-provider-btn" class="action-btn h-10 px-5 text-[11px]">
            + {{ __('admin.settings.ai_add') }}
        </button>
    </div>

    {{-- Token usage KPI stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">{{ __('admin.settings.ai_usage_today') }}</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-neutral-900">{{ number_format($usage['today']) }}</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-100 text-neutral-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                </span>
            </div>
        </div>
        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">{{ __('admin.settings.ai_usage_month') }}</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-neutral-900">{{ number_format($usage['month']) }}</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-100 text-neutral-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="16" rx="1"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                </span>
            </div>
        </div>
        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">{{ __('admin.settings.ai_usage_requests') }}</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-neutral-900">{{ number_format($usage['requests']) }}</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-100 text-neutral-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>
                </span>
            </div>
        </div>
        <div class="border border-neutral-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">{{ __('admin.settings.ai_usage_images') }}</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-neutral-900">{{ number_format($usage['images']) }}</p>
                </div>
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-100 text-neutral-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="1"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L6 21"/></svg>
                </span>
            </div>
        </div>
    </div>

    {{-- Active AI Role assignments --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Language Translator Model --}}
        <div class="flex flex-col border border-neutral-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 5h8M7 3v2c0 3-1.5 5.5-4 7M5 9c1.5 2 3.5 3.5 6 4M13 21l4-9 4 9M14.5 17h5"/></svg>
                </span>
                <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.settings.ai_translator') }}</h3>
            </div>
            <p class="mt-2 text-xs text-neutral-500 leading-relaxed">{{ __('admin.settings.ai_translator_sub') }}</p>
            <form method="POST" action="{{ route('admin.settings.ai.translator') }}" class="mt-4 flex flex-1 flex-col gap-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_model') }}</label>
                    <select name="selection" class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs focus:border-neutral-900 focus:outline-none">
                        <option value="">{{ __('admin.settings.ai_none') }}</option>
                        @foreach ($providers as $provider)
                            @foreach ($provider->availableModels() as $m)
                                <option value="{{ $provider->id }}|{{ $m }}" @selected($translatorValue === $provider->id.'|'.$m) @disabled(! $provider->is_active)>
                                    {{ $provider->name }} — {{ $m }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="ai-timeout-translate" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_timeout') }} (min)</label>
                    <input type="number" id="ai-timeout-translate" name="timeout_minutes" min="0"
                           max="{{ intdiv(\App\Services\AiTimeoutService::MAX_TIMEOUT, 60) }}" step="1"
                           value="{{ old('timeout_minutes', intdiv($aiTimeouts['translate'] ?? 0, 60)) }}"
                           class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    <p class="mt-1 text-[11px] text-neutral-400">
                        @php $aiMinutes = (int) old('timeout_minutes', intdiv($aiTimeouts['translate'] ?? 0, 60)); @endphp
                        @if ($aiMinutes > 0)
                            {{ __('admin.settings.ai_timeout_about', ['minutes' => $aiMinutes]) }}
                        @else
                            {{ __('admin.settings.ai_timeout_unlimited') }}
                        @endif
                    </p>
                </div>
                <button type="submit" class="mt-auto action-btn h-10 w-full">{{ __('admin.common.save') }}</button>
            </form>
        </div>

        {{-- Content Generator Model --}}
        <div class="flex flex-col border border-neutral-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.2 6.9L21 11.1l-6.8 2.2L12 20.2l-2.2-6.9L3 11.1l6.8-2.2L12 2z"/></svg>
                </span>
                <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.settings.ai_generator') }}</h3>
            </div>
            <p class="mt-2 text-xs text-neutral-500 leading-relaxed">{{ __('admin.settings.ai_generator_sub') }}</p>
            <form method="POST" action="{{ route('admin.settings.ai.generator') }}" class="mt-4 flex flex-1 flex-col gap-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_model') }}</label>
                    <select name="selection" class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs focus:border-neutral-900 focus:outline-none">
                        <option value="">{{ __('admin.settings.ai_none') }}</option>
                        @foreach ($providers as $provider)
                            @foreach ($provider->availableModels() as $m)
                                <option value="{{ $provider->id }}|{{ $m }}" @selected($generatorValue === $provider->id.'|'.$m) @disabled(! $provider->is_active)>
                                    {{ $provider->name }} — {{ $m }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="ai-timeout-generate" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_timeout') }} (min)</label>
                    <input type="number" id="ai-timeout-generate" name="timeout_minutes" min="0"
                           max="{{ intdiv(\App\Services\AiTimeoutService::MAX_TIMEOUT, 60) }}" step="1"
                           value="{{ old('timeout_minutes', intdiv($aiTimeouts['generate'] ?? 0, 60)) }}"
                           class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    <p class="mt-1 text-[11px] text-neutral-400">
                        @php $aiMinutes = (int) old('timeout_minutes', intdiv($aiTimeouts['generate'] ?? 0, 60)); @endphp
                        @if ($aiMinutes > 0)
                            {{ __('admin.settings.ai_timeout_about', ['minutes' => $aiMinutes]) }}
                        @else
                            {{ __('admin.settings.ai_timeout_unlimited') }}
                        @endif
                    </p>
                </div>
                <button type="submit" class="mt-auto action-btn h-10 w-full">{{ __('admin.common.save') }}</button>
            </form>
        </div>

        {{-- Image Generator Model --}}
        <div class="flex flex-col border border-neutral-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="1"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L6 21"/></svg>
                </span>
                <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.settings.ai_image') }}</h3>
            </div>
            <p class="mt-2 text-xs text-neutral-500 leading-relaxed">{{ __('admin.settings.ai_image_sub') }}</p>
            <form method="POST" action="{{ route('admin.settings.ai.image') }}" class="mt-4 flex flex-1 flex-col gap-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_model') }}</label>
                    <select name="selection" class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs focus:border-neutral-900 focus:outline-none">
                        <option value="">{{ __('admin.settings.ai_none') }}</option>
                        @foreach ($providers->where('type', 'openai') as $provider)
                            @foreach ($provider->availableModels() as $m)
                                <option value="{{ $provider->id }}|{{ $m }}" @selected($imageValue === $provider->id.'|'.$m) @disabled(! $provider->is_active)>
                                    {{ $provider->name }} — {{ $m }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="ai-timeout-image" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_timeout') }} (min)</label>
                    <input type="number" id="ai-timeout-image" name="timeout_minutes" min="0"
                           max="{{ intdiv(\App\Services\AiTimeoutService::MAX_TIMEOUT, 60) }}" step="1"
                           value="{{ old('timeout_minutes', intdiv($aiTimeouts['image'] ?? 0, 60)) }}"
                           class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    <p class="mt-1 text-[11px] text-neutral-400">
                        @php $aiMinutes = (int) old('timeout_minutes', intdiv($aiTimeouts['image'] ?? 0, 60)); @endphp
                        @if ($aiMinutes > 0)
                            {{ __('admin.settings.ai_timeout_about', ['minutes' => $aiMinutes]) }}
                        @else
                            {{ __('admin.settings.ai_timeout_unlimited') }}
                        @endif
                    </p>
                </div>
                <button type="submit" class="mt-auto action-btn h-10 w-full">{{ __('admin.common.save') }}</button>
            </form>
        </div>
    </div>

    {{-- Chat Playground --}}
    <div class="border border-neutral-200 bg-white p-6 shadow-xs">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 flex-none items-center justify-center bg-neutral-900 text-white">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12a8 8 0 0 1-8 8H4l2.5-2.5A8 8 0 1 1 21 12Z"/><path d="M9 11h.01M13 11h.01M17 11h.01"/></svg>
                </span>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.settings.ai_playground') }}</h3>
                    <p class="text-xs text-neutral-400">{{ __('admin.settings.ai_playground_sub') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <select id="ai-pg-model" class="h-10 max-w-[320px] cursor-pointer border border-neutral-300 bg-white px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    @forelse ($providers as $provider)
                        @if ($provider->availableModels())
                            <optgroup label="{{ $provider->name }}@unless($provider->is_active) — (disabled)@endunless">
                                @foreach ($provider->availableModels() as $m)
                                    <option value="{{ $provider->id }}|{{ $m }}" @selected($translatorValue === $provider->id.'|'.$m)>
                                        {{ $provider->name }} — {{ $m }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @empty
                        <option value="">{{ __('admin.settings.ai_empty') }}</option>
                    @endforelse
                </select>
                <button type="button" id="ai-pg-clear" class="action-btn action-btn-danger h-10 px-4">
                    {{ __('admin.settings.ai_playground_clear') }}
                </button>
            </div>
        </div>

        <div class="mt-4">
            <label for="ai-pg-system" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_playground_system') }}</label>
            <input type="text" id="ai-pg-system" placeholder="{{ __('admin.settings.ai_playground_system_ph') }}" maxlength="4000"
                   class="h-10 w-full border border-neutral-300 px-3 text-xs placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
        </div>

        <div id="ai-pg-log" class="mt-4 flex h-[340px] flex-col gap-3 overflow-y-auto border border-neutral-200 bg-neutral-50/70 p-4">
            <p class="ai-pg-empty m-auto text-center text-xs text-neutral-400">{{ __('admin.settings.ai_playground_empty') }}</p>
        </div>

        <div class="mt-4 flex items-end gap-3">
            <textarea id="ai-pg-input" rows="2" placeholder="{{ __('admin.settings.ai_playground_input_ph') }}"
                      class="flex-1 resize-y border border-neutral-300 p-3 text-xs placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none"></textarea>
            <button type="button" id="ai-pg-send" class="action-btn h-[52px] px-6">{{ __('admin.settings.ai_playground_send') }}</button>
        </div>
        <p class="mt-2 text-[11px] text-neutral-400">{{ __('admin.settings.ai_playground_hint') }}</p>
    </div>

    {{-- Providers List --}}
    <div>
        <h3 class="mb-4 text-base font-semibold tracking-tight text-neutral-900">{{ __('admin.settings.ai_heading') }} ({{ $providers->count() }})</h3>

        @if ($providers->isEmpty())
            <div class="border border-neutral-200 bg-white p-12 text-center text-sm text-neutral-400 shadow-xs">
                {{ __('admin.settings.ai_empty') }}
            </div>
        @else
            <div class="space-y-6">
                @foreach ($providers as $provider)
                    <div class="border border-neutral-200 bg-white p-6 shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-neutral-900">
                                    {{ $provider->name }}
                                    <span class="ms-2 inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-neutral-100 text-neutral-700 border border-neutral-200">
                                        {{ $provider->type }}
                                    </span>
                                    @unless ($provider->is_active)
                                        <span class="ms-1 inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200">
                                            {{ __('admin.common.disabled') }}
                                        </span>
                                    @endunless
                                </h3>
                                <p class="mt-1 font-mono text-xs text-neutral-400">{{ $provider->base_url }} · {{ $provider->maskedKey() }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.settings.ai.destroy', $provider) }}" onsubmit="return confirm('{{ __('admin.common.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger h-8 px-3 text-[10px]">{{ __('admin.common.delete') }}</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('admin.settings.ai.update', $provider) }}" id="ai-{{ $provider->id }}" class="mt-4 grid gap-4 lg:grid-cols-2">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.languages.name') }}</label>
                                <input type="text" name="name" required value="{{ $provider->name }}" maxlength="80"
                                       class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_type') }}</label>
                                <select name="type" data-ai-type class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs focus:border-neutral-900 focus:outline-none">
                                    <option value="openai" @selected($provider->type === 'openai')>OpenAI / Compatible</option>
                                    <option value="anthropic" @selected($provider->type === 'anthropic')>Anthropic</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_base_url') }}</label>
                                <input type="url" name="base_url" required value="{{ $provider->base_url }}" data-ai-url maxlength="190"
                                       class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_api_key') }}</label>
                                <input type="text" name="api_key" value="" placeholder="{{ __('admin.settings.ai_key_keep') }}" maxlength="190" autocomplete="off"
                                       class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                            </div>

                            <div class="lg:col-span-2">
                                @php
                                    $fetched = $provider->models ?? [];
                                    $selected = $provider->availableModels();
                                    if ($provider->model && ! in_array($provider->model, $fetched, true)) {
                                        array_unshift($fetched, $provider->model);
                                    }
                                @endphp
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_models') }}</label>
                                <div class="flex gap-3">
                                    <div class="ai-ms" data-ai-ms>
                                        <button type="button" class="ai-ms-btn" data-ai-ms-btn>
                                            <span class="ai-ms-label empty" data-ai-ms-label>{{ __('admin.settings.ai_select_models') }}</span>
                                            <svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                        <div class="ai-ms-panel">
                                            <div class="ai-ms-search">
                                                <input type="text" class="ai-ms-search-input" data-ai-ms-search placeholder="{{ __('admin.settings.ai_search_models') }}">
                                            </div>
                                            <div class="ai-ms-items" id="ai-models-{{ $provider->id }}">
                                                @forelse ($fetched as $m)
                                                    <label class="ai-ms-item">
                                                        <input type="checkbox" name="selected_models[]" value="{{ $m }}" @checked(in_array($m, $selected, true))>
                                                        <span>{{ $m }}</span>
                                                    </label>
                                                @empty
                                                    <p class="ai-ms-empty">{{ __('admin.settings.ai_no_models') }}</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-ai-fetch data-ai-form="ai-{{ $provider->id }}" data-ai-models="ai-models-{{ $provider->id }}" data-ai-provider="{{ $provider->id }}"
                                            class="action-btn flex-none px-4 text-[10px]">{{ __('admin.settings.ai_fetch') }}</button>
                                </div>
                            </div>

                            <div class="hidden lg:col-span-2" data-ai-manual-wrap="ai-{{ $provider->id }}">
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_manual_model') }}</label>
                                <input type="text" name="manual_model" value="" placeholder="{{ __('admin.settings.ai_manual_model_ph') }}" maxlength="190"
                                       class="h-10 w-full border border-neutral-300 px-3 text-xs placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
                                <p class="mt-1 text-[11px] text-neutral-400">{{ __('admin.settings.ai_manual_model_hint') }}</p>
                            </div>

                            <div class="lg:col-span-2 flex items-center justify-between pt-2">
                                <label class="flex cursor-pointer items-center gap-2 text-xs text-neutral-700">
                                    <input type="checkbox" name="is_active" value="1" @checked($provider->is_active) class="h-4 w-4">
                                    {{ __('admin.common.enabled') }}
                                </label>
                                <button type="submit" class="action-btn h-9 px-5">{{ __('admin.common.save') }}</button>
                            </div>
                            <p class="hidden text-xs text-red-600 lg:col-span-2" data-ai-error="ai-{{ $provider->id }}"></p>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Add Provider Modal --}}
    <div id="ai-provider-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-xs" data-ai-modal-backdrop></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white p-6 shadow-xl border border-neutral-200">
                <div class="mb-5 flex items-center justify-between border-b border-neutral-100 pb-3">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.settings.ai_add') }}</h3>
                    <button type="button" data-ai-modal-close class="text-neutral-400 hover:text-neutral-900 text-sm">✕</button>
                </div>
                <form method="POST" action="{{ route('admin.settings.ai.store') }}" id="ai-add" class="grid gap-4 lg:grid-cols-2">
                    @csrf
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.languages.name') }} *</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. OpenAI Production" maxlength="80"
                               class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_type') }} *</label>
                        <select name="type" data-ai-type class="h-10 w-full cursor-pointer border border-neutral-300 bg-white px-3 text-xs focus:border-neutral-900 focus:outline-none">
                            <option value="openai">OpenAI / Compatible</option>
                            <option value="anthropic">Anthropic</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_base_url') }} *</label>
                        <input type="url" name="base_url" required value="{{ old('base_url', 'https://api.openai.com/v1') }}" data-ai-url maxlength="190"
                               class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_api_key') }} *</label>
                        <input type="text" name="api_key" required value="{{ old('api_key') }}" placeholder="sk-..." maxlength="190" autocomplete="off"
                               class="h-10 w-full border border-neutral-300 px-3 text-xs focus:border-neutral-900 focus:outline-none">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_models') }}</label>
                        <div class="flex gap-3">
                            <div class="ai-ms" data-ai-ms>
                                <button type="button" class="ai-ms-btn" data-ai-ms-btn>
                                    <span class="ai-ms-label empty" data-ai-ms-label>{{ __('admin.settings.ai_select_models') }}</span>
                                    <svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <div class="ai-ms-panel">
                                    <div class="ai-ms-search">
                                        <input type="text" class="ai-ms-search-input" data-ai-ms-search placeholder="{{ __('admin.settings.ai_search_models') }}">
                                    </div>
                                    <div class="ai-ms-items" id="ai-add-models">
                                        <p class="ai-ms-empty">{{ __('admin.settings.ai_no_models') }}</p>
                                    </div>
                                </div>
                            </div>
                            <button type="button" data-ai-fetch data-ai-form="ai-add" data-ai-models="ai-add-models"
                                    class="action-btn flex-none px-4 text-[10px]">{{ __('admin.settings.ai_fetch') }}</button>
                        </div>
                    </div>
                    <div class="hidden lg:col-span-2" data-ai-manual-wrap="ai-add">
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ __('admin.settings.ai_manual_model') }}</label>
                        <input type="text" name="manual_model" value="{{ old('manual_model') }}" placeholder="{{ __('admin.settings.ai_manual_model_ph') }}" maxlength="190"
                               class="h-10 w-full border border-neutral-300 px-3 text-xs placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none">
                        <p class="mt-1 text-[11px] text-neutral-400">{{ __('admin.settings.ai_manual_model_hint') }}</p>
                    </div>
                    <p class="hidden text-xs text-red-600 lg:col-span-2" data-ai-error="ai-add"></p>
                    <div class="lg:col-span-2 flex items-center justify-end gap-3 pt-3 border-t border-neutral-100">
                        <button type="button" data-ai-modal-close class="action-btn bg-white text-neutral-700 border-neutral-300 hover:bg-neutral-50 hover:text-neutral-900 h-9 px-4">
                            {{ __('admin.common.cancel') }}
                        </button>
                        <button type="submit" class="action-btn h-9 px-5">{{ __('admin.common.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var fetchUrl      = @json(route('admin.settings.ai.models'));
    var playgroundUrl = @json(route('admin.settings.ai.playground'));
    var token         = @json(csrf_token());
    var defaults      = @json($defaultUrls);
    var fetchingLabel = @json(__('admin.settings.ai_fetching'));
    var fetchLabel    = @json(__('admin.settings.ai_fetch'));

    // Modal show/hide
    var modal = document.getElementById('ai-provider-modal');
    var addBtn = document.getElementById('ai-add-provider-btn');
    function openModal() { if (modal) modal.classList.remove('hidden'); }
    function closeModal() { if (modal) modal.classList.add('hidden'); }
    if (addBtn) addBtn.addEventListener('click', openModal);
    document.querySelectorAll('[data-ai-modal-close], [data-ai-modal-backdrop]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    // Multi-select dropdown label refresh
    function aiMsRefresh(root) {
        var label = root.querySelector('[data-ai-ms-label]');
        var cbs   = root.querySelectorAll('input[type="checkbox"]:checked');
        if (! cbs.length) {
            label.textContent = @json(__('admin.settings.ai_select_models'));
            label.classList.add('empty');
        } else if (cbs.length === 1) {
            label.textContent = cbs[0].value;
            label.classList.remove('empty');
        } else {
            label.textContent = cbs.length + ' ' + @json(__('admin.settings.ai_models'));
            label.classList.remove('empty');
        }
    }

    function aiMsFilter(root) {
        var q = root.querySelector('[data-ai-ms-search]').value.trim().toLowerCase();
        root.querySelectorAll('.ai-ms-item').forEach(function (item) {
            item.style.display = item.textContent.toLowerCase().indexOf(q) !== -1 ? '' : 'none';
        });
    }

    document.querySelectorAll('[data-ai-ms]').forEach(function (root) {
        root.querySelector('[data-ai-ms-btn]').addEventListener('click', function () {
            var isOpen = root.classList.contains('open');
            document.querySelectorAll('[data-ai-ms].open').forEach(function (o) { o.classList.remove('open'); });
            if (! isOpen) {
                root.classList.add('open');
                root.querySelector('[data-ai-ms-search]').focus();
            }
        });
        root.addEventListener('change', function (e) {
            if (e.target.matches('input[type="checkbox"]')) aiMsRefresh(root);
        });
        var search = root.querySelector('[data-ai-ms-search]');
        search.addEventListener('input', function () { aiMsFilter(root); });
        search.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') e.preventDefault();
            if (e.key === 'Escape') root.classList.remove('open');
        });
        aiMsRefresh(root);
    });

    document.addEventListener('click', function (e) {
        if (! e.target.closest('[data-ai-ms]')) {
            document.querySelectorAll('[data-ai-ms].open').forEach(function (o) { o.classList.remove('open'); });
        }
    });

    // Type change -> auto-suggest base URL
    document.querySelectorAll('[data-ai-type]').forEach(function (sel) {
        sel.addEventListener('change', function () {
            var form = sel.closest('form');
            var url  = form ? form.querySelector('[data-ai-url]') : null;
            if (! url) return;
            var def = defaults[sel.value] || '';
            var isDefault = Object.keys(defaults).some(function (k) { return defaults[k] === url.value; });
            if (! url.value || isDefault) url.value = def;
        });
    });

    // Fetch models AJAX
    document.querySelectorAll('[data-ai-fetch]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var form  = document.getElementById(btn.dataset.aiForm);
            var list  = document.getElementById(btn.dataset.aiModels);
            var error = document.querySelector('[data-ai-error="' + btn.dataset.aiForm + '"]');
            var manualWrap = document.querySelector('[data-ai-manual-wrap="' + btn.dataset.aiForm + '"]');
            if (! form || ! list) return;

            var payload = {
                type:     form.querySelector('[name="type"]').value,
                base_url: form.querySelector('[name="base_url"]').value,
                api_key:  form.querySelector('[name="api_key"]').value
            };
            if (btn.dataset.aiProvider) payload.provider_id = btn.dataset.aiProvider;

            btn.disabled = true;
            btn.textContent = fetchingLabel;
            if (error) { error.classList.add('hidden'); error.textContent = ''; }

            fetch(fetchUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function (res) {
                return res.json().then(function (body) {
                    if (! res.ok) throw new Error(body.message || 'Error');
                    return body;
                });
            })
            .then(function (body) {
                var checked = {};
                list.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                    if (cb.checked) checked[cb.value] = true;
                });
                list.innerHTML = '';
                (body.models || []).forEach(function (m) {
                    var label = document.createElement('label');
                    label.className = 'ai-ms-item';
                    var cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.name = 'selected_models[]';
                    cb.value = m;
                    cb.checked = !! checked[m];
                    var span = document.createElement('span');
                    span.textContent = m;
                    label.appendChild(cb);
                    label.appendChild(span);
                    list.appendChild(label);
                });
                if (! (body.models || []).length) {
                    list.innerHTML = '<p class="ai-ms-empty">' + @json(__('admin.settings.ai_no_models')) + '</p>';
                }
                var root = list.closest('[data-ai-ms]');
                if (root) aiMsRefresh(root);
                if (manualWrap) manualWrap.classList.add('hidden');
            })
            .catch(function (err) {
                if (error) {
                    error.textContent = err.message || @json(__('admin.settings.ai_fetch_failed'));
                    error.classList.remove('hidden');
                }
                if (manualWrap) manualWrap.classList.remove('hidden');
            })
            .finally(function () {
                btn.disabled = false;
                btn.textContent = fetchLabel;
            });
        });
    });

    // Chat Playground
    var pgModel   = document.getElementById('ai-pg-model');
    var pgSystem  = document.getElementById('ai-pg-system');
    var pgInput   = document.getElementById('ai-pg-input');
    var pgSend    = document.getElementById('ai-pg-send');
    var pgLog     = document.getElementById('ai-pg-log');
    var pgClear   = document.getElementById('ai-pg-clear');
    var messages  = [];
    var thinkingEl= null;

    function renderLog() {
        if (! pgLog) return;
        if (! messages.length && ! thinkingEl) {
            pgLog.innerHTML = '<p class="ai-pg-empty m-auto text-center text-xs text-neutral-400">' + @json(__('admin.settings.ai_playground_empty')) + '</p>';
            return;
        }
        var empty = pgLog.querySelector('.ai-pg-empty');
        if (empty) empty.remove();
    }

    function appendMsg(role, text, meta) {
        renderLog();
        var wrap = document.createElement('div');
        wrap.className = 'ai-pg-msg ' + role;
        var who = document.createElement('p');
        who.className = 'ai-pg-who';
        who.textContent = role === 'user' ? @json(__('admin.settings.ai_playground_you')) : (role === 'ai' ? 'Assistant' : @json(__('admin.settings.ai_playground_error')));
        var body = document.createElement('div');
        body.className = 'ai-pg-body';
        body.textContent = text;
        wrap.appendChild(who);
        wrap.appendChild(body);
        if (meta) {
            var m = document.createElement('p');
            m.className = 'ai-pg-meta';
            m.textContent = meta;
            wrap.appendChild(m);
        }
        pgLog.appendChild(wrap);
        pgLog.scrollTop = pgLog.scrollHeight;
    }

    function sendPlayground() {
        var text = (pgInput.value || '').trim();
        var modelSel = (pgModel.value || '').trim();
        if (! text) return;
        if (! modelSel) {
            alert(@json(__('admin.settings.ai_playground_pick')));
            return;
        }

        var parts = modelSel.split('|');
        var providerId = parts[0];
        var model = parts.slice(1).join('|');

        messages.push({ role: 'user', content: text });
        appendMsg('user', text);
        pgInput.value = '';

        // Show thinking
        thinkingEl = document.createElement('div');
        thinkingEl.className = 'ai-pg-msg ai ai-pg-thinking';
        thinkingEl.innerHTML = '<p class="ai-pg-who">Assistant</p><div class="ai-pg-body">' + @json(__('admin.settings.ai_playground_thinking')) + '</div>';
        pgLog.appendChild(thinkingEl);
        pgLog.scrollTop = pgLog.scrollHeight;

        pgSend.disabled = true;

        fetch(playgroundUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({
                provider_id: providerId,
                model: model,
                system: pgSystem ? pgSystem.value.trim() : '',
                messages: messages
            })
        })
        .then(function (res) {
            return res.json().then(function (body) {
                if (! res.ok) throw new Error(body.error || body.message || 'Failed');
                return body;
            });
        })
        .then(function (body) {
            if (thinkingEl) thinkingEl.remove();
            thinkingEl = null;
            if (body.ok) {
                messages.push({ role: 'assistant', content: body.content });
                var meta = (body.duration_ms ? (body.duration_ms / 1000).toFixed(2) + 's' : '') +
                           (body.input_tokens && body.output_tokens ? ' · ' + body.input_tokens + ' in / ' + body.output_tokens + ' out' : '');
                appendMsg('ai', body.content, meta);
            } else {
                appendMsg('err', body.error || 'Provider returned error');
            }
        })
        .catch(function (err) {
            if (thinkingEl) thinkingEl.remove();
            thinkingEl = null;
            appendMsg('err', err.message || 'Network / server error');
        })
        .finally(function () {
            pgSend.disabled = false;
        });
    }

    if (pgSend) pgSend.addEventListener('click', sendPlayground);
    if (pgInput) {
        pgInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && ! e.shiftKey) {
                e.preventDefault();
                sendPlayground();
            }
        });
    }
    if (pgClear) {
        pgClear.addEventListener('click', function () {
            messages = [];
            if (thinkingEl) { thinkingEl.remove(); thinkingEl = null; }
            pgLog.innerHTML = '<p class="ai-pg-empty m-auto text-center text-xs text-neutral-400">' + @json(__('admin.settings.ai_playground_empty')) + '</p>';
        });
    }
})();
</script>

</x-layouts.dashboard>
