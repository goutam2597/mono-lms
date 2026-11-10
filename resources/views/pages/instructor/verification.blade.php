<x-layouts.dashboard title="Instructor Verification - Mono LMS" active="verification" userType="instructor">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-[#191919]">Instructor Identity &amp; Certification Verification</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">To ensure platform safety and quality, all instructors must be verified with their national ID and instructor certification by an administrator before creating courses.</p>
            </div>
            <div>
                @if ($instructor->isVerified())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Verified Instructor
                    </span>
                @elseif ($instructor->isPendingVerification())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <svg class="w-4 h-4 text-amber-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Pending Admin Review
                    </span>
                @elseif ($instructor->isVerificationRejected())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Action Required / Rejected
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        Unverified
                    </span>
                @endif
            </div>
        </div>

        <!-- Status Alerts -->
        @if (session('status'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($instructor->isVerified())
            <div class="bg-white border border-emerald-200 rounded-2xl p-6 sm:p-8 shadow-xs">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-emerald-900">Your Instructor Account is Fully Verified!</h3>
                        <p class="text-xs text-emerald-800 leading-relaxed">Congratulations! Your instructor credentials and identity have been confirmed by our administration on {{ $instructor->verified_at ? $instructor->verified_at->format('M d, Y') : 'record' }}. You have complete access to create courses, publish curriculum, and your profile is listed in the public instructor directory.</p>
                        <div class="pt-3">
                            <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-semibold transition-all shadow-xs">
                                <span>+ Create Your Next Course</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($instructor->isPendingVerification())
            <div class="bg-white border border-amber-200 rounded-2xl p-6 sm:p-8 shadow-xs">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-amber-900">Verification Under Admin Review</h3>
                        <p class="text-xs text-amber-800 leading-relaxed">Your instructor certificate and national ID have been uploaded and are actively queued for admin review. Reviews are typically completed within 24 to 48 hours. Once approved, course creation tools will automatically unlock.</p>
                        <div class="pt-2 text-xs text-gray-500">
                            <span>Uploaded files: </span>
                            @if ($instructor->certification_file)
                                <a href="{{ $instructor->certificationUrl() }}" target="_blank" class="font-medium text-[#344c3d] underline hover:text-[#25372c] mr-3">View Certificate</a>
                            @endif
                            @if ($instructor->national_id_file)
                                <a href="{{ $instructor->nationalIdUrl() }}" target="_blank" class="font-medium text-[#344c3d] underline hover:text-[#25372c]">View National ID</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($instructor->isVerificationRejected())
            <div class="bg-white border border-red-200 rounded-2xl p-6 sm:p-8 shadow-xs">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-red-900">Verification Rejected</h3>
                        <p class="text-xs text-red-800 leading-relaxed">
                            Your verification was not approved. <strong>Reason:</strong> {{ $instructor->rejection_reason ?: 'Documents could not be verified. Please ensure valid, legible official documents.' }}
                        </p>
                        <p class="text-xs text-gray-600">Please review the reason above and resubmit fresh, clear copies of your documents below.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Document Upload / Resubmission Form (shown if not verified or if updating) -->
        @if (! $instructor->isVerified())
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs">
                <div class="pb-4 mb-6 border-b border-gray-100">
                    <h3 class="text-base font-bold text-[#191919]">{{ $instructor->isPendingVerification() ? 'Update Submitted Documents' : 'Submit Required Documents' }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, PNG, WebP (maximum 10MB per file). Ensure text, government seals, and your full legal name are clearly visible.</p>
                </div>

                <form action="{{ route('instructor.verification.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Instructor Certification File -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-[#191919] uppercase tracking-wider">
                                    Instructor Certification / Degree <span class="text-red-500">*</span>
                                </label>
                                @if ($instructor->certification_file)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Attached
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400">Instructor certificate, university degree, diploma, or professional accreditation.</p>

                            <div id="cert-dropzone" class="relative border-2 border-dashed {{ $errors->has('certification_file') ? 'border-red-400 bg-red-50/20' : 'border-slate-300 hover:border-[#344c3d] bg-slate-50/50 hover:bg-slate-50' }} rounded-2xl p-6 sm:p-8 min-h-[190px] flex flex-col items-center justify-center text-center transition-all cursor-pointer group">
                                <input type="file" name="certification_file" id="certification_file" {{ $instructor->certification_file ? '' : 'required' }} accept=".pdf,.jpg,.jpeg,.png,.webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-center text-[#344c3d] group-hover:scale-110 group-hover:bg-[#344c3d] group-hover:text-white transition-all mb-3 pointer-events-none">
                                    <svg class="w-6 h-6 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>

                                <div class="space-y-1 pointer-events-none" id="cert-prompt">
                                    <p class="text-xs font-semibold text-slate-800">
                                        <span class="text-[#344c3d] underline underline-offset-2">Choose file</span>
                                        <span class="text-slate-500 font-normal"> or drag &amp; drop here</span>
                                    </p>
                                    <p class="text-[11px] text-slate-400">PDF, JPG, PNG or WebP (max 10MB)</p>
                                </div>

                                <div id="cert-selected" class="hidden mt-1 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2 max-w-full pointer-events-none shadow-2xs">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span id="cert-name" class="font-semibold truncate max-w-[180px] sm:max-w-[220px]">file.pdf</span>
                                    <span id="cert-size" class="text-[10px] text-emerald-700 ml-auto shrink-0 font-medium"></span>
                                </div>

                                @if ($instructor->certification_file)
                                    <div class="mt-3 pointer-events-auto">
                                        <a href="{{ $instructor->certificationUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#344c3d] hover:text-[#25372c] underline">
                                            <span>View Current Certificate</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @error('certification_file')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- National ID / Passport File -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-[#191919] uppercase tracking-wider">
                                    National ID / Passport <span class="text-red-500">*</span>
                                </label>
                                @if ($instructor->national_id_file)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Attached
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400">Government-issued ID card, passport, or driver's license showing your identity clearly.</p>

                            <div id="id-dropzone" class="relative border-2 border-dashed {{ $errors->has('national_id_file') ? 'border-red-400 bg-red-50/20' : 'border-slate-300 hover:border-[#344c3d] bg-slate-50/50 hover:bg-slate-50' }} rounded-2xl p-6 sm:p-8 min-h-[190px] flex flex-col items-center justify-center text-center transition-all cursor-pointer group">
                                <input type="file" name="national_id_file" id="national_id_file" {{ $instructor->national_id_file ? '' : 'required' }} accept=".pdf,.jpg,.jpeg,.png,.webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-center text-[#344c3d] group-hover:scale-110 group-hover:bg-[#344c3d] group-hover:text-white transition-all mb-3 pointer-events-none">
                                    <svg class="w-6 h-6 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                </div>

                                <div class="space-y-1 pointer-events-none" id="id-prompt">
                                    <p class="text-xs font-semibold text-slate-800">
                                        <span class="text-[#344c3d] underline underline-offset-2">Choose file</span>
                                        <span class="text-slate-500 font-normal"> or drag &amp; drop here</span>
                                    </p>
                                    <p class="text-[11px] text-slate-400">PDF, JPG, PNG or WebP (max 10MB)</p>
                                </div>

                                <div id="id-selected" class="hidden mt-1 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2 max-w-full pointer-events-none shadow-2xs">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span id="id-name" class="font-semibold truncate max-w-[180px] sm:max-w-[220px]">file.pdf</span>
                                    <span id="id-size" class="text-[10px] text-emerald-700 ml-auto shrink-0 font-medium"></span>
                                </div>

                                @if ($instructor->national_id_file)
                                    <div class="mt-3 pointer-events-auto">
                                        <a href="{{ $instructor->nationalIdUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#344c3d] hover:text-[#25372c] underline">
                                            <span>View Current National ID</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @error('national_id_file')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notice -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Your personal documents are encrypted and kept strictly confidential. They are used exclusively by administrators to verify your instructor identity and eligibility to teach.</span>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-[#344c3d] hover:bg-[#25372c] text-white px-6 py-3 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all shadow-md cursor-pointer">
                            <span>Submit Documents for Review</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Live File Dropzone Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupDropzone(inputId, dropzoneId, promptId, selectedId, nameId, sizeId) {
                var input = document.getElementById(inputId);
                var dropzone = document.getElementById(dropzoneId);
                var prompt = document.getElementById(promptId);
                var selected = document.getElementById(selectedId);
                var nameEl = document.getElementById(nameId);
                var sizeEl = document.getElementById(sizeId);

                if (!input || !dropzone) return;

                function formatBytes(bytes) {
                    if (!bytes) return '0 B';
                    var k = 1024;
                    var sizes = ['B', 'KB', 'MB', 'GB'];
                    var i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                }

                input.addEventListener('change', function () {
                    var file = this.files && this.files[0];
                    if (file) {
                        nameEl.textContent = file.name;
                        sizeEl.textContent = '(' + formatBytes(file.size) + ')';
                        selected.classList.remove('hidden');
                        prompt.classList.add('opacity-40');
                        dropzone.classList.remove('border-slate-300');
                        dropzone.classList.add('border-emerald-500', 'bg-emerald-50/20');
                    }
                });

                ['dragenter', 'dragover'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.classList.add('border-[#344c3d]', 'bg-slate-100');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.classList.remove('border-[#344c3d]', 'bg-slate-100');
                    });
                });
            }

            setupDropzone('certification_file', 'cert-dropzone', 'cert-prompt', 'cert-selected', 'cert-name', 'cert-size');
            setupDropzone('national_id_file', 'id-dropzone', 'id-prompt', 'id-selected', 'id-name', 'id-size');
        });
    </script>
</x-layouts.dashboard>
