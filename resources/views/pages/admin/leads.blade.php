<x-layouts.dashboard title="Leads & CRM - Mono LMS" active="leads" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Leads & CRM Pipeline</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $leads->total() }} Prospects
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Track prospective enterprise clients, student leads, and admission pipelines.</p>
            </div>
        </div>

        <!-- 5 Pipeline Stage Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white border border-slate-200 rounded-none p-3.5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">New</span>
                <p class="text-xl font-extrabold text-blue-600 mt-0.5">{{ $stages['new'] }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-3.5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Contacted</span>
                <p class="text-xl font-extrabold text-amber-500 mt-0.5">{{ $stages['contacted'] }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-3.5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Interested</span>
                <p class="text-xl font-extrabold text-purple-600 mt-0.5">{{ $stages['interested'] }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-3.5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Enrolled</span>
                <p class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ $stages['enrolled'] }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-none p-3.5 shadow-xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Closed</span>
                <p class="text-xl font-extrabold text-slate-400 mt-0.5">{{ $stages['closed'] }}</p>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="bg-white border border-slate-200 rounded-none shadow-xs overflow-hidden">
            <x-bulk-toolbar action="{{ route('admin.leads.bulk-destroy') }}" />

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-semibold text-[10px] uppercase tracking-wider">
                            <th class="w-10 py-3.5 px-4"><input type="checkbox" id="check-all" class="control" aria-label="Select All"></th>
                            <th class="py-3.5 px-4">Lead / Company</th>
                            <th class="py-3.5 px-4">Contact Details</th>
                            <th class="py-3.5 px-4">Source</th>
                            <th class="py-3.5 px-4">Notes</th>
                            <th class="py-3.5 px-4">Pipeline Stage</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="w-10 py-3.5 px-4"><input type="checkbox" class="control row-check" name="ids[]" value="{{ $lead->id }}" form="bulk-form" aria-label="{{ $lead->name }}"></td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $lead->name }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    <p>{{ $lead->email }}</p>
                                    @if($lead->phone)
                                        <p class="text-[10px] text-slate-400">{{ $lead->phone }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 uppercase text-[10px] font-semibold text-slate-500">
                                    {{ $lead->source }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate">
                                    {{ $lead->notes ?? '—' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" data-mselect data-mselect-no-search class="h-7 w-auto flex-none text-[10px] font-bold uppercase tracking-wider border border-slate-200 rounded-none px-2.5 bg-white cursor-pointer hover:border-slate-300">
                                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="interested" {{ $lead->status === 'interested' ? 'selected' : '' }}>Interested</option>
                                            <option value="enrolled" {{ $lead->status === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                            <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Remove lead?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger h-7 px-2 text-[10px]" title="Remove lead">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">No leads in pipeline.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.dashboard>
