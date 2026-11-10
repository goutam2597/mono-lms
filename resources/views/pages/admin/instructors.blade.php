<x-layouts.dashboard :title="__('admin.instructors.title').' - Mono LMS'" :active="request('verification') === 'pending' ? 'instructor_verifications' : 'instructors'" userType="admin">

    <style>
        #instructor-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box; }
        #instructor-modal.open { display: flex !important; }
        #instructor-modal .panel { background: #ffffff; width: 560px; max-width: 100%; max-height: calc(100vh - 48px); overflow-y: auto; padding: 24px; border-radius: 0 !important; border: 1px solid #e5e5e5; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
    </style>

    <div class="bg-white border border-[#e7e7e7] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.instructors.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.instructors.subtitle') }}</p>
            </div>
            <button type="button" id="add-instructor-btn" class="action-btn action-btn-primary h-10 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">+ {{ __('admin.instructors.create') }}</button>
        </div>

        <!-- Verification & Status Filter Tabs -->
        <div class="px-6 py-2.5 bg-slate-50/70 border-b border-gray-100 flex flex-wrap items-center gap-2 text-xs">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mr-1">Filter:</span>
            <a href="{{ route('admin.instructors.index') }}"
               class="px-3 py-1.5 rounded-[4px] font-medium transition-colors flex items-center gap-1.5 {{ ($activeFilter ?? 'all') === 'all' ? 'bg-[#08131e] text-white shadow-2xs font-semibold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                <span>All Instructors</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($activeFilter ?? 'all') === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.instructors.index', ['verification' => 'pending']) }}"
               class="px-3 py-1.5 rounded-[4px] font-medium transition-colors flex items-center gap-1.5 {{ ($activeFilter ?? '') === 'pending' ? 'bg-amber-600 text-white shadow-2xs font-semibold' : 'bg-white border border-amber-200 text-amber-700 hover:bg-amber-50/60' }}">
                <span>Pending Verification</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($activeFilter ?? '') === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">{{ $counts['pending'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.instructors.index', ['verification' => 'approved']) }}"
               class="px-3 py-1.5 rounded-[4px] font-medium transition-colors flex items-center gap-1.5 {{ ($activeFilter ?? '') === 'approved' ? 'bg-emerald-700 text-white shadow-2xs font-semibold' : 'bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50/60' }}">
                <span>Verified</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($activeFilter ?? '') === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $counts['approved'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.instructors.index', ['verification' => 'unverified']) }}"
               class="px-3 py-1.5 rounded-[4px] font-medium transition-colors flex items-center gap-1.5 {{ ($activeFilter ?? '') === 'unverified' ? 'bg-slate-700 text-white shadow-2xs font-semibold' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                <span>Unverified</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($activeFilter ?? '') === 'unverified' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $counts['unverified'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.instructors.index', ['verification' => 'rejected']) }}"
               class="px-3 py-1.5 rounded-[4px] font-medium transition-colors flex items-center gap-1.5 {{ ($activeFilter ?? '') === 'rejected' ? 'bg-red-700 text-white shadow-2xs font-semibold' : 'bg-white border border-red-200 text-red-700 hover:bg-red-50/60' }}">
                <span>Rejected</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($activeFilter ?? '') === 'rejected' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-700' }}">{{ $counts['rejected'] ?? 0 }}</span>
            </a>
        </div>

        <x-bulk-toolbar action="{{ route('admin.instructors.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="w-10 px-6 py-3">
                            <input type="checkbox" id="check-all" class="control" aria-label="{{ __('admin.common.select_all') ?? 'Select All' }}">
                        </th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.instructors.designation') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.instructors.courses_count') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold">Verification</th>
                        <th class="px-6 py-3 text-right font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($instructors as $instructor)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="w-10 px-6 py-3.5">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $instructor->id }}" form="bulk-form" aria-label="{{ $instructor->name }}">
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($instructor->avatarUrl())
                                        <img src="{{ $instructor->avatarUrl() }}" alt="{{ $instructor->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <span class="w-9 h-9 rounded-full bg-[#738a6e]/10 text-[#738a6e] font-bold text-sm items-center justify-center hidden">{{ strtoupper(substr($instructor->name, 0, 1)) }}</span>
                                    @else
                                        <span class="w-9 h-9 rounded-full bg-[#738a6e]/10 text-[#738a6e] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($instructor->name, 0, 1)) }}</span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-[#191919]">{{ $instructor->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $instructor->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $instructor->designation ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $instructor->courses_count }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] {{ $instructor->status === 'active' ? 'bg-[#03c95a]/10 text-[#0d7a38] border border-[#03c95a]/20' : ($instructor->status === 'pending_verification' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-gray-100 text-gray-500 border border-gray-200') }}">
                                    {{ $instructor->status === 'active' ? __('app.common.active') : ($instructor->status === 'pending_verification' ? 'Pending' : __('app.common.inactive')) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($instructor->verification_status === 'approved')
                                    <span class="inline-flex items-center gap-1 h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Verified
                                    </span>
                                @elseif ($instructor->verification_status === 'pending')
                                    <button type="button" class="review-verification-btn inline-flex items-center gap-1.5 h-6 px-2.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-amber-500 hover:bg-amber-600 text-white rounded-xs cursor-pointer shadow-2xs"
                                            data-instructor="{{ json_encode([
                                                'id' => $instructor->id,
                                                'name' => $instructor->name,
                                                'email' => $instructor->email,
                                                'designation' => $instructor->designation,
                                                'certification_file' => $instructor->certification_file,
                                                'certification_url' => $instructor->certificationUrl(),
                                                'national_id_file' => $instructor->national_id_file,
                                                'national_id_url' => $instructor->nationalIdUrl(),
                                                'verification_status' => $instructor->verification_status,
                                                'rejection_reason' => $instructor->rejection_reason,
                                                'approve_url' => route('admin.instructors.approve-verification', $instructor),
                                                'reject_url' => route('admin.instructors.reject-verification', $instructor),
                                            ]) }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Review
                                    </button>
                                @elseif ($instructor->verification_status === 'rejected')
                                    <button type="button" class="review-verification-btn inline-flex items-center gap-1 h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 cursor-pointer"
                                            title="Click to review: {{ $instructor->rejection_reason }}"
                                            data-instructor="{{ json_encode([
                                                'id' => $instructor->id,
                                                'name' => $instructor->name,
                                                'email' => $instructor->email,
                                                'designation' => $instructor->designation,
                                                'certification_file' => $instructor->certification_file,
                                                'certification_url' => $instructor->certificationUrl(),
                                                'national_id_file' => $instructor->national_id_file,
                                                'national_id_url' => $instructor->nationalIdUrl(),
                                                'verification_status' => $instructor->verification_status,
                                                'rejection_reason' => $instructor->rejection_reason,
                                                'approve_url' => route('admin.instructors.approve-verification', $instructor),
                                                'reject_url' => route('admin.instructors.reject-verification', $instructor),
                                            ]) }}">
                                        Rejected
                                    </button>
                                @else
                                    <button type="button" class="review-verification-btn inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] bg-gray-100 hover:bg-gray-200 text-gray-500 border border-gray-200 cursor-pointer"
                                            data-instructor="{{ json_encode([
                                                'id' => $instructor->id,
                                                'name' => $instructor->name,
                                                'email' => $instructor->email,
                                                'designation' => $instructor->designation,
                                                'certification_file' => $instructor->certification_file,
                                                'certification_url' => $instructor->certificationUrl(),
                                                'national_id_file' => $instructor->national_id_file,
                                                'national_id_url' => $instructor->nationalIdUrl(),
                                                'verification_status' => $instructor->verification_status,
                                                'rejection_reason' => $instructor->rejection_reason,
                                                'approve_url' => route('admin.instructors.approve-verification', $instructor),
                                                'reject_url' => route('admin.instructors.reject-verification', $instructor),
                                            ]) }}">
                                        Unverified
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($instructor->certification_file || $instructor->national_id_file)
                                        <button type="button"
                                                class="review-verification-btn action-btn action-btn-secondary h-7 px-3 text-[10px]"
                                                data-instructor="{{ json_encode([
                                                    'id' => $instructor->id,
                                                    'name' => $instructor->name,
                                                    'email' => $instructor->email,
                                                    'designation' => $instructor->designation,
                                                    'certification_file' => $instructor->certification_file,
                                                    'certification_url' => $instructor->certificationUrl(),
                                                    'national_id_file' => $instructor->national_id_file,
                                                    'national_id_url' => $instructor->nationalIdUrl(),
                                                    'verification_status' => $instructor->verification_status,
                                                    'rejection_reason' => $instructor->rejection_reason,
                                                    'approve_url' => route('admin.instructors.approve-verification', $instructor),
                                                    'reject_url' => route('admin.instructors.reject-verification', $instructor),
                                                ]) }}">
                                            Docs
                                        </button>
                                    @endif
                                    <button type="button"
                                            class="edit-instructor-btn action-btn action-btn-ghost h-7 px-3 text-[10px]"
                                            data-action="{{ route('admin.instructors.update', $instructor) }}"
                                            data-instructor="{{ json_encode([
                                                'id' => $instructor->id,
                                                'name' => $instructor->name,
                                                'email' => $instructor->email,
                                                'phone' => $instructor->phone,
                                                'designation' => $instructor->designation,
                                                'bio' => $instructor->bio,
                                                'avatar' => $instructor->avatar,
                                                'avatar_url' => $instructor->avatarUrl(),
                                                'status' => $instructor->status,
                                            ]) }}">
                                        {{ __('app.common.edit') }}
                                    </button>
                                    <form method="POST" action="{{ route('admin.instructors.destroy', $instructor) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $instructors->links() }}</div>
    </div>

    <!-- Instructor Add / Edit Modal (mono_market pattern) -->
    <div id="instructor-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                <h3 class="text-base font-bold text-neutral-900" id="instructor-modal-heading">{{ __('admin.instructors.create') }}</h3>
                <button type="button" id="instructor-modal-close" class="text-neutral-400 hover:text-neutral-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="instructor-form" action="{{ route('admin.instructors.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="instructor-form-method" value="POST" disabled>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} *</label>
                        <input type="text" name="name" id="instructor-name" required maxlength="255" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.email') }} *</label>
                        <input type="email" name="email" id="instructor-email" required maxlength="255" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">
                            {{ __('app.common.password') }}
                            <span id="instructor-password-hint" class="text-neutral-400 font-normal">({{ __('instructor.profile.new_password_hint') }})</span>
                        </label>
                        <input type="password" name="password" id="instructor-password" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.phone') }}</label>
                        <input type="text" name="phone" id="instructor-phone" maxlength="30" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.instructors.designation') }}</label>
                        <input type="text" name="designation" id="instructor-designation" placeholder="e.g. Senior PHP Architect" maxlength="100" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.status') }}</label>
                        <select name="status" id="instructor-status" data-mselect data-mselect-no-search class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900 bg-white">
                            <option value="active">{{ __('app.common.active') }}</option>
                            <option value="inactive">{{ __('app.common.inactive') }}</option>
                            <option value="pending_verification">Pending Verification</option>
                        </select>
                    </div>
                </div>

                <!-- Avatar Media Selector -->
                <div id="instructor-media-wrapper">
                    <x-media-selector
                        name="avatar"
                        label="Avatar Image"
                        shape="circle"
                        hint="Picked from media library. Leave blank to generate initials avatar."
                    />
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.instructors.bio') }}</label>
                    <textarea name="bio" id="instructor-bio" rows="3" class="w-full border border-neutral-300 p-4 text-sm focus:outline-none focus:border-neutral-900"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-200">
                    <button type="button" id="instructor-modal-cancel" class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.cancel') }}</button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructor Verification Review Modal -->
    <div id="verification-modal" class="modal-overlay" role="dialog" aria-modal="true" style="position: fixed; inset: 0; z-index: 130; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box;">
        <div class="bg-white w-[600px] max-w-full max-h-[calc(100vh-48px)] overflow-y-auto p-6 border border-neutral-200 shadow-xl space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                <div>
                    <h3 class="text-base font-bold text-neutral-900">Instructor Verification Review</h3>
                    <p class="text-xs text-neutral-500 mt-0.5" id="verify-modal-subheading">Review uploaded certification and national ID documents</p>
                </div>
                <button type="button" id="verification-modal-close" class="text-neutral-400 hover:text-neutral-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <!-- Instructor summary -->
            <div class="p-3.5 bg-neutral-50 border border-neutral-200 text-xs space-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-neutral-900 text-sm" id="verify-instructor-name">—</span>
                    <span id="verify-status-badge" class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border"></span>
                </div>
                <p class="text-neutral-500" id="verify-instructor-email">—</p>
                <p class="text-neutral-600 font-medium" id="verify-instructor-desig">—</p>
            </div>

            <!-- Documents Preview / Links -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <!-- Instructor Certificate -->
                <div class="p-4 border border-neutral-200 rounded-none bg-white space-y-2">
                    <div class="flex items-center gap-2 font-bold text-neutral-900">
                        <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Instructor Certification</span>
                    </div>
                    <div id="verify-cert-content" class="pt-1">
                        <a id="verify-cert-link" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-neutral-900 text-white font-semibold text-[11px] rounded-xs hover:bg-neutral-800 transition-colors">
                            <span>Open Certificate</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <p id="verify-cert-empty" class="text-neutral-400 italic hidden">No certificate uploaded</p>
                    </div>
                </div>

                <!-- National ID / Passport -->
                <div class="p-4 border border-neutral-200 rounded-none bg-white space-y-2">
                    <div class="flex items-center gap-2 font-bold text-neutral-900">
                        <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        <span>National ID / Passport</span>
                    </div>
                    <div id="verify-id-content" class="pt-1">
                        <a id="verify-id-link" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-neutral-900 text-white font-semibold text-[11px] rounded-xs hover:bg-neutral-800 transition-colors">
                            <span>Open National ID</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <p id="verify-id-empty" class="text-neutral-400 italic hidden">No national ID uploaded</p>
                    </div>
                </div>
            </div>

            <!-- Previous rejection reason if any -->
            <div id="verify-rejection-info" class="p-3 bg-red-50 border border-red-200 text-xs text-red-800 rounded-none hidden">
                <span class="font-bold">Prior Rejection Note:</span>
                <span id="verify-rejection-text"></span>
            </div>

            <!-- Action Controls -->
            <div class="pt-4 border-t border-neutral-200 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <form id="verify-approve-form" method="POST" action="">
                        @csrf
                        <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-bold uppercase tracking-[0.14em] bg-emerald-600 hover:bg-emerald-700 text-white border-0 flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Approve &amp; Activate</span>
                        </button>
                    </form>

                    <button type="button" id="toggle-reject-btn" class="action-btn action-btn-danger h-9 px-4 text-[11px] font-bold uppercase tracking-[0.14em] cursor-pointer">
                        Reject Verification
                    </button>
                </div>

                <!-- Rejection Form (toggled) -->
                <form id="verify-reject-form" method="POST" action="" class="p-3.5 bg-neutral-50 border border-red-200 space-y-2 hidden">
                    @csrf
                    <label class="block text-[11px] font-bold uppercase text-red-800">Reason for Rejection *</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="2" required placeholder="Explain why the documents cannot be verified (e.g. illegible copy, expired ID, name mismatch)..." class="w-full border border-neutral-300 p-2.5 text-xs focus:outline-none focus:border-red-600 bg-white"></textarea>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" id="cancel-reject-btn" class="action-btn action-btn-ghost h-8 px-3 text-[10px]">Cancel</button>
                        <button type="submit" class="action-btn action-btn-danger h-8 px-4 text-[10px] font-bold uppercase">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal       = document.getElementById('instructor-modal');
        var form        = document.getElementById('instructor-form');
        var heading     = document.getElementById('instructor-modal-heading');
        var method      = document.getElementById('instructor-form-method');
        var addBtn      = document.getElementById('add-instructor-btn');
        var closeBtn    = document.getElementById('instructor-modal-close');
        var cancelBtn   = document.getElementById('instructor-modal-cancel');
        var nameIn      = document.getElementById('instructor-name');
        var emailIn     = document.getElementById('instructor-email');
        var passIn      = document.getElementById('instructor-password');
        var passHint    = document.getElementById('instructor-password-hint');
        var phoneIn     = document.getElementById('instructor-phone');
        var desigIn     = document.getElementById('instructor-designation');
        var statusIn    = document.getElementById('instructor-status');
        var bioIn       = document.getElementById('instructor-bio');
        var mediaWrap   = document.getElementById('instructor-media-wrapper');
        var storeUrl    = @json(route('admin.instructors.store'));

        function setMediaValue(path, url) {
            if (!mediaWrap) return;
            var input       = mediaWrap.querySelector('[data-media-input]');
            var img         = mediaWrap.querySelector('[data-media-img]');
            var placeholder = mediaWrap.querySelector('[data-media-placeholder]');
            var removeBtn   = mediaWrap.querySelector('[data-media-remove]');

            if (input) input.value = path || '';
            if (url) {
                if (img) { img.src = url; img.classList.remove('hidden'); }
                if (placeholder) placeholder.classList.add('hidden');
                if (removeBtn) removeBtn.classList.remove('hidden');
            } else {
                if (img) { img.src = ''; img.classList.add('hidden'); }
                if (placeholder) placeholder.classList.remove('hidden');
                if (removeBtn) removeBtn.classList.add('hidden');
            }
        }

        function openAdd() {
            heading.textContent = @json(__('admin.instructors.create'));
            form.action = storeUrl;
            method.disabled = true;
            nameIn.value = '';
            emailIn.value = '';
            passIn.value = '';
            passIn.required = true;
            passHint.textContent = '*';
            phoneIn.value = '';
            desigIn.value = '';
            statusIn.value = 'active';
            bioIn.value = '';
            setMediaValue('', '');
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function openEdit(data, action) {
            heading.textContent = @json(__('admin.instructors.edit'));
            form.action = action;
            method.disabled = false;
            method.value = 'PUT';
            nameIn.value = data.name || '';
            emailIn.value = data.email || '';
            passIn.value = '';
            passIn.required = false;
            passHint.textContent = '(' + @json(__('instructor.profile.new_password_hint')) + ')';
            phoneIn.value = data.phone || '';
            desigIn.value = data.designation || '';
            statusIn.value = data.status || 'active';
            bioIn.value = data.bio || '';
            setMediaValue(data.avatar || '', data.avatar_url || '');
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        if (addBtn) addBtn.addEventListener('click', openAdd);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        document.querySelectorAll('.edit-instructor-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = JSON.parse(btn.dataset.instructor || '{}');
                openEdit(data, btn.dataset.action);
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        // Verification Modal logic
        var vModal = document.getElementById('verification-modal');
        var vClose = document.getElementById('verification-modal-close');
        var vName = document.getElementById('verify-instructor-name');
        var vEmail = document.getElementById('verify-instructor-email');
        var vDesig = document.getElementById('verify-instructor-desig');
        var vBadge = document.getElementById('verify-status-badge');
        var vCertLink = document.getElementById('verify-cert-link');
        var vCertEmpty = document.getElementById('verify-cert-empty');
        var vIdLink = document.getElementById('verify-id-link');
        var vIdEmpty = document.getElementById('verify-id-empty');
        var vRejectionInfo = document.getElementById('verify-rejection-info');
        var vRejectionText = document.getElementById('verify-rejection-text');
        var vApproveForm = document.getElementById('verify-approve-form');
        var vRejectForm = document.getElementById('verify-reject-form');
        var toggleRejectBtn = document.getElementById('toggle-reject-btn');
        var cancelRejectBtn = document.getElementById('cancel-reject-btn');

        function openVerifyModal(data) {
            vName.textContent = data.name || '—';
            vEmail.textContent = data.email || '—';
            vDesig.textContent = data.designation || 'Instructor';

            var st = data.verification_status || 'unverified';
            vBadge.textContent = st;
            if (st === 'approved') {
                vBadge.className = 'px-2 py-0.5 text-[10px] font-bold uppercase rounded border bg-emerald-50 text-emerald-700 border-emerald-200';
            } else if (st === 'pending') {
                vBadge.className = 'px-2 py-0.5 text-[10px] font-bold uppercase rounded border bg-amber-50 text-amber-700 border-amber-200';
            } else if (st === 'rejected') {
                vBadge.className = 'px-2 py-0.5 text-[10px] font-bold uppercase rounded border bg-red-50 text-red-700 border-red-200';
            } else {
                vBadge.className = 'px-2 py-0.5 text-[10px] font-bold uppercase rounded border bg-gray-100 text-gray-600 border-gray-200';
            }

            if (data.certification_url) {
                vCertLink.href = data.certification_url;
                vCertLink.classList.remove('hidden');
                vCertEmpty.classList.add('hidden');
            } else {
                vCertLink.classList.add('hidden');
                vCertEmpty.classList.remove('hidden');
            }

            if (data.national_id_url) {
                vIdLink.href = data.national_id_url;
                vIdLink.classList.remove('hidden');
                vIdEmpty.classList.add('hidden');
            } else {
                vIdLink.classList.add('hidden');
                vIdEmpty.classList.remove('hidden');
            }

            if (data.rejection_reason) {
                vRejectionText.textContent = ' ' + data.rejection_reason;
                vRejectionInfo.classList.remove('hidden');
            } else {
                vRejectionInfo.classList.add('hidden');
            }

            vApproveForm.action = data.approve_url || '#';
            vRejectForm.action = data.reject_url || '#';
            vRejectForm.classList.add('hidden');

            vModal.style.display = 'flex';
        }

        function closeVerifyModal() {
            vModal.style.display = 'none';
        }

        document.querySelectorAll('.review-verification-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = JSON.parse(btn.dataset.instructor || '{}');
                openVerifyModal(data);
            });
        });

        if (vClose) vClose.addEventListener('click', closeVerifyModal);
        if (toggleRejectBtn) {
            toggleRejectBtn.addEventListener('click', function () {
                vRejectForm.classList.toggle('hidden');
                if (!vRejectForm.classList.contains('hidden')) {
                    document.getElementById('rejection_reason').focus();
                }
            });
        }
        if (cancelRejectBtn) {
            cancelRejectBtn.addEventListener('click', function () {
                vRejectForm.classList.add('hidden');
            });
        }

        vModal.addEventListener('click', function (e) {
            if (e.target === vModal) closeVerifyModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (modal.classList.contains('open')) closeModal();
                if (vModal.style.display === 'flex') closeVerifyModal();
            }
        });
    });
    </script>

</x-layouts.dashboard>
