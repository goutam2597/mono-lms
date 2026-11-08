<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Role;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        // 1. Dashboard
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin overview')
            ->assertSee('Welcome back, Administrator');

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
}
