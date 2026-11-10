@props(['active' => 'dashboard', 'admin' => null])

@php
    $admin ??= auth('admin')->user();

    $sections = [
        [
            'heading' => 'MAIN',
            'items' => [
                [
                    'type' => 'link',
                    'id' => 'dashboard',
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
                ],
                [
                    'type' => 'group',
                    'id' => 'group_pages',
                    'title' => 'Pages',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />',
                    'children' => [
                        ['id' => 'homepage', 'label' => 'Home Page Editor', 'route' => 'admin.homepage.index'],
                    ],
                ],
                [
                    'type' => 'link',
                    'id' => 'media',
                    'label' => 'Media Library',
                    'route' => 'admin.media.index',
                    'permission' => 'media.manage',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />',
                ],
                [
                    'type' => 'link',
                    'id' => 'blog',
                    'label' => 'Blog & Articles',
                    'route' => 'admin.blog.index',
                    'permission' => 'blog.manage',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />',
                ],
            ],
        ],
        [
            'heading' => 'COURSES & LEARNING',
            'items' => [
                [
                    'type' => 'group',
                    'id' => 'group_courses',
                    'title' => 'Courses',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />',
                    'children' => [
                        ['id' => 'courses', 'label' => 'All Courses', 'route' => 'admin.courses.index'],
                        ['id' => 'categories', 'label' => 'Categories', 'route' => 'admin.categories.index'],
                        ['id' => 'reviews', 'label' => 'Student Reviews', 'route' => 'admin.reviews.index', 'badge' => \App\Models\Review::where('status', 'pending')->count()],
                        ['id' => 'certificates', 'label' => 'Certificate Templates', 'route' => 'admin.certificates.index'],
                        ['id' => 'events', 'label' => 'Live Webinars & Events', 'route' => 'admin.events.index'],
                    ],
                ],
            ],
        ],
        [
            'heading' => 'MANAGEMENT',
            'items' => [
                [
                    'type' => 'group',
                    'id' => 'group_users',
                    'title' => 'Users & Staff',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
                    'children' => [
                        ['id' => 'users', 'label' => 'All Users Directory', 'route' => 'admin.users.index'],
                        ['id' => 'students', 'label' => 'Students', 'route' => 'admin.students.index'],
                        ['id' => 'instructors', 'label' => 'Instructors', 'route' => 'admin.instructors.index'],
                        ['id' => 'instructor_verifications', 'label' => 'Verifications', 'route' => 'admin.instructors.index', 'params' => ['verification' => 'pending'], 'badge' => \App\Models\Instructor::where('verification_status', 'pending')->count()],
                        ['id' => 'admins', 'label' => 'Administrators', 'route' => 'admin.admins.index', 'super' => true],
                        ['id' => 'roles', 'label' => 'Roles & Permissions', 'route' => 'admin.roles.index', 'super' => true],
                    ],
                ],
            ],
        ],
        [
            'heading' => 'COMMERCE & ORDERS',
            'items' => [
                [
                    'type' => 'group',
                    'id' => 'group_sales',
                    'title' => 'Invoices & Sales',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />',
                    'children' => [
                        ['id' => 'orders', 'label' => 'All Orders & Invoices', 'route' => 'admin.orders.index'],
                        ['id' => 'coupons', 'label' => 'Coupons & Discounts', 'route' => 'admin.coupons.index'],
                        ['id' => 'plans', 'label' => 'Memberships & Plans', 'route' => 'admin.plans.index'],
                    ],
                ],
                [
                    'type' => 'group',
                    'id' => 'group_finance',
                    'title' => 'Finance & Payouts',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'children' => [
                        ['id' => 'payouts', 'label' => 'Instructor Payouts', 'route' => 'admin.payouts.index', 'badge' => \App\Models\Payout::where('status', 'pending')->count()],
                    ],
                ],
            ],
        ],
        [
            'heading' => 'ANALYTICS & DESK',
            'items' => [
                [
                    'type' => 'group',
                    'id' => 'group_reports',
                    'title' => 'Reports & CRM',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />',
                    'children' => [
                        ['id' => 'reports', 'label' => 'Analytics Overview', 'route' => 'admin.reports.index'],
                        ['id' => 'leads', 'label' => 'Leads (CRM)', 'route' => 'admin.leads.index'],
                    ],
                ],
                [
                    'type' => 'group',
                    'id' => 'group_support',
                    'title' => 'Help Desk & Support',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.75.75 0 01-.874-.874 5.972 5.972 0 011.057-3.035C4.03 15.656 3 13.916 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />',
                    'children' => [
                        ['id' => 'tickets', 'label' => 'Support Tickets', 'route' => 'admin.tickets.index', 'badge' => \App\Models\SupportTicket::where('status', 'open')->count()],
                        ['id' => 'messages', 'label' => 'Contact Messages', 'route' => 'admin.messages.index', 'badge' => \App\Models\ContactMessage::where('is_read', false)->count()],
                    ],
                ],
            ],
        ],
        [
            'heading' => 'SYSTEM & SETTINGS',
            'items' => [
                [
                    'type' => 'group',
                    'id' => 'group_settings',
                    'title' => 'Settings',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
                    'children' => [
                        ['id' => 'settings', 'label' => 'General Settings', 'route' => 'admin.settings.index'],
                        ['id' => 'google_settings', 'label' => 'Google Settings', 'route' => 'admin.settings.google'],
                        ['id' => 'payment-gateways', 'label' => 'Payment Gateways', 'route' => 'admin.payment-gateways.index'],
                        ['id' => 'services', 'label' => 'Mail (SMTP) & Storage', 'route' => 'admin.services.index'],
                        ['id' => 'languages', 'label' => 'Languages & Translations', 'route' => 'admin.languages.index'],
                        ['id' => 'ai_settings', 'label' => 'AI Suite & Settings', 'route' => 'admin.settings.ai'],
                        ['id' => 'system', 'label' => 'System Diagnostics', 'route' => 'admin.system.index'],
                    ],
                ],
            ],
        ],
    ];
@endphp

<div class="space-y-4 text-[13px]" data-sidebar-accordion>
    @foreach($sections as $secIndex => $section)
        @php
            $visibleSectionItems = [];
            foreach($section['items'] as $item) {
                if ($item['type'] === 'link') {
                    if (!empty($item['permission']) && ! $admin->hasPermission($item['permission'])) {
                        continue;
                    }
                    if (!empty($item['super']) && ! $admin->isSuperAdmin()) {
                        continue;
                    }
                    $visibleSectionItems[] = $item;
                } else {
                    $filteredChildren = collect($item['children'])->filter(function ($child) use ($admin) {
                        if (! $admin) return false;
                        if (!empty($child['super']) && ! $admin->isSuperAdmin()) return false;
                        if (!empty($child['permission']) && ! $admin->hasPermission($child['permission'])) return false;
                        return true;
                    })->values()->all();

                    if (count($filteredChildren) > 0) {
                        $item['children'] = $filteredChildren;
                        $visibleSectionItems[] = $item;
                    }
                }
            }
        @endphp

        @if(count($visibleSectionItems) > 0)
            <div class="space-y-1">
                <!-- Section Category Header (Figma Dev Mode font) -->
                <div class="px-2.5 pt-2 pb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-[#64748b] select-none">
                    <span>{{ $section['heading'] }}</span>
                </div>

                @foreach($visibleSectionItems as $itemIndex => $item)
                    @if($item['type'] === 'link')
                        @php $isActive = ($active === $item['id']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center gap-2.5 px-2.5 py-2 rounded-[5px] font-medium transition-all duration-150 {{ $isActive ? 'bg-[var(--theme-primary,#344c3d)] text-white shadow-xs font-semibold' : 'text-[#94a3b8] hover:text-white hover:bg-white/[0.05]' }}">
                            <div class="w-4.5 h-4.5 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-[#94a3b8] group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    {!! $item['icon'] !!}
                                </svg>
                            </div>
                            <span class="truncate flex-1 text-[13.5px]">{{ $item['label'] }}</span>
                        </a>
                    @else
                        @php
                            $hasActiveChild = collect($item['children'])->contains(fn ($c) => $c['id'] === $active);
                            $totalBadge = collect($item['children'])->sum(fn ($c) => $c['badge'] ?? 0);
                        @endphp

                        <div class="rounded-[5px] overflow-hidden group/acc {{ $hasActiveChild ? 'is-expanded' : '' }}" data-accordion-item>
                            <!-- Group Toggle Button -->
                            <button type="button"
                                    data-accordion-toggle
                                    aria-expanded="{{ $hasActiveChild ? 'true' : 'false' }}"
                                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-[5px] font-medium transition-all duration-150 cursor-pointer select-none text-left {{ $hasActiveChild ? 'text-white bg-white/[0.04]' : 'text-[#94a3b8] hover:text-white hover:bg-white/[0.05]' }}">
                                <div class="w-4.5 h-4.5 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 {{ $hasActiveChild ? 'text-[var(--theme-accent,#738a6e)]' : 'text-[#94a3b8] group-hover/acc:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        {!! $item['icon'] !!}
                                    </svg>
                                </div>

                                <span class="truncate flex-1 text-[13.5px]">{{ $item['title'] }}</span>

                                @if($totalBadge > 0)
                                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 shrink-0">
                                        {{ $totalBadge }}
                                    </span>
                                @endif

                                <!-- Chevron Indicator (Figma arrow style) -->
                                <svg class="w-3.5 h-3.5 text-[#64748b] transition-transform duration-200 shrink-0 chevron-arrow {{ $hasActiveChild ? 'rotate-180 text-white' : '' }}"
                                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <!-- Submenu List with Connector Track Line -->
                            <div class="submenu-container pl-4 pr-1 py-1 space-y-0.5 transition-all duration-200 {{ $hasActiveChild ? '' : 'hidden' }}">
                                <div class="border-l border-white/[0.08] pl-3 space-y-0.5 my-0.5">
                                    @foreach($item['children'] as $child)
                                        @php
                                            $isChildActive = ($child['id'] === 'instructor_verifications' && request('verification') === 'pending')
                                                || ($child['id'] === 'instructors' && $active === 'instructors' && request('verification') !== 'pending')
                                                || ($child['id'] !== 'instructors' && $child['id'] !== 'instructor_verifications' && $active === $child['id']);
                                        @endphp
                                        <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                           class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-[5px] text-[12.5px] transition-all duration-150 {{ $isChildActive ? 'bg-[var(--theme-primary,#344c3d)] text-white font-semibold shadow-xs' : 'text-[#94a3b8] hover:text-white hover:bg-white/[0.04]' }}">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="w-1.5 h-1.5 rounded-full shrink-0 transition-colors {{ $isChildActive ? 'bg-white' : 'bg-slate-600 group-hover/item:bg-slate-400' }}"></span>
                                                <span class="truncate">{{ $child['label'] }}</span>
                                            </div>

                                            @if(!empty($child['badge']) && $child['badge'] > 0)
                                                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full shrink-0 {{ $isChildActive ? 'bg-white/20 text-white' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                                    {{ $child['badge'] }}
                                                </span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @endforeach
</div>

<script>
    (function() {
        document.querySelectorAll('[data-accordion-toggle]').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const container = this.closest('[data-accordion-item]');
                if (!container) return;
                const submenu = container.querySelector('.submenu-container');
                const chevron = container.querySelector('.chevron-arrow');
                if (!submenu) return;

                const isHidden = submenu.classList.contains('hidden');
                if (isHidden) {
                    submenu.classList.remove('hidden');
                    if (chevron) chevron.classList.add('rotate-180');
                    button.setAttribute('aria-expanded', 'true');
                    container.classList.add('is-expanded');
                } else {
                    submenu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    button.setAttribute('aria-expanded', 'false');
                    container.classList.remove('is-expanded');
                }
            });
        });
    })();
</script>
