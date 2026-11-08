<x-layouts.dashboard title="Certificate Templates - Mono LMS" active="certificates" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Certificate Templates</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $templates->count() }} Templates
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Design and manage accredited completion certificates awarded to students upon course completion.</p>
            </div>

            <button type="button" onclick="document.getElementById('createCertModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#283a2f] text-white transition-colors shadow-xs shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>New Template</span>
            </button>
        </div>

        <!-- Certificate Templates Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs flex flex-col justify-between hover:border-slate-300 transition-all">
                    <!-- Certificate Live Graphical Preview -->
                    <div class="p-6 bg-slate-50 border-b border-slate-200 flex items-center justify-center">
                        <div class="w-full aspect-[4/3] rounded-lg p-4 flex flex-col justify-between shadow-sm relative overflow-hidden" style="background-color: {{ $template->bg_color }}; border: 4px double {{ $template->border_color }};">
                            <!-- Header seal -->
                            <div class="text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 block">Certificate of Achievement</span>
                                <h4 class="text-xs font-serif font-bold text-slate-900 mt-1">{{ $template->title }}</h4>
                            </div>

                            <!-- Student preview placeholder -->
                            <div class="text-center my-auto py-2">
                                <p class="text-[8px] text-slate-400 italic">This is proudly presented to</p>
                                <p class="text-xs font-bold text-[#344c3d] font-serif border-b border-slate-300 inline-block px-3 pb-0.5">Alex Johnson</p>
                                <p class="text-[8px] text-slate-500 mt-1">For successfully mastering the curriculum with excellence.</p>
                            </div>

                            <!-- Footer Signatures -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-200 text-[8px]">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $template->signer_name }}</span>
                                    <span class="text-slate-400 text-[7px]">{{ $template->signature_title }}</span>
                                </div>
                                <div class="w-6 h-6 rounded-full border border-amber-500/60 bg-amber-50 flex items-center justify-center text-amber-600 font-bold text-[8px]">
                                    ★
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Info & Controls -->
                    <div class="p-5 flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs font-bold text-slate-900">{{ $template->title }}</h3>
                                @if($template->is_default)
                                    <span class="text-[9px] font-bold px-2 py-0.2 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Default
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5 uppercase tracking-wider font-semibold">{{ $template->template_type }} layout</p>
                        </div>

                        <div>
                            @if(! $template->is_default)
                                <form method="POST" action="{{ route('admin.certificates.default', $template) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors">
                                        Set Default
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Active</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Certificate Modal -->
    <div id="createCertModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-5 animate-in fade-in zoom-in duration-150">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">New Certificate Template</h3>
                <button type="button" onclick="document.getElementById('createCertModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.certificates.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Template Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Masterclass Executive Diploma" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Layout Style *</label>
                    <select name="template_type" class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white focus:outline-none focus:border-[#344c3d]">
                        <option value="classic">Classic Academic</option>
                        <option value="modern">Modern Minimalist</option>
                        <option value="elegant">Elegant Executive</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signer Name *</label>
                        <input type="text" name="signer_name" required value="Dr. Alexander Wright" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signature Title *</label>
                        <input type="text" name="signature_title" required value="Academic Director" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Background Color</label>
                        <input type="text" name="bg_color" value="#ffffff" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Border Color</label>
                        <input type="text" name="border_color" value="#344c3d" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:border-[#344c3d]">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createCertModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#344c3d] text-white font-semibold rounded-xl hover:bg-[#283a2f] shadow-xs">
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.dashboard>
