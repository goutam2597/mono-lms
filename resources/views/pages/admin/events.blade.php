<x-layouts.dashboard title="Events & Live Classes - Mono LMS" active="events" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Events & Live Webinars</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $events->count() }} Scheduled
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Schedule interactive live sessions, Zoom webinars, and cohort workshops.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" id="add-event-btn" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider shrink-0 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Schedule Event</span>
                </button>
            </div>
        </div>

        <!-- Bulk Toolbar for Events -->
        <x-bulk-toolbar action="{{ route('admin.events.bulk-destroy') }}" />

        <!-- Select all banner if items exist -->
        @if ($events->isNotEmpty())
            <div class="flex items-center gap-2 px-1">
                <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" id="check-all" class="control">
                    <span>Select All Events</span>
                </label>
            </div>
        @endif

        <!-- Events List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($events as $event)
                <div class="bg-white border border-slate-200 rounded-none p-6 shadow-xs space-y-4 hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $event->id }}" form="bulk-form" aria-label="{{ $event->title }}">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none bg-blue-50 text-blue-700 border border-blue-100">
                                    Live Session
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">
                                {{ $event->duration_minutes }} mins
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 leading-snug">{{ $event->title }}</h3>

                        <div class="flex items-center gap-2.5 mt-3 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $event->start_time->format('l, M d, Y - h:i A') }}</span>
                        </div>

                        <div class="flex items-center gap-2.5 mt-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Hosted by: <strong class="text-slate-800">{{ $event->instructor?->name ?? 'Mono LMS Staff' }}</strong></span>
                        </div>

                        @if($event->meeting_link)
                            <div class="mt-3 p-2.5 bg-slate-50 rounded-none border border-slate-200 flex items-center justify-between text-xs">
                                <span class="font-mono text-[11px] text-[#344c3d] truncate max-w-[200px]">{{ $event->meeting_link }}</span>
                                <a href="{{ $event->meeting_link }}" target="_blank" class="text-xs font-semibold uppercase tracking-wider text-emerald-600 hover:underline shrink-0">Open Link</a>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400">Capacity: {{ $event->capacity }} attendees</span>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    class="edit-event-btn action-btn action-btn-ghost h-7 px-3 text-[10px]"
                                    data-action="{{ route('admin.events.update', $event) }}"
                                    data-event="{{ json_encode([
                                        'id' => $event->id,
                                        'title' => $event->title,
                                        'instructor_id' => $event->instructor_id,
                                        'start_time' => $event->start_time->format('Y-m-d\TH:i'),
                                        'duration_minutes' => $event->duration_minutes,
                                        'meeting_link' => $event->meeting_link,
                                        'capacity' => $event->capacity,
                                    ]) }}">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Cancel this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">
                                    Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-12 text-center text-slate-400 text-xs bg-white rounded-none border border-slate-200">
                    No upcoming live events scheduled.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Event Add / Edit Modal (mono_market pattern) -->
    <div id="event-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900" id="event-modal-heading">Schedule Live Session</h3>
                <button type="button" id="event-modal-close" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="event-form" action="{{ route('admin.events.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="event-form-method" value="POST" disabled>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Session Title *</label>
                    <input type="text" name="title" id="event-title" required placeholder="e.g. Live Q&A: Reactive PHP Architecture" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Host Instructor</label>
                    <select name="instructor_id" id="event-instructor" data-mselect class="w-full px-3 py-2.5 border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="">Mono LMS Staff Team</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Start Date & Time *</label>
                        <input type="datetime-local" name="start_time" id="event-start-time" required class="w-full px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Duration (Mins) *</label>
                        <input type="number" name="duration_minutes" id="event-duration" value="60" required class="w-full px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Meeting Link (Zoom / Google Meet)</label>
                    <input type="url" name="meeting_link" id="event-meeting-link" placeholder="https://zoom.us/j/..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Max Attendee Capacity</label>
                    <input type="number" name="capacity" id="event-capacity" value="100" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" id="event-modal-cancel" class="action-btn action-btn-ghost h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal       = document.getElementById('event-modal');
        var form        = document.getElementById('event-form');
        var heading     = document.getElementById('event-modal-heading');
        var method      = document.getElementById('event-form-method');
        var addBtn      = document.getElementById('add-event-btn');
        var closeBtn    = document.getElementById('event-modal-close');
        var cancelBtn   = document.getElementById('event-modal-cancel');
        var titleIn     = document.getElementById('event-title');
        var instIn      = document.getElementById('event-instructor');
        var startIn     = document.getElementById('event-start-time');
        var durationIn  = document.getElementById('event-duration');
        var meetingIn   = document.getElementById('event-meeting-link');
        var capacityIn  = document.getElementById('event-capacity');
        var storeUrl    = @json(route('admin.events.store'));

        function openAdd() {
            heading.textContent = 'Schedule Live Session';
            form.action = storeUrl;
            method.disabled = true;
            titleIn.value = '';
            instIn.value = '';
            startIn.value = '';
            durationIn.value = '60';
            meetingIn.value = '';
            capacityIn.value = '100';
            modal.classList.add('open');
            setTimeout(function () { titleIn.focus(); }, 100);
        }

        function openEdit(data, action) {
            heading.textContent = 'Edit Live Session';
            form.action = action;
            method.disabled = false;
            method.value = 'PUT';
            titleIn.value = data.title || '';
            instIn.value = data.instructor_id || '';
            startIn.value = data.start_time || '';
            durationIn.value = data.duration_minutes || 60;
            meetingIn.value = data.meeting_link || '';
            capacityIn.value = data.capacity || 100;
            modal.classList.add('open');
            setTimeout(function () { titleIn.focus(); }, 100);
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        if (addBtn) addBtn.addEventListener('click', openAdd);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        document.querySelectorAll('.edit-event-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = JSON.parse(btn.dataset.event || '{}');
                openEdit(data, btn.dataset.action);
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
    });
    </script>

</x-layouts.dashboard>
