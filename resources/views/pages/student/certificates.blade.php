<x-layouts.dashboard title="My Certificates - Dreams LMS" active="certificates" userType="student">

    <div class="space-y-6">
        <!-- Header (Figma: 5035:15047) -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-[#191919]">My Certificates (05)</h2>
                <p class="text-xs text-[#6d6d6d] mt-0.5">Accredited verified certificates earned from completed LMS curricula.</p>
            </div>
            <button class="bg-[#392c7d] hover:bg-[#ff4667] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                Verify Credential ID
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $certs = [
                    ['title' => 'The Complete Business & Management Masterclass', 'code' => 'CERT-LMS-98214', 'date' => 'October 15, 2026', 'grade' => 'Grade A+ (94%)'],
                    ['title' => 'Modern React with Redux & Next.js 15', 'code' => 'CERT-LMS-74620', 'date' => 'September 28, 2026', 'grade' => 'Grade A (89%)'],
                    ['title' => 'Graphic Design Masterclass: Photoshop & Illustrator', 'code' => 'CERT-LMS-55319', 'date' => 'August 14, 2026', 'grade' => 'Grade A+ (96%)'],
                    ['title' => 'Agile Scrum Master Certification Guide', 'code' => 'CERT-LMS-41092', 'date' => 'July 05, 2026', 'grade' => 'Grade A (91%)'],
                    ['title' => 'SEO & Inbound Marketing Foundations', 'code' => 'CERT-LMS-33105', 'date' => 'May 20, 2026', 'grade' => 'Grade A+ (95%)'],
                ];
            @endphp

            @foreach($certs as $cert)
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <!-- Certificate Thumbnail Preview -->
                        <div class="relative h-40 bg-gradient-to-br from-indigo-50 to-purple-100 rounded-lg border-2 border-dashed border-[#392c7d]/30 p-4 flex flex-col justify-between text-center mb-4">
                            <div class="text-[10px] font-bold tracking-widest text-[#392c7d] uppercase">Dreams LMS Certificate</div>
                            <div>
                                <div class="text-xs font-extrabold text-[#191919] line-clamp-1">{{ $cert['title'] }}</div>
                                <div class="text-[10px] text-gray-500 mt-1">Presented to Eugene Andre</div>
                            </div>
                            <div class="flex items-center justify-between text-[9px] text-[#ff4667] font-mono">
                                <span>{{ $cert['code'] }}</span>
                                <span>Verified ✓</span>
                            </div>
                        </div>

                        <h4 class="font-bold text-sm text-[#191919] mb-1.5">{{ $cert['title'] }}</h4>
                        <div class="text-xs text-[#6d6d6d] space-y-1 mb-4">
                            <div>Issued: <strong class="text-gray-800">{{ $cert['date'] }}</strong></div>
                            <div>Score: <strong class="text-[#03c95a]">{{ $cert['grade'] }}</strong></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                        <button class="flex-1 bg-[#392c7d] hover:bg-[#ff4667] text-white text-xs font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Download PDF</span>
                        </button>
                        <button class="bg-[#f4f6f9] hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg transition-colors" title="Share on LinkedIn">
                            Share
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.dashboard>
