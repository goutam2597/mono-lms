<x-layouts.dashboard title="Instructor Courses - Dreams LMS" active="courses" userType="instructor">

    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#191919]">My Published Courses (12)</h2>
                <p class="text-xs text-[#6d6d6d] mt-0.5">Manage course status, pricing, curriculum, and enrollments.</p>
            </div>
            <a href="{{ route('courses.grid') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold px-4 py-2.5 rounded-full transition-colors shadow-sm">
                + Add New Course
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 text-[#888888]">
                        <th class="pb-3 font-semibold">COURSE TITLE</th>
                        <th class="pb-3 font-semibold">ENROLLED</th>
                        <th class="pb-3 font-semibold">PRICE</th>
                        <th class="pb-3 font-semibold">RATING</th>
                        <th class="pb-3 font-semibold">STATUS</th>
                        <th class="pb-3 font-semibold text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-[#191919]">
                    <tr>
                        <td class="py-4">
                            <div class="font-bold text-sm text-[#191919]">Complete Python Bootcamp From Zero to Hero</div>
                            <div class="text-[11px] text-gray-500">24 Lectures • 22 Hours Total</div>
                        </td>
                        <td class="py-4 font-semibold text-gray-700">4,280 Students</td>
                        <td class="py-4 font-bold text-[#191919]">$150.00</td>
                        <td class="py-4 font-bold text-[#ffc107]">★ 4.8</td>
                        <td class="py-4"><span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold">Published</span></td>
                        <td class="py-4 text-right space-x-2">
                            <a href="{{ route('courses.detail') }}" class="text-[#392c7d] hover:underline font-semibold">View</a>
                            <button class="text-gray-400 hover:text-red-500">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="font-bold text-sm text-[#191919]">Laravel 12 API Development and Testing Masterclass</div>
                            <div class="text-[11px] text-gray-500">18 Lectures • 16 Hours Total</div>
                        </td>
                        <td class="py-4 font-semibold text-gray-700">3,120 Students</td>
                        <td class="py-4 font-bold text-[#191919]">$145.00</td>
                        <td class="py-4 font-bold text-[#ffc107]">★ 4.9</td>
                        <td class="py-4"><span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold">Published</span></td>
                        <td class="py-4 text-right space-x-2">
                            <a href="{{ route('courses.detail') }}" class="text-[#392c7d] hover:underline font-semibold">View</a>
                            <button class="text-gray-400 hover:text-red-500">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.dashboard>
