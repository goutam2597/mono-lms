<x-layouts.dashboard title="Certificate Templates - Mono LMS" active="certificates" userType="admin">

    <div class="space-y-6">
        <!-- Top Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Design Templates</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $templates->count() }}</p>
                    <span class="text-[11px] text-slate-400">Available award styles</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Default Template</span>
                    <p class="text-base font-bold text-slate-900 mt-1 line-clamp-1">{{ $defaultTemplate?->title ?? 'None Set' }}</p>
                    <span class="text-[11px] text-emerald-600/80 font-medium font-mono uppercase">{{ $defaultTemplate?->template_type ?? 'classic' }} layout</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Issued Certificates</span>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalIssued ?? 0) }}</p>
                    <span class="text-[11px] text-slate-400">Awarded to graduates</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
            </div>
        </div>

        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-5 sm:p-6 shadow-xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Certificate Templates Gallery</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Design, customize, and manage accredited certificates automatically awarded upon course graduation.</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('certificates.verify.show') }}" target="_blank" class="action-btn action-btn-ghost h-9 px-3.5 text-xs font-semibold shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Verify Tool</span>
                </a>
                <button type="button" onclick="openCreateModal()" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider shrink-0 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Template</span>
                </button>
            </div>
        </div>

        <!-- Certificate Templates Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <div class="bg-white border border-slate-200 rounded-none overflow-hidden shadow-xs flex flex-col justify-between hover:border-slate-400 transition-all">
                    <!-- Certificate Live Graphical Preview -->
                    <div class="p-6 bg-slate-50 border-b border-slate-200 flex items-center justify-center">
                        <div class="w-full aspect-[4/3] rounded-none p-4 flex flex-col justify-between shadow-xs relative overflow-hidden select-none"
                             style="background-color: {{ $template->bg_color }}; border: 4px double {{ $template->border_color }};">
                            <!-- Header seal -->
                            <div class="text-center">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400 block font-mono">MONO LMS ACADEMY</span>
                                <h4 class="text-xs font-serif font-bold text-slate-900 mt-0.5 line-clamp-1">{{ $template->title }}</h4>
                            </div>

                            <!-- Student preview placeholder -->
                            <div class="text-center my-auto py-1">
                                <p class="text-[7px] text-slate-400 italic">This is proudly awarded to</p>
                                <p class="text-xs font-bold text-[#344c3d] font-serif border-b border-slate-300 inline-block px-3 pb-0.5">Alex Johnson</p>
                                <p class="text-[7px] text-slate-500 mt-1 line-clamp-2">{{ $template->subtitle ?: 'For successfully mastering the curriculum with academic excellence.' }}</p>
                            </div>

                            <!-- Footer Signatures -->
                            <div class="flex items-center justify-between pt-1.5 border-t border-slate-200 text-[8px]">
                                <div>
                                    <span class="font-bold text-slate-800 block text-[9px]">{{ $template->signer_name }}</span>
                                    <span class="text-slate-400 text-[7px]">{{ $template->signature_title }}</span>
                                </div>
                                <div class="w-6 h-6 rounded-full border border-amber-500/60 bg-amber-50 flex items-center justify-center text-amber-600 font-bold text-[8px] shrink-0">
                                    ★
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Info & Controls -->
                    <div class="p-5 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs font-bold text-slate-900">{{ $template->title }}</h3>
                                    @if($template->is_default)
                                        <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Default
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-wider font-semibold">
                                    {{ $template->template_type }} layout · {{ $template->certificates_count }} Issued
                                </p>
                            </div>

                            @if(! $template->is_default)
                                <form method="POST" action="{{ route('admin.certificates.default', $template) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn action-btn-ghost h-7 px-2.5 text-[10px]">
                                        Set Default
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-1">
                                    ✓ Active
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="openEditModal({{ json_encode($template) }})"
                                        class="text-xs font-semibold text-slate-700 hover:text-slate-900 hover:underline">
                                    Edit Settings
                                </button>
                                <span class="text-slate-300">•</span>
                                <button type="button"
                                        onclick="openPreviewModal({{ json_encode($template) }})"
                                        class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">
                                    Full Preview
                                </button>
                            </div>

                            @if (! $template->is_default)
                                <form method="POST" action="{{ route('admin.certificates.destroy', $template) }}" onsubmit="return confirm('Delete template \'{{ $template->title }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold" title="Delete Template">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Issued Certificates Registry -->
        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919] text-base">Graduation Registry & Issued Certificates</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Real-time log of credentials awarded to students upon course completion</p>
                </div>
                <span class="text-xs font-bold text-slate-700 font-mono">
                    {{ number_format($totalIssued) }} total verified records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] uppercase tracking-wider text-slate-500 bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold">Credential ID</th>
                            <th class="px-5 py-3 font-semibold">Student Name</th>
                            <th class="px-5 py-3 font-semibold">Course Title</th>
                            <th class="px-5 py-3 font-semibold">Template</th>
                            <th class="px-5 py-3 font-semibold">Standing</th>
                            <th class="px-5 py-3 font-semibold">Issue Date</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($issued as $cert)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900 whitespace-nowrap">
                                    {{ $cert->certificate_number }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <p class="font-bold text-slate-900">{{ $cert->student_name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $cert->student?->email ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 max-w-xs">
                                    <p class="font-semibold text-slate-800 truncate">{{ $cert->course_title }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">Instructor: {{ $cert->instructor_name ?: 'Course Mentor' }}</p>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-600">
                                    {{ $cert->template?->title ?? 'Default Template' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-emerald-700 font-bold font-mono text-[11px]">
                                        {{ $cert->grade ?: 'Passed with Distinction' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-500 text-[11px]">
                                    {{ $cert->issue_date->format('M d, Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('student.certificates.print', $cert) }}"
                                           target="_blank"
                                           class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition-colors">
                                            Print / PDF
                                        </a>
                                        <a href="{{ $cert->verificationUrl() }}"
                                           target="_blank"
                                           class="px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[11px] font-semibold transition-colors">
                                            Verify
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                    No student certificates issued yet. When students complete 100% of curriculum lessons, issued credentials will appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($issued->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $issued->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Certificate Modal -->
    <div id="createCertModal" class="modal-overlay" role="dialog" aria-modal="true" onclick="if(event.target===this)closeModal('createCertModal')">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">New Certificate Template</h3>
                <button type="button" onclick="closeModal('createCertModal')" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.certificates.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Template Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Masterclass Executive Diploma" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Layout Style *</label>
                    <select name="template_type" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="classic">Classic Academic</option>
                        <option value="modern">Modern Minimalist</option>
                        <option value="elegant">Elegant Executive</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signer Name *</label>
                        <input type="text" name="signer_name" required value="Dr. Alexander Wright" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signature Title *</label>
                        <input type="text" name="signature_title" required value="Program Director" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Award Subtitle Text</label>
                    <textarea name="subtitle" rows="2" placeholder="For successfully mastering the curriculum with academic excellence..." class="w-full p-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">For successfully mastering the comprehensive curriculum with academic excellence.</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Background Color</label>
                        <input type="text" name="bg_color" value="#ffffff" class="w-full px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Border Color</label>
                        <input type="text" name="border_color" value="#344c3d" class="w-full px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 font-mono">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('createCertModal')" class="action-btn action-btn-ghost h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Certificate Modal -->
    <div id="editCertModal" class="modal-overlay" role="dialog" aria-modal="true" onclick="if(event.target===this)closeModal('editCertModal')">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Edit Certificate Template</h3>
                <button type="button" onclick="closeModal('editCertModal')" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form id="editTemplateForm" method="POST" action="" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Template Title *</label>
                    <input type="text" id="edit_title" name="title" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Layout Style *</label>
                    <select id="edit_template_type" name="template_type" class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none bg-white focus:outline-none focus:border-slate-900">
                        <option value="classic">Classic Academic</option>
                        <option value="modern">Modern Minimalist</option>
                        <option value="elegant">Elegant Executive</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signer Name *</label>
                        <input type="text" id="edit_signer_name" name="signer_name" required class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Signature Title *</label>
                        <input type="text" id="edit_signature_title" name="signature_title" required class="h-10 w-full px-3 text-xs border border-slate-200 rounded-none focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Award Subtitle Text</label>
                    <textarea id="edit_subtitle" name="subtitle" rows="2" class="w-full p-2.5 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Background Color</label>
                        <input type="text" id="edit_bg_color" name="bg_color" class="w-full px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Border Color</label>
                        <input type="text" id="edit_border_color" name="border_color" class="w-full px-3 py-2 border border-slate-200 rounded-none focus:outline-none focus:border-slate-900 font-mono">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editCertModal')" class="action-btn action-btn-ghost h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider">
                        Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Preview Modal -->
    <div id="previewCertModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white max-w-4xl w-full border border-slate-200 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900 text-sm" id="preview-modal-title">Certificate Live Preview</h3>
                <button type="button" onclick="document.getElementById('previewCertModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-lg">&times;</button>
            </div>

            <div class="p-8 bg-slate-100 flex items-center justify-center">
                <div id="preview-frame" class="w-full max-w-2xl aspect-[1.414/1] bg-white p-8 shadow-xl flex flex-col justify-between text-center relative overflow-hidden"
                     style="background-color: #ffffff; border: 8px double #344c3d;">
                    <div>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 block font-mono">MONO LMS ACCREDITED ACADEMY</span>
                        <h2 class="text-xl font-serif font-extrabold text-slate-900 mt-1 uppercase" id="preview-template-title">Certificate of Achievement</h2>
                    </div>

                    <div class="my-auto py-2">
                        <p class="text-xs italic text-slate-500 font-serif">This proudly certifies that</p>
                        <p class="text-2xl font-bold font-serif text-slate-900 border-b-2 inline-block px-8 pb-1 mt-1" id="preview-border-sample" style="border-color: #344c3d;">
                            Alex Johnson
                        </p>
                        <p class="text-[11px] text-slate-600 max-w-md mx-auto mt-2 leading-relaxed" id="preview-subtitle">
                            has successfully mastered the comprehensive curriculum with academic distinction.
                        </p>
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mt-1">Full-Stack Web Development Masterclass</h3>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-200 text-xs">
                        <div class="text-left">
                            <span class="font-bold text-slate-900 block" id="preview-signer">Dr. Alexander Wright</span>
                            <span class="text-[10px] text-slate-500" id="preview-signer-title">Program Director</span>
                        </div>
                        <div class="w-12 h-12 rounded-full border-2 border-amber-500 bg-amber-50 flex items-center justify-center text-amber-700 font-bold text-xs shadow-2xs">
                            ★ VERIFIED
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createCertModal').classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        function openEditModal(template) {
            const form = document.getElementById('editTemplateForm');
            form.action = '/admin/certificate-templates/' + template.id;

            document.getElementById('edit_title').value = template.title || '';
            document.getElementById('edit_template_type').value = template.template_type || 'classic';
            document.getElementById('edit_signer_name').value = template.signer_name || '';
            document.getElementById('edit_signature_title').value = template.signature_title || '';
            document.getElementById('edit_subtitle').value = template.subtitle || '';
            document.getElementById('edit_bg_color').value = template.bg_color || '#ffffff';
            document.getElementById('edit_border_color').value = template.border_color || '#344c3d';

            document.getElementById('editCertModal').classList.add('open');
        }

        function openPreviewModal(template) {
            document.getElementById('preview-modal-title').textContent = 'Preview: ' + template.title;
            document.getElementById('preview-template-title').textContent = template.title;
            document.getElementById('preview-subtitle').textContent = template.subtitle || 'has successfully mastered the comprehensive curriculum with academic distinction.';
            document.getElementById('preview-signer').textContent = template.signer_name || 'Dr. Alexander Wright';
            document.getElementById('preview-signer-title').textContent = template.signature_title || 'Program Director';

            const frame = document.getElementById('preview-frame');
            frame.style.backgroundColor = template.bg_color || '#ffffff';
            frame.style.borderColor = template.border_color || '#344c3d';

            const borderSample = document.getElementById('preview-border-sample');
            borderSample.style.borderColor = template.border_color || '#344c3d';

            document.getElementById('previewCertModal').classList.remove('hidden');
        }
    </script>

</x-layouts.dashboard>
