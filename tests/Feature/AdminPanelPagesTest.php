<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Role;
use App\Models\Setting;
use App\Models\SupportTicket;
use App\Services\HomePageContent;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelPagesTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $superRole = Role::where('slug', 'super-admin')->first();

        $this->admin = Admin::create([
            'role_id' => $superRole?->id,
            'name' => 'Administrator',
            'email' => 'admin@learnify.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_login_with_provided_credentials(): void
    {
        $this->post(route('login.attempt'), [
            'email' => 'admin@learnify.test',
            'password' => '12345678',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_all_sidebar_pages_are_accessible_and_render_cleanly(): void
    {
        $this->actingAs($this->admin, 'admin');

        // 1. Dashboard & Pages dropdown
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin overview')
            ->assertSee('Welcome back, Administrator')
            ->assertSee('Pages')
            ->assertSee('Home Page');

        // 2. Courses, Categories, Reviews, Certificates, Events
        $this->get(route('admin.courses.index'))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.reviews.index'))->assertOk();
        $this->get(route('admin.certificates.index'))->assertOk();
        $this->get(route('admin.events.index'))->assertOk();

        // 3. User Directory & Roles
        $this->get(route('admin.users.index'))->assertOk()->assertSee('User Directory');
        $this->get(route('admin.students.index'))->assertOk();
        $this->get(route('admin.instructors.index'))->assertOk();
        $this->get(route('admin.admins.index'))->assertOk();
        $this->get(route('admin.roles.index'))->assertOk();

        // 4. Orders, Coupons, Plans
        $this->get(route('admin.orders.index'))->assertOk()->assertSee('Orders & Sales');
        $this->get(route('admin.coupons.index'))->assertOk()->assertSee('Coupons & Discounts');
        $this->get(route('admin.plans.index'))->assertOk();

        // 5. Payment Gateways & Payouts
        $this->get(route('admin.payment-gateways.index'))->assertOk()->assertSee('Stripe');
        $this->get(route('admin.payouts.index'))->assertOk()->assertSee('Instructor Payouts');

        // 6. Reports & Leads
        $this->get(route('admin.reports.index'))->assertOk()->assertSee('Reports & Analytics', false);
        $this->get(route('admin.leads.index'))->assertOk()->assertSee('Leads & CRM Pipeline', false);

        // 7. Support & Messages
        $this->get(route('admin.tickets.index'))->assertOk()->assertSee('Support Help Desk');
        $this->get(route('admin.messages.index'))->assertOk()->assertSee('Inbound Messages');

        // 8. Settings, Services, System
        $this->get(route('admin.settings.index'))->assertOk()->assertSee('Platform Settings');
        $this->get(route('admin.settings.google'))->assertOk()->assertSee('Google & Firebase Settings');
        $this->get(route('admin.homepage.index'))->assertOk()->assertSee('Home Page Editor');
        $this->get(route('admin.services.index'))->assertOk()->assertSee('Outgoing Mail (SMTP)');
        $this->get(route('admin.languages.index'))->assertOk();
        $this->get(route('admin.system.index'))->assertOk()->assertSee('Environment Diagnostics');
    }

    public function test_coupon_crud_functionality(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Store
        $this->post(route('admin.coupons.store'), [
            'code' => 'TESTCODE50',
            'title' => 'Test Promo 50%',
            'discount_type' => 'percent',
            'discount_value' => 50,
            'max_uses' => 10,
        ])->assertRedirect();

        $coupon = Coupon::where('code', 'TESTCODE50')->first();
        $this->assertNotNull($coupon);
        $this->assertTrue($coupon->is_active);

        // Toggle
        $this->patch(route('admin.coupons.toggle', $coupon))->assertRedirect();
        $this->assertFalse($coupon->fresh()->is_active);

        // Destroy
        $this->delete(route('admin.coupons.destroy', $coupon))->assertRedirect();
        $this->assertDatabaseMissing('coupons', ['code' => 'TESTCODE50']);
    }

    public function test_orders_status_update_functionality(): void
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::create([
            'order_number' => 'TEST-ORD-001',
            'amount' => 199.00,
            'payment_status' => 'pending',
            'payment_method' => 'stripe',
        ]);

        $this->patch(route('admin.orders.status', $order), [
            'payment_status' => 'paid',
        ])->assertRedirect();

        $this->assertEquals('paid', $order->fresh()->payment_status);

        // Can view detail page
        $this->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('TEST-ORD-001');
    }

    public function test_system_cache_clear_action(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->post(route('admin.system.clear-cache'))
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    public function test_admin_can_update_color_stack_theme_settings(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Core Color Stack & Appearance', false)
            ->assertSee('Mono Sage')
            ->assertSee('theme_primary');

        $this->post(route('admin.settings.update'), [
            'site_name' => 'Mono LMS Customized',
            'theme_primary' => '#065f46',
            'theme_primary_hover' => '#044e39',
            'theme_accent' => '#10b981',
            'theme_dark' => '#064e3b',
            'theme_bg' => '#f0fdf4',
        ])->assertRedirect();

        $this->assertEquals('#065f46', Setting::get('theme_primary'));
        $this->assertEquals('#044e39', Setting::get('theme_primary_hover'));
        $this->assertEquals('#10b981', Setting::get('theme_accent'));
        $this->assertEquals('#064e3b', Setting::get('theme_dark'));
        $this->assertEquals('#f0fdf4', Setting::get('theme_bg'));

        // Check dashboard reflects the injected dynamic theme vars
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('--theme-primary: #065f46', false);
    }

    public function test_admin_can_update_footer_payment_methods(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Footer Payment Methods')
            ->assertSee('footer_payment_methods')
            ->assertSee('footer_payment_methods_width');

        $this->post(route('admin.settings.update'), [
            'footer_payment_methods' => 'media/custom-gateways.png',
            'footer_payment_methods_enabled' => '1',
            'footer_payment_methods_width' => '420px',
        ])->assertRedirect();

        $this->assertEquals('media/custom-gateways.png', Setting::get('footer_payment_methods'));
        $this->assertEquals('1', Setting::get('footer_payment_methods_enabled'));
        $this->assertEquals('420px', Setting::get('footer_payment_methods_width'));

        $this->get('/')
            ->assertOk()
            ->assertSee('max-width: 420px', false);

        // Disabling it hides payment methods from footer
        $this->post(route('admin.settings.update'), [
            // No footer_payment_methods_enabled passed (unchecked)
        ])->assertRedirect();

        $this->assertEquals('0', Setting::get('footer_payment_methods_enabled'));

        $this->get('/')
            ->assertOk()
            ->assertDontSee('payment-methods.png')
            ->assertDontSee('custom-gateways.png');
    }

    public function test_admin_can_update_mobile_app_store_links(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Can view the setting fields on the admin settings page
        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Mobile App Store Links')
            ->assertSee('app_store_url')
            ->assertSee('play_store_url')
            ->assertSee('app_store_enabled')
            ->assertSee('play_store_enabled');

        // Admin updates custom App Store and Play Store links and enables them
        $this->post(route('admin.settings.update'), [
            'app_store_url' => 'https://apps.apple.com/app/mono-lms-pro/id999888777',
            'play_store_url' => 'https://play.google.com/store/apps/details?id=com.monolms.pro',
            'app_store_enabled' => '1',
            'play_store_enabled' => '1',
        ])->assertRedirect();

        $this->assertEquals('https://apps.apple.com/app/mono-lms-pro/id999888777', Setting::get('app_store_url'));
        $this->assertEquals('https://play.google.com/store/apps/details?id=com.monolms.pro', Setting::get('play_store_url'));
        $this->assertEquals('1', Setting::get('app_store_enabled'));
        $this->assertEquals('1', Setting::get('play_store_enabled'));

        // Public storefront footer now displays both custom links
        $this->get('/')
            ->assertOk()
            ->assertSee('https://apps.apple.com/app/mono-lms-pro/id999888777', false)
            ->assertSee('https://play.google.com/store/apps/details?id=com.monolms.pro', false)
            ->assertSee('app-store-badge.svg')
            ->assertSee('google-play-badge.svg');

        // Disabling App Store hides it from the footer while keeping Play Store
        $this->post(route('admin.settings.update'), [
            'app_store_url' => 'https://apps.apple.com/app/mono-lms-pro/id999888777',
            'play_store_url' => 'https://play.google.com/store/apps/details?id=com.monolms.pro',
            // app_store_enabled omitted (unchecked)
            'play_store_enabled' => '1',
        ])->assertRedirect();

        $this->assertEquals('0', Setting::get('app_store_enabled'));
        $this->assertEquals('1', Setting::get('play_store_enabled'));

        $this->get('/')
            ->assertOk()
            ->assertDontSee('https://apps.apple.com/app/mono-lms-pro/id999888777')
            ->assertSee('https://play.google.com/store/apps/details?id=com.monolms.pro', false);
    }

    public function test_admin_can_update_homepage_banner_strip_width(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.homepage.index'))
            ->assertOk()
            ->assertSee('Strip / Container Width', false);

        $this->put(route('admin.homepage.update'), [
            'sections' => [
                'banner_instructor' => [
                    'enabled' => '1',
                    'heading' => 'Become An Instructor Today',
                    'description' => 'Teach millions of students.',
                    'button_text' => 'Join Now',
                    'button_url' => '/instructor/register',
                    'width' => 'full',
                ],
            ],
        ])->assertRedirect();

        $homeContent = app(HomePageContent::class);
        $section = $homeContent->section('banner_instructor');
        $this->assertSame('full', $section['width']);

        $this->get('/')
            ->assertOk()
            ->assertSee('w-full my-16 px-4 sm:px-8 lg:px-12', false);
    }

    public function test_media_picker_and_modals_rendered_on_admin_pages(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Settings page has Choose from Media button and global media picker modal
        $this->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('mediap-modal', false)
            ->assertSee('window.mediaPicker', false)
            ->assertSee('modal-overlay', false);

        // Certificate templates renders createCertModal modal overlay
        $this->get(route('admin.certificates.index'))
            ->assertOk()
            ->assertSee('id="createCertModal"', false)
            ->assertSee('modal-overlay', false);

        // Coupons renders createCouponModal modal overlay
        $this->get(route('admin.coupons.index'))
            ->assertOk()
            ->assertSee('id="createCouponModal"', false)
            ->assertSee('modal-overlay', false);

        // Users page renders user-modal modal overlay
        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('id="user-modal"', false)
            ->assertSee('modal-overlay', false);

        // Support tickets renders reply form with properly structured inputs
        SupportTicket::create([
            'ticket_number' => 'TCK-9999',
            'user_name' => 'Eugene Test',
            'user_email' => 'eugene@test.com',
            'subject' => 'Test buffer issue',
            'priority' => 'high',
            'status' => 'open',
            'message' => 'Video buffers',
        ]);

        $this->get(route('admin.tickets.index'))
            ->assertOk()
            ->assertSee('Type staff response...', false)
            ->assertSee('data-mselect', false);
    }

    public function test_custom_favicon_and_logo_rendered_in_head_and_navigation(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Set custom favicon and logo
        Setting::set('site_favicon', 'media/2026/11/custom-favicon.png');
        Setting::set('site_logo', 'media/2026/11/custom-logo.png');

        $faviconUrl = Setting::faviconUrl();
        $this->assertStringContainsString('custom-favicon.png', $faviconUrl);

        $logoUrl = Setting::logoUrl();
        $this->assertStringContainsString('custom-logo.png', $logoUrl);

        // Dashboard renders the custom favicon link in <head>
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('<link rel="icon" href="'.$faviconUrl.'">', false)
            ->assertSee('<link rel="shortcut icon" href="'.$faviconUrl.'">', false);

        // Storefront home renders the custom favicon and logo
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<link rel="icon" href="'.$faviconUrl.'">', false)
            ->assertSee($logoUrl, false);
    }

    public function test_admin_avatar_rendered_in_navbar_and_sidebar_bottom(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Without avatar: renders initial badges
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Administrator')
            ->assertSee('Super Admin');

        // With avatar: renders proper resolved URL in both header and sidebar
        $this->admin->update(['avatar' => 'media/2026/11/super-admin.png']);
        $avatarUrl = $this->admin->avatarUrl();

        $response = $this->get(route('admin.dashboard'))->assertOk();
        $response->assertSee($avatarUrl, false);
    }

    public function test_header_profile_dropdown_and_edit_profile_page_are_accessible(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('header-user-menu-btn', false)
            ->assertSee('header-user-menu-dropdown', false)
            ->assertSee(route('admin.profile'), false);

        $this->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Account Settings')
            ->assertSee('Personal Information')
            ->assertSee('Security')
            ->assertSee($this->admin->email);
    }

    public function test_admin_can_update_profile_information(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->put(route('admin.profile.update'), [
            'name' => 'Updated Admin Name',
            'email' => 'updatedadmin@learnify.test',
            'phone' => '+1 234 567 8901',
            'locale' => 'en',
        ])->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertSame('Updated Admin Name', $this->admin->name);
        $this->assertSame('updatedadmin@learnify.test', $this->admin->email);
        $this->assertSame('+1 234 567 8901', $this->admin->phone);
    }

    public function test_admin_can_update_password(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->put(route('admin.profile.password'), [
            'current_password' => '12345678',
            'password' => 'newsecretpassword123',
            'password_confirmation' => 'newsecretpassword123',
        ])->assertSessionHas('success');

        $this->assertTrue(auth('admin')->attempt([
            'email' => $this->admin->email,
            'password' => 'newsecretpassword123',
        ]));
    }

    public function test_admin_can_update_google_and_firebase_settings(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.settings.google'))
            ->assertOk()
            ->assertSee('Google & Firebase Settings')
            ->assertSee('Firebase Project ID')
            ->assertSee('FCM Server Key');

        $response = $this->post(route('admin.settings.google.store'), [
            'google_login_enabled' => '1',
            'google_client_id' => 'test-client-id.apps.googleusercontent.com',
            'google_client_secret' => 'test-client-secret-12345',
            'google_allow_student' => '1',
            'google_allow_instructor' => '1',
            'firebase_enabled' => '1',
            'firebase_project_id' => 'learnify-mobile-app',
            'firebase_api_key' => 'AIzaSyFakeApiKey1234567890',
            'firebase_auth_domain' => 'learnify-mobile-app.firebaseapp.com',
            'firebase_messaging_sender_id' => '109876543210',
            'firebase_app_id' => '1:109876543210:web:abcdef123456',
            'firebase_fcm_server_key' => 'AAAAFakeFcmServerKey123',
        ]);

        $response->assertSessionHas('success');
        $this->assertSame('learnify-mobile-app', Setting::get('firebase_project_id'));
        $this->assertSame('AIzaSyFakeApiKey1234567890', Setting::get('firebase_api_key'));
        $this->assertTrue(Setting::enabled('firebase_enabled'));
    }

    public function test_admin_can_upload_and_destroy_firebase_admin_sdk_service_account(): void
    {
        Storage::fake('local');
        $this->actingAs($this->admin, 'admin');

        $fakeServiceAccount = [
            'type' => 'service_account',
            'project_id' => 'mono-lms-push-project',
            'private_key_id' => 'privkey123456',
            'private_key' => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCfake\n-----END PRIVATE KEY-----\n",
            'client_email' => 'firebase-adminsdk-fcm@mono-lms-push-project.iam.gserviceaccount.com',
            'client_id' => '1092837465',
        ];

        $uploadedFile = UploadedFile::fake()->createWithContent(
            'mono-lms-firebase-adminsdk.json',
            json_encode($fakeServiceAccount)
        );

        $response = $this->post(route('admin.settings.google.store'), [
            'firebase_enabled' => '1',
            'firebase_service_account_file' => $uploadedFile,
        ]);

        $response->assertSessionHas('success');
        Storage::disk('local')->assertExists('firebase/service-account.json');
        $this->assertSame('firebase/service-account.json', Setting::get('firebase_service_account_path'));
        $this->assertSame('mono-lms-firebase-adminsdk.json', Setting::get('firebase_service_account_filename'));
        $this->assertSame('firebase-adminsdk-fcm@mono-lms-push-project.iam.gserviceaccount.com', Setting::get('firebase_service_account_client_email'));
        $this->assertSame('mono-lms-push-project', Setting::get('firebase_service_account_project_id'));

        // View page now shows configured state
        $this->get(route('admin.settings.google'))
            ->assertOk()
            ->assertSee('Configured')
            ->assertSee('firebase-adminsdk-fcm@mono-lms-push-project.iam.gserviceaccount.com');

        // Admin can destroy/remove the service account key
        $deleteResponse = $this->delete(route('admin.settings.google.firebase_service_account.destroy'));
        $deleteResponse->assertSessionHas('success');
        Storage::disk('local')->assertMissing('firebase/service-account.json');
        $this->assertSame('', Setting::get('firebase_service_account_path'));
    }

    public function test_admin_upload_rejects_invalid_firebase_service_account(): void
    {
        Storage::fake('local');
        $this->actingAs($this->admin, 'admin');

        $invalidFile = UploadedFile::fake()->createWithContent(
            'invalid.json',
            json_encode(['random_key' => 'not_a_service_account'])
        );

        $response = $this->post(route('admin.settings.google.store'), [
            'firebase_service_account_file' => $invalidFile,
        ]);

        $response->assertSessionHasErrors('firebase_service_account_file');
    }

    public function test_support_tickets_full_crud_and_reply_flow(): void
    {
        $this->actingAs($this->admin, 'admin');

        // 1. Create a ticket
        $createRes = $this->post(route('admin.tickets.store'), [
            'user_name' => 'Alice Student',
            'user_email' => 'alice@learnify.test',
            'subject' => 'Issue accessing quiz module',
            'priority' => 'high',
            'status' => 'open',
            'message' => 'The quiz submit button is not responsive on lesson 3.',
        ]);
        $createRes->assertSessionHas('success');

        $ticket = SupportTicket::where('user_email', 'alice@learnify.test')->first();
        $this->assertNotNull($ticket);
        $this->assertStringStartsWith('TK-', $ticket->ticket_number);
        $this->assertSame('high', $ticket->priority);
        $this->assertSame('open', $ticket->status);

        // 2. View ticket in list with filters and search
        $this->get(route('admin.tickets.index', ['search' => 'quiz module']))
            ->assertOk()
            ->assertSee($ticket->ticket_number)
            ->assertSee('Alice Student')
            ->assertSee('Issue accessing quiz module');

        // 3. Reply to ticket
        $replyRes = $this->patch(route('admin.tickets.reply', $ticket), [
            'reply' => 'We have refreshed the quiz component for lesson 3. Please try again.',
            'status' => 'resolved',
            'send_email' => '0',
        ]);
        $replyRes->assertSessionHas('success');

        $ticket->refresh();
        $this->assertSame('resolved', $ticket->status);
        $this->assertSame('We have refreshed the quiz component for lesson 3. Please try again.', $ticket->reply);
        $this->assertNotNull($ticket->replied_at);

        // 4. Update ticket priority
        $this->patch(route('admin.tickets.update', $ticket), [
            'priority' => 'urgent',
        ])->assertSessionHas('success');

        $ticket->refresh();
        $this->assertSame('urgent', $ticket->priority);

        // 5. Bulk action (change status to closed)
        $this->post(route('admin.tickets.bulk-action'), [
            'action' => 'status_closed',
            'ids' => [$ticket->id],
        ])->assertSessionHas('success');

        $ticket->refresh();
        $this->assertSame('closed', $ticket->status);

        // 6. Delete ticket
        $this->delete(route('admin.tickets.destroy', $ticket))->assertSessionHas('success');
        $this->assertDatabaseMissing('support_tickets', ['id' => $ticket->id]);
    }

    public function test_contact_messages_full_crud_reply_and_export_flow(): void
    {
        $this->actingAs($this->admin, 'admin');

        // 1. Create a contact message
        $msg = ContactMessage::create([
            'name' => 'Dr. Robert Miller',
            'email' => 'robert.miller@enterprise.test',
            'phone' => '+1 555 8899',
            'subject' => 'Enterprise LMS License Inquiry',
            'message' => 'We would like to license Mono LMS for 500 medical staff.',
            'is_read' => false,
        ]);

        // 2. View list and tabs
        $this->get(route('admin.messages.index', ['tab' => 'unread']))
            ->assertOk()
            ->assertSee('Dr. Robert Miller')
            ->assertSee('Enterprise LMS License Inquiry');

        // 3. Toggle read status
        $this->patch(route('admin.messages.toggle', $msg))->assertSessionHas('success');
        $msg->refresh();
        $this->assertTrue($msg->is_read);

        // 4. Reply to contact message
        $replyRes = $this->post(route('admin.messages.reply', $msg), [
            'subject' => 'Re: Enterprise LMS Inquiry',
            'reply' => 'Thank you Dr. Miller. Our enterprise solutions manager will contact you.',
            'send_email' => '0',
        ]);
        $replyRes->assertSessionHas('success');

        $msg->refresh();
        $this->assertSame('Thank you Dr. Miller. Our enterprise solutions manager will contact you.', $msg->reply);
        $this->assertNotNull($msg->replied_at);
        $this->assertTrue($msg->isReplied());

        // 5. Export messages to CSV
        $exportRes = $this->get(route('admin.messages.export'));
        $exportRes->assertOk();
        $exportRes->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // 6. Bulk action (mark unread)
        $this->post(route('admin.messages.bulk-action'), [
            'action' => 'mark_unread',
            'ids' => [$msg->id],
        ])->assertSessionHas('success');

        $msg->refresh();
        $this->assertFalse($msg->is_read);

        // 7. Delete message
        $this->delete(route('admin.messages.destroy', $msg))->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', ['id' => $msg->id]);
    }
}
