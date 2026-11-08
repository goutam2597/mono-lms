<x-layouts.dashboard title="Events & Live Classes - Mono LMS" active="events" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Events & Live Webinars</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $events->count() }} Scheduled
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Schedule interactive live sessions, Zoom webinars, and cohort workshops.</p>
            </div>

            <button type="button" onclick="document.getElementById('scheduleEventModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#283a2f] text-white transition-colors shadow-xs shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Schedule Event</span>
            </button>
        </div>

        <!-- Events List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($events as $event)
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4 hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100">
                                Live Session
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
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
                            <span>Hosted by: <strong class="text-slate-800">{{ $event->instructor?->name ?? 'Staff Team' }}</strong></span>
                        </div>

                        @if($event->meeting_link)
                            <div class="mt-3 p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-mono text-[11px] text-[#344c3d] truncate max-w-[200px]">{{ $event->meeting_link }}</span>
                                <a href="{{ $event->meeting_link }}" target="_blank" class="text-xs font-semibold text-emerald-600 hover:underline shrink-0">Open Zoom</a>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400">Capacity: {{ $event->capacity }} attendees</span>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Cancel this event?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-12 text-center text-slate-400 text-xs bg-white rounded-2xl border border-slate-200">
                    No upcoming live events scheduled.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Schedule Event Modal -->
    <div id="scheduleEventModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-5 animate-in fade-in zoom-in duration-150">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Schedule Live Session</h3>
                <button type="button" onclick="document.getElementById('scheduleEventModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Session Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Live Q&A: Reactive PHP Architecture" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Host Instructor</label>
                    <select name="instructor_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white focus:outline-none focus:border-[#344c3d]">
                        <option value="">Mono LMS Staff Team</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Start Date & Time *</label>
                        <input type="datetime-local" name="start_time" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Duration (Mins) *</label>
                        <input type="number" name="duration_minutes" value="60" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Meeting Link (Zoom / Google Meet)</label>
                    <input type="url" name="meeting_link" placeholder="https://zoom.us/j/..." class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Max Attendee Capacity</label>
                    <input type="number" name="capacity" value="100" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('scheduleEventModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs">
                        Publish Event
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.dashboard>
