<x-layouts.app title="Instructor List - Dreams LMS" active="instructors">

    <div class="bg-[#f4f6f9] py-10 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#191919]">Instructor List</h1>
                <nav class="flex items-center gap-2 text-xs text-[#6d6d6d] mt-2">
                    <a href="{{ route('home') }}" class="hover:text-[#ff4667]">Home</a>
                    <span>/</span>
                    <a href="{{ route('instructors.grid') }}" class="hover:text-[#ff4667]">Instructors</a>
                    <span>/</span>
                    <span class="text-[#ff4667] font-medium">Instructor List</span>
                </nav>
            </div>
            <div class="text-xs text-[#888888] font-medium">
                Figma Node: <code class="bg-white px-2 py-1 rounded border border-gray-200 font-mono text-[#191919]">311:35028</code>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <div class="space-y-6">
            @php
                $instructors = [
                    [
                        'name' => 'Rolands Granger',
                        'role' => 'Senior Full-Stack Engineer & Architect',
                        'bio' => 'Teaching web architecture, PHP, Laravel, Docker, and modern reactive frontends with over 10 years of production experience.',
                        'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
                        'rating' => '4.9',
                        'reviews' => '200',
                        'students' => '12,450',
                        'courses' => '12'
                    ],
                    [
                        'name' => 'David Benitez',
                        'role' => 'Principal Product Designer & Figma Certified',
                        'bio' => 'Former design lead at high-growth startups, coaching digital designers on building design systems and seamless UX flows.',
                        'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
                        'rating' => '4.9',
                        'reviews' => '180',
                        'students' => '9,800',
                        'courses' => '8'
                    ],
                    [
                        'name' => 'Hinata Hyuga',
                        'role' => 'Machine Learning & Python Specialist',
                        'bio' => 'Specializing in deep learning, neural networks, computer vision, and big data pipelines for AI-driven applications.',
                        'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80',
                        'rating' => '5.0',
                        'reviews' => '310',
                        'students' => '15,200',
                        'courses' => '14'
                    ],
                ];
            @endphp

            @foreach($instructors as $inst)
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <img src="{{ $inst['image'] }}" alt="{{ $inst['name'] }}" class="w-28 h-28 rounded-2xl object-cover shrink-0">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#ffc107]">★ ★ ★ ★ ★</span>
                            <span class="text-xs font-bold text-gray-800">{{ $inst['rating'] }} ({{ $inst['reviews'] }} Reviews)</span>
                        </div>
                        <a href="{{ route('instructors.detail') }}" class="block text-xl font-bold text-[#191919] hover:text-[#ff4667] transition-colors">
                            {{ $inst['name'] }}
                        </a>
                        <p class="text-xs text-[#ff4667] font-semibold">{{ $inst['role'] }}</p>
                        <p class="text-sm text-[#6d6d6d]">{{ $inst['bio'] }}</p>
                        <div class="flex items-center gap-6 pt-1 text-xs text-gray-500">
                            <span>👥 <strong>{{ $inst['students'] }}</strong> Students</span>
                            <span>📚 <strong>{{ $inst['courses'] }}</strong> Courses</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                        <a href="{{ route('instructors.detail') }}" class="block text-center bg-[#08131e] hover:bg-[#ff4667] text-white text-xs font-semibold px-5 py-2.5 rounded-full transition-colors whitespace-nowrap">
                            View Profile →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-layouts.app>
