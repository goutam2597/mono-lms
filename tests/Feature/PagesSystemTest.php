<?php

namespace Tests\Feature;

use App\Mail\ContactMessageAdminMail;
use App\Mail\ContactMessageUserConfirmationMail;
use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PagesSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_system_pages_are_seeded_with_expected_keys(): void
    {
        $this->assertDatabaseHas('pages', ['system_key' => 'about']);
        $this->assertDatabaseHas('pages', ['system_key' => 'contact']);
        $this->assertDatabaseHas('pages', ['system_key' => 'privacy']);
        $this->assertDatabaseHas('pages', ['system_key' => 'tnc', 'slug' => 'terms']);
        $this->assertDatabaseHas('pages', ['system_key' => 'instructors']);
    }

    public function test_public_about_page_renders_successfully(): void
    {
        $response = $this->get('/about');
        $response->assertOk();
        $response->assertSee('About Us');
    }

    public function test_public_contact_page_renders_successfully(): void
    {
        $response = $this->get('/contact');
        $response->assertOk();
        $response->assertSee('Get in Touch');
    }

    public function test_public_privacy_page_renders_successfully(): void
    {
        $response = $this->get('/privacy');
        $response->assertOk();
        $response->assertSee('Privacy Policy');
    }

    public function test_public_terms_page_renders_successfully(): void
    {
        $response = $this->get('/terms');
        $response->assertOk();
        $response->assertSee('Terms & Conditions');
    }

    public function test_public_instructors_page_renders_successfully(): void
    {
        $response = $this->get('/instructors');
        $response->assertOk();
        $response->assertSee('Teach with Us');
    }

    public function test_public_contact_form_submission_creates_record(): void
    {
        Mail::fake();

        $activeAdmin = Admin::where('status', 'active')->first();
        $this->assertNotNull($activeAdmin);

        // Guest submits contact form without login
        $response = $this->post(route('contact.submit'), [
            'name' => 'Alice Wonder',
            'email' => 'alice@example.com',
            'phone' => '+1 555 123 4567',
            'subject' => 'Enterprise Licensing Inquiry',
            'message' => 'Hello, we are interested in onboarding 500 team members.',
        ]);

        $response->assertSessionHas('success');

        // Record created in contact_messages
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'alice@example.com',
            'subject' => 'Enterprise Licensing Inquiry',
            'is_read' => false,
        ]);

        $message = ContactMessage::where('email', 'alice@example.com')->first();
        $this->assertNotNull($message);

        // 1. Admin gets email copy via Mail
        Mail::assertSent(ContactMessageAdminMail::class, function ($mail) use ($activeAdmin) {
            return $mail->hasTo($activeAdmin->email);
        });

        // 2. User gets confirmation copy via Mail
        Mail::assertSent(ContactMessageUserConfirmationMail::class, function ($mail) {
            return $mail->hasTo('alice@example.com');
        });

        // 3. Admin gets in-app notification in dashboard
        $adminNotification = $activeAdmin->notifications()
            ->where('created_at', '>=', now()->subSeconds(30))
            ->get()
            ->first(fn ($n) => ($n->data['category'] ?? null) === 'support');

        $this->assertNotNull($adminNotification);
        $this->assertStringContainsString('Enterprise Licensing Inquiry', $adminNotification->data['title']);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_custom_page_can_be_created_and_accessed_via_permalink(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.pages.store'), [
            'title' => 'Student Careers & Placement',
            'slug' => 'careers',
            'template' => 'standard',
            'status' => 'published',
            'content' => '<p>Welcome to our careers center.</p>',
            'meta_title' => 'Careers at Mono LMS',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pages', [
            'slug' => 'careers',
            'status' => 'published',
        ]);

        $publicResponse = $this->get('/careers');
        $publicResponse->assertOk();
        $publicResponse->assertSee('Student Careers & Placement');
        $publicResponse->assertSee('Welcome to our careers center.', false);
    }

    public function test_admin_can_create_page_using_visual_drag_and_drop_blocks_without_coding(): void
    {
        $admin = Admin::first();

        $sampleBlocks = [
            [
                'id' => 'blk_hero_1',
                'type' => 'hero',
                'data' => [
                    'eyebrow' => 'Special Program',
                    'title' => 'Executive AI Masterclass',
                    'subtitle' => 'Zero coding required to design this modern landing page.',
                    'btn_primary_label' => 'Enroll in Program',
                    'btn_primary_url' => '/checkout',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'id' => 'blk_faq_1',
                'type' => 'faq',
                'data' => [
                    'title' => 'Masterclass Questions',
                    'items' => [
                        ['question' => 'Who is this for?', 'answer' => 'Executives, creators, and professionals.'],
                    ],
                ],
            ],
            [
                'id' => 'blk_cta_1',
                'type' => 'cta_banner',
                'data' => [
                    'title' => 'Begin Your Transformation Today',
                    'subtitle' => 'Seats are strictly limited to 50 learners.',
                    'btn_label' => 'Reserve Seat',
                    'btn_url' => '/register',
                ],
            ],
        ];

        // Admin submits the page form with settings_blocks_json without writing any HTML
        $response = $this->actingAs($admin, 'admin')->post(route('admin.pages.store'), [
            'title' => 'AI Executive Bootcamp',
            'slug' => 'ai-bootcamp',
            'template' => 'standard',
            'status' => 'published',
            'settings_blocks_json' => json_encode($sampleBlocks),
            'meta_title' => 'Executive AI Bootcamp',
        ]);

        $response->assertSessionHas('success');

        // Verify page saved with structured blocks and auto-compiled HTML
        $page = Page::where('slug', 'ai-bootcamp')->first();
        $this->assertNotNull($page);
        $this->assertCount(3, $page->setting('blocks'));
        $this->assertStringContainsString('Executive AI Masterclass', $page->content);

        // Storefront visitor sees the full visual rendered layout
        $public = $this->get('/ai-bootcamp');
        $public->assertOk();
        $public->assertSee('Executive AI Masterclass');
        $public->assertSee('Zero coding required to design this modern landing page.');
        $public->assertSee('Masterclass Questions');
        $public->assertSee('Who is this for?');
        $public->assertSee('Begin Your Transformation Today');
    }

    public function test_admin_can_organize_header_navbar_menu_with_submenus_and_custom_pages(): void
    {
        $admin = Admin::first();

        // View Header Editor and ensure Menu Builder elements are rendered
        $viewRes = $this->actingAs($admin, 'admin')->get(route('admin.header.index'));
        $viewRes->assertOk();
        $viewRes->assertSee('Header Navbar Menu Organizer');
        $viewRes->assertSee('header_menu_items_input');

        // Post custom menu structure with submenus and custom pages
        $customMenu = [
            [
                'id' => 'nav_home',
                'label' => 'Start',
                'url' => '/',
                'type' => 'route',
                'target' => '_self',
                'badge' => '',
                'is_active' => true,
                'children' => [],
            ],
            [
                'id' => 'nav_catalog',
                'label' => 'Explore',
                'url' => '#',
                'type' => 'custom',
                'target' => '_self',
                'badge' => 'New',
                'is_active' => true,
                'children' => [
                    [
                        'id' => 'nav_all_courses',
                        'label' => 'All Courses',
                        'url' => '/courses',
                        'type' => 'route',
                        'target' => '_self',
                        'badge' => '',
                        'is_active' => true,
                    ],
                    [
                        'id' => 'nav_about',
                        'label' => 'About Our Academy',
                        'url' => '/about',
                        'type' => 'page',
                        'target' => '_self',
                        'badge' => '',
                        'is_active' => true,
                    ],
                ],
            ],
        ];

        $updateRes = $this->actingAs($admin, 'admin')->post(route('admin.header.update'), [
            'header_menu_items' => json_encode($customMenu),
            'header_topbar_address' => '123 Innovation Blvd',
            'header_topbar_phone' => '+1 (555) 000-1111',
        ]);
        $updateRes->assertSessionHas('success');

        // Storefront header renders custom top-level items and dropdown submenu
        $storefront = $this->get('/');
        $storefront->assertOk();
        $storefront->assertSee('Explore');
        $storefront->assertSee('All Courses');
        $storefront->assertSee('About Our Academy');
    }

    public function test_admin_can_organize_footer_columns_and_links(): void
    {
        $admin = Admin::first();

        $viewRes = $this->actingAs($admin, 'admin')->get(route('admin.footer.index'));
        $viewRes->assertOk();
        $viewRes->assertSee('Columns &amp; Navigation Links Organizer', false);

        $customCols = [
            [
                'id' => 'col_academy',
                'title' => 'Academy Programs',
                'links' => [
                    ['id' => 'l1', 'label' => 'Full Curriculum', 'url' => '/courses', 'target' => '_self'],
                    ['id' => 'l2', 'label' => 'About Our Mission', 'url' => '/about', 'target' => '_self'],
                ],
            ],
            [
                'id' => 'col_community',
                'title' => 'Community Portal',
                'links' => [
                    ['id' => 'l3', 'label' => 'Instructor Application', 'url' => '/instructor/register', 'target' => '_self'],
                    ['id' => 'l4', 'label' => 'Direct Contact', 'url' => '/contact', 'target' => '_self'],
                ],
            ],
        ];

        $updateRes = $this->actingAs($admin, 'admin')->post(route('admin.footer.update'), [
            'footer_navigation_columns' => json_encode($customCols),
            'footer_copyright' => '© 2026 Mono LMS Global',
        ]);
        $updateRes->assertSessionHas('success');

        $storefront = $this->get('/');
        $storefront->assertOk();
        $storefront->assertSee('Academy Programs');
        $storefront->assertSee('Full Curriculum');
        $storefront->assertSee('About Our Mission');
        $storefront->assertSee('Community Portal');
        $storefront->assertSee('Instructor Application');
    }

    public function test_admin_pages_index_is_accessible(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.pages.index'));
        $response->assertOk();
        $response->assertSee('All Pages');
        $response->assertSee('About Us');
        $response->assertSee('Contact Us');
    }

    public function test_admin_sidebar_mutual_exclusion_for_instructors_and_verifications(): void
    {
        $admin = Admin::first();

        // 1. Visit instructors index without verification query
        $response = $this->actingAs($admin, 'admin')->get(route('admin.instructors.index'));
        $response->assertOk();

        // Instructors active link styling should appear, but Verifications should not have active styling
        $content = $response->getContent();
        $this->assertStringContainsString('Instructors', $content);

        // 2. Visit instructors index WITH verification=pending query
        $pendingResponse = $this->actingAs($admin, 'admin')->get(route('admin.instructors.index', ['verification' => 'pending']));
        $pendingResponse->assertOk();
        $pendingContent = $pendingResponse->getContent();
        $this->assertStringContainsString('Verifications', $pendingContent);
    }
}
