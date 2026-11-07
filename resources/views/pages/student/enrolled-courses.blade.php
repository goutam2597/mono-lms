<x-layouts.dashboard title="Enrolled Courses - Dreams LMS" active="enrolled" userType="student">

    <div class="space-y-6">
        <!-- Header & Filter Tabs (Figma: 5013:75981) -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-[#191919]">Enrolled Courses</h2>
            <div class="flex items-center gap-2">
                <button class="bg-[#ff4667] text-white text-xs font-semibold px-4 py-2 rounded-lg">
                    All Courses (12)
                </button>
                <button class="bg-[#f4f6f9] hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                    Active (2)
                </button>
                <button class="bg-[#f4f6f9] hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                    Completed (10)
                </button>
            </div>
        </div>

        <!-- Enrolled Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $courses = [
                    ['title' => 'Information About UI/UX Design Degree', 'progress' => 65, 'lessons' => '12/20 Lessons', 'category' => 'Design', 'img' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80'],
                    ['title' => 'Complete Python Bootcamp From Zero to Hero', 'progress' => 25, 'lessons' => '5/24 Lessons', 'category' => 'Python', 'img' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80'],
                    ['title' => 'The Complete Business and Management Masterclass', 'progress' => 100, 'lessons' => '18/18 Lessons', 'category' => 'Business', 'img' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=80'],
                    ['title' => 'Modern React with Redux and Next.js 15', 'progress' => 100, 'lessons' => '30/30 Lessons', 'category' => 'Frontend', 'img' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80'],
                    ['title' => 'Graphic Design Masterclass: Photoshop & Illustrator', 'progress' => 100, 'lessons' => '15/15 Lessons', 'category' => 'Design', 'img' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&auto=format&fit=crop&q=80'],
                    ['title' => 'Agile Scrum Master Certification Guide', 'progress' => 100, 'lessons' => '12/12 Lessons', 'category' => 'Agile', 'img' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=600&auto=format&fit=crop&q=80'],
                ];
            @endphp

            @foreach($courses as $c)
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="relative h-36 rounded-lg overflow-hidden mb-3 bg-gray-100">
                            <img src="{{ $c['img'] }}" alt="{{ $c['title'] }}" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-xs text-[#191919] text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $c['category'] }}</span>
                        </div>

                        <h4 class="font-bold text-sm text-[#191919] mb-3 line-clamp-2">{{ $c['title'] }}</h4>

                        <div class="space-y-1.5 mb-4">
                            <div class="flex items-center justify-between text-xs text-[#6d6d6d]">
                                <span>{{ $c['lessons'] }}</span>
                                <strong class="{{ $c['progress'] == 100 ? 'text-[#03c95a]' : 'text-[#ff4667]' }}">{{ $c['progress'] }}%</strong>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $c['progress'] == 100 ? 'bg-[#03c95a]' : 'bg-[#ff4667]' }} rounded-full" style="width: {{ $c['progress'] }}%;"></div>
                            </div>
                        </div>
                    </div>

                    @if($c['progress'] == 100)
                        <a href="{{ route('student.certificates') }}" class="block text-center bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-xs font-bold py-2 rounded-lg transition-colors">
                            Download Certificate 🏆
                        </a>
                    @else
                        <a href="{{ route('courses.detail') }}" class="block text-center bg-[#392c7d] hover:bg-[#ff4667] text-white text-xs font-bold py-2 rounded-lg transition-colors">
                            Continue Class →
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.dashboard>
