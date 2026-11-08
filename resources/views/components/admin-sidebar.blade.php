@props(['active' => 'dashboard', 'admin' => null])

@php
    $admin ??= auth('admin')->user();

    $groups = [
        [
            'title' => 'Overview',
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'items' => [
                ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ],
        ],
        [
            'title' => 'Courses & Learning',
            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'items' => [
                ['id' => 'courses', 'label' => 'All Courses', 'route' => 'admin.courses.index'],
                ['id' => 'categories', 'label' => 'Categories', 'route' => 'admin.categories.index'],
                ['id' => 'reviews', 'label' => 'Reviews', 'route' => 'admin.reviews.index', 'badge' => \App\Models\Review::where('status', 'pending')->count()],
                ['id' => 'certificates', 'label' => 'Certificate Templates', 'route' => 'admin.certificates.index'],
                ['id' => 'events', 'label' => 'Events & Webinars', 'route' => 'admin.events.index'],
            ],
        ],
        [
            'title' => 'Users & Staff',
            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'items' => [
                ['id' => 'users', 'label' => 'All Users', 'route' => 'admin.users.index'],
                ['id' => 'students', 'label' => 'Students', 'route' => 'admin.students.index'],
                ['id' => 'instructors', 'label' => 'Instructors', 'route' => 'admin.instructors.index'],
                ['id' => 'admins', 'label' => 'Administrators', 'route' => 'admin.admins.index', 'super' => true],
                ['id' => 'roles', 'label' => 'Roles & Permissions', 'route' => 'admin.roles.index', 'super' => true],
            ],
        ],
        [
            'title' => 'Orders & Sales',
            'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z',
            'items' => [
                ['id' => 'orders', 'label' => 'All Orders', 'route' => 'admin.orders.index'],
                ['id' => 'coupons', 'label' => 'Coupons', 'route' => 'admin.coupons.index'],
                ['id' => 'plans', 'label' => 'Memberships & Plans', 'route' => 'admin.plans.index'],
            ],
        ],
        [
            'title' => 'Payments & Payouts',
            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'items' => [
                ['id' => 'payment-gateways', 'label' => 'Payment Gateways', 'route' => 'admin.payment-gateways.index'],
                ['id' => 'payouts', 'label' => 'Instructor Payouts', 'route' => 'admin.payouts.index', 'badge' => \App\Models\Payout::where('status', 'pending')->count()],
            ],
        ],
        [
            'title' => 'Reports & CRM',
            'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            'items' => [
                ['id' => 'reports', 'label' => 'Analytics Overview', 'route' => 'admin.reports.index'],
                ['id' => 'leads', 'label' => 'Leads (CRM)', 'route' => 'admin.leads.index'],
            ],
        ],
        [
            'title' => 'Support & Inbound',
            'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
            'items' => [
                ['id' => 'tickets', 'label' => 'Support Tickets', 'route' => 'admin.tickets.index', 'badge' => \App\Models\SupportTicket::where('status', 'open')->count()],
                ['id' => 'messages', 'label' => 'Contact Messages', 'route' => 'admin.messages.index', 'badge' => \App\Models\ContactMessage::where('is_read', false)->count()],
            ],
        ],
        [
            'title' => 'Settings & System',
            'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
            'items' => [
                ['id' => 'settings', 'label' => 'General Settings', 'route' => 'admin.settings.index'],
                ['id' => 'services', 'label' => 'Mail (SMTP) & Storage', 'route' => 'admin.services.index'],
                ['id' => 'languages', 'label' => 'Languages', 'route' => 'admin.languages.index'],
                ['id' => 'blog', 'label' => 'Blog Articles', 'route' => 'admin.blog.index'],
                ['id' => 'system', 'label' => 'System Diagnostics', 'route' => 'admin.system.index'],
            ],
        ],
    ];
@endphp

<nav class="space-y-4 text-xs">
    @foreach($groups as $gIndex => $group)
        @php
            $visibleItems = collect($group['items'])->filter(function ($item) use ($admin) {
                if (! $admin) return false;
                if (!empty($item['super']) && ! $admin->isSuperAdmin()) return false;
                return true;
            })->all();

            $hasActiveChild = collect($visibleItems)->contains(fn ($i) => $i['id'] === $active);
        @endphp

        @if(count($visibleItems) > 0)
            <div class="space-y-1">
                @if(count($visibleItems) === 1 && $group['title'] === 'Overview')
                    <!-- Standalone Dashboard link -->
                    @php $item = $visibleItems[0]; $isActive = ($active === $item['id']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all {{ $isActive ? 'bg-[#344c3d] text-white shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['icon'] }}"/>
                            </svg>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </div>
                    </a>
                @else
                    <!-- Collapsible Accordion Group (Learnify style) -->
                    <div class="rounded-xl overflow-hidden">
                        <button type="button"
                                onclick="const sub = document.getElementById('group-sub-{{ $gIndex }}'); const chev = document.getElementById('chev-{{ $gIndex }}'); sub.classList.toggle('hidden'); chev.classList.toggle('rotate-180');"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/40 font-bold text-[11px] uppercase tracking-wider transition-colors cursor-pointer">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-3.5 h-3.5 shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['icon'] }}"/>
                                </svg>
                                <span class="truncate">{{ $group['title'] }}</span>
                            </div>
                            <svg id="chev-{{ $gIndex }}" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 {{ $hasActiveChild ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="group-sub-{{ $gIndex }}" class="space-y-0.5 pt-0.5 pl-2 {{ $hasActiveChild ? '' : 'hidden' }}">
                            @foreach($visibleItems as $item)
                                @php $isActive = ($active === $item['id']); @endphp
                                <a href="{{ route($item['route']) }}"
                                   class="group flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $isActive ? 'bg-[#344c3d] text-white font-semibold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                    <span class="truncate">{{ $item['label'] }}</span>

                                    @if(!empty($item['badge']) && $item['badge'] > 0)
                                        <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-amber-500/20 text-amber-300' }}">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endforeach
</nav>
