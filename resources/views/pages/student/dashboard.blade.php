<x-layouts.dashboard :title="__('student.dashboard.title').' - Mono LMS'" active="dashboard" userType="student">

    <div class="space-y-6">
        <!-- 4 Key Stat Cards (Boxy Mono-Market Style) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
            <!-- Enrolled -->
            <div class="bg-white border border-[#e2e8f0] rounded-[8px] p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">{{ __('student.dashboard.enrolled') }}</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ str_pad((string) $stats['enrolled'], 2, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Active catalog</p>
                </div>
                <div class="w-11 h-11 rounded-[6px] bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] flex items-center justify-center shrink-0 border border-[var(--theme-primary,#344c3d)]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white border border-[#e2e8f0] rounded-[8px] p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">{{ __('student.dashboard.in_progress') }}</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-indigo-600 mt-1">{{ str_pad((string) $stats['in_progress'], 2, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Underway</p>
                </div>
                <div class="w-11 h-11 rounded-[6px] bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white border border-[#e2e8f0] rounded-[8px] p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">{{ __('student.dashboard.completed') }}</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1">{{ str_pad((string) $stats['completed'], 2, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Finished 100%</p>
                </div>
                <div class="w-11 h-11 rounded-[6px] bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Certificates -->
            <div class="bg-white border border-[#e2e8f0] rounded-[8px] p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">{{ __('student.dashboard.certificates') }}</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-1">{{ str_pad((string) $stats['certificates'], 2, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Accredited</p>
                </div>
                <div class="w-11 h-11 rounded-[6px] bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a6 6 0 100-12 6 6 0 000 12zm-3-6l2 2 4-4m2.5 7.5L17 22l-5-3-5 3 1.5-8.5"/></svg>
                </div>
            </div>
        </div>

        @if (isset($continueCourse) && $continueCourse)
            <!-- Continue Learning Spotlight (Boxy Hero Widget) -->
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Current Course In Progress</h2>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Last activity {{ $continueCourse->updated_at->diffForHumans() }}</span>
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-5">
                    <div class="relative w-full md:w-52 h-32 rounded-[8px] overflow-hidden shrink-0 border border-slate-200 bg-slate-100 group">
                        <img src="{{ $continueCourse->course->image ?? 'https://picsum.photos/seed/course-'.$continueCourse->course->id.'/320/200' }}"
                             alt="{{ $continueCourse->course->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                            <span class="w-10 h-10 rounded-full bg-white/90 text-[var(--theme-primary,#344c3d)] flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 w-full">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            @if ($continueCourse->course->category)
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-[4px] bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20">
                                    {{ $continueCourse->course->category->name }}
                                </span>
                            @endif
                            <span class="text-xs text-slate-400">
                                {{ $continueCourse->course->lessons()->count() }} {{ __('courses.lessons') ?? 'Lessons' }}
                            </span>
                        </div>

                        <h3 class="text-base sm:text-lg font-bold text-slate-900 truncate">
                            <a href="{{ route('courses.show', $continueCourse->course->slug) }}" class="hover:text-[var(--theme-primary,#344c3d)] transition-colors">
                                {{ $continueCourse->course->title }}
                            </a>
                        </h3>

                        <!-- Instructor with Bulletproof Avatar -->
                        <div class="flex items-center gap-2 mt-2">
                            @php
                                $inst = $continueCourse->course->instructor;
                                $instAvatar = $inst?->avatarUrl();
                            @endphp
                            <div class="w-6 h-6 rounded-full overflow-hidden bg-slate-200 ring-1 ring-slate-300 flex items-center justify-center shrink-0">
                                @if ($instAvatar)
                                    <img src="{{ $instAvatar }}"
                                         alt="{{ $inst->name }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[10px] font-bold items-center justify-center hidden">
                                        {{ strtoupper(substr($inst->name ?? 'I', 0, 1)) }}
                                    </span>
                                @else
                                    <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[10px] font-bold flex items-center justify-center">
                                        {{ strtoupper(substr($inst->name ?? 'I', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs text-slate-600 font-medium truncate">{{ $inst->name ?? 'Lead Instructor' }}</span>
                        </div>

                        <!-- Progress Bar & Actions -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="flex-1 w-full sm:max-w-xs">
                                <div class="flex items-center justify-between text-xs font-semibold mb-1">
                                    <span class="text-slate-600">{{ __('student.enrolled.progress') }}</span>
                                    <span class="text-slate-900 font-bold">{{ $continueCourse->progress }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $continueCourse->progress >= 100 ? 'bg-emerald-500' : 'bg-[var(--theme-primary,#344c3d)]' }} transition-all duration-500"
                                         style="width: {{ $continueCourse->progress }}%"></div>
                                </div>
                            </div>

                            <a href="{{ route('courses.show', $continueCourse->course->slug) }}"
                               class="inline-flex items-center gap-2 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-4 py-2 rounded-[6px] transition-colors shadow-xs">
                                <span>{{ $continueCourse->progress >= 100 ? 'Review Course' : 'Resume Lesson' }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recently Enrolled Section -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">{{ __('student.dashboard.recently_enrolled') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Courses currently in your curriculum</p>
                </div>
                <a href="{{ route('student.enrolled-courses') }}"
                   class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline inline-flex items-center gap-1">
                    <span>{{ __('app.common.view_all') }}</span>
                    <span>→</span>
                </a>
            </div>

            @forelse ($enrollments->take(4) as $enrollment)
                <div class="border border-slate-100 hover:border-slate-300 rounded-[8px] p-3.5 mb-3 transition-colors bg-white hover:bg-slate-50/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-3.5 min-w-0 flex-1">
                        <img src="{{ $enrollment->course->image ?? 'https://picsum.photos/seed/course-'.$enrollment->course->id.'/160/120' }}"
                             alt="{{ $enrollment->course->title }}"
                             class="w-20 sm:w-24 h-16 object-cover rounded-[6px] shrink-0 border border-slate-200">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--theme-accent,#738a6e)]">
                                    {{ $enrollment->course->category?->name ?? 'Course' }}
                                </span>
                            </div>
                            <h4 class="font-bold text-sm text-slate-900 truncate">
                                <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="hover:text-[var(--theme-primary,#344c3d)]">
                                    {{ $enrollment->course->title }}
                                </a>
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                @php
                                    $eInst = $enrollment->course->instructor;
                                    $eAvatar = $eInst?->avatarUrl();
                                @endphp
                                <div class="w-4 h-4 rounded-full overflow-hidden bg-slate-200 ring-1 ring-slate-300 shrink-0">
                                    @if ($eAvatar)
                                        <img src="{{ $eAvatar }}" alt="" class="w-full h-full object-cover" onerror="this.style.display='none';">
                                    @else
                                        <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[8px] font-bold flex items-center justify-center">
                                            {{ strtoupper(substr($eInst->name ?? 'I', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="truncate">{{ $eInst?->name ?? 'Instructor' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 w-full sm:w-auto shrink-0 justify-between sm:justify-end">
                        <div class="w-28 sm:w-32">
                            <div class="flex items-center justify-between text-[11px] mb-1 font-semibold">
                                <span class="text-slate-500">{{ $enrollment->progress }}%</span>
                                <span class="text-slate-400">{{ $enrollment->status === 'completed' ? 'Done' : 'Active' }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $enrollment->progress >= 100 ? 'bg-emerald-500' : 'bg-[var(--theme-primary,#344c3d)]' }}"
                                     style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('courses.show', $enrollment->course->slug) }}"
                           class="bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-[5px] transition-colors whitespace-nowrap">
                            {{ $enrollment->progress >= 100 ? 'Review' : __('student.dashboard.keep_learning') }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="border border-dashed border-slate-200 rounded-[8px] p-8 text-center bg-slate-50/50">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-sm font-medium text-slate-600">{{ __('student.dashboard.no_enrollments') }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ __('student.dashboard.browse_hint') }}</p>
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center gap-1.5 mt-3 bg-[var(--theme-primary,#344c3d)] text-white text-xs font-semibold px-4 py-2 rounded-[6px] shadow-xs hover:bg-[var(--theme-primary-hover,#25372c)] transition-colors">
                        <span>{{ __('student.nav.browse') }}</span>
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Recent Invoices & Billing Table (Boxy Table) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">{{ __('student.dashboard.invoices') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Payment receipts and enrollment transactions</p>
                </div>
                <span class="text-xs font-semibold text-slate-400 font-mono">{{ $enrollments->count() }} records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="text-[11px] uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-100 font-semibold">
                            <th class="px-5 py-3">{{ __('student.dashboard.order_id') }}</th>
                            <th class="px-5 py-3">{{ __('student.dashboard.course') }}</th>
                            <th class="px-5 py-3">{{ __('student.dashboard.date') }}</th>
                            <th class="px-5 py-3">{{ __('student.dashboard.amount') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('student.dashboard.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($enrollments as $enrollment)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3 font-mono font-bold text-[var(--theme-primary,#344c3d)]">
                                    #ENR-{{ str_pad((string) $enrollment->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="font-semibold text-slate-900 hover:text-[var(--theme-primary,#344c3d)] truncate max-w-xs block">
                                        {{ $enrollment->course->title }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-slate-500">
                                    {{ $enrollment->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3 font-bold text-slate-900">
                                    {{ $enrollment->course->price > 0 ? __('app.common.currency').number_format($enrollment->course->price, 2) : __('courses.price_free') }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-[4px] {{ $enrollment->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $enrollment->status === 'completed' ? 'bg-emerald-500' : 'bg-[var(--theme-primary,#344c3d)]' }}"></span>
                                        {{ $enrollment->status === 'completed' ? __('student.dashboard.completed_label') : __('app.common.active') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                    {{ __('student.dashboard.no_enrollments') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
