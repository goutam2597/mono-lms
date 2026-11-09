<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    private Student $student;

    private Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('membership_enabled', '1');

        $this->student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->plan = Plan::create([
            'name' => 'Pro Plan',
            'slug' => 'pro-plan',
            'description' => 'Unlimited access to all courses',
            'price' => 29.00,
            'billing_period' => 'monthly',
            'features' => ['All courses', 'Certificate of completion'],
            'is_active' => true,
            'is_popular' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_guest_can_view_membership_page_when_enabled(): void
    {
        $response = $this->get(route('membership.index'));

        $response->assertStatus(200);
        $response->assertSee('Pro Plan');
        $response->assertSee(route('login'));
    }

    public function test_membership_page_returns_404_when_disabled(): void
    {
        Setting::set('membership_enabled', '0');

        $response = $this->get(route('membership.index'));

        $response->assertStatus(404);
    }

    public function test_authenticated_student_can_view_membership_page(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->get(route('membership.index'));

        $response->assertStatus(200);
        $response->assertSee('Pro Plan');
        $response->assertSee(route('membership.subscribe', $this->plan));
    }

    public function test_authenticated_student_with_active_subscription_sees_current_plan(): void
    {
        Subscription::create([
            'student_id' => $this->student->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('membership.index'));

        $response->assertStatus(200);
        $response->assertSee(__('membership.current_plan'));
    }

    public function test_student_can_subscribe_to_a_plan(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->post(route('membership.subscribe', $this->plan));

        $response->assertRedirect(route('membership.index'));

        $this->assertDatabaseHas('subscriptions', [
            'student_id' => $this->student->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
        ]);

        $this->assertTrue($this->student->isSubscribed());
        $this->assertEquals($this->plan->id, $this->student->activeSubscription()?->plan_id);
    }

    public function test_subscribing_cancels_previous_active_subscription(): void
    {
        $oldPlan = Plan::create([
            'name' => 'Basic Plan',
            'slug' => 'basic-plan',
            'price' => 15.00,
            'billing_period' => 'monthly',
            'is_active' => true,
        ]);

        $oldSubscription = Subscription::create([
            'student_id' => $this->student->id,
            'plan_id' => $oldPlan->id,
            'status' => 'active',
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->addDays(5),
        ]);

        $this->actingAs($this->student, 'student')
            ->post(route('membership.subscribe', $this->plan));

        $this->assertEquals('cancelled', $oldSubscription->fresh()->status);

        $this->assertDatabaseHas('subscriptions', [
            'student_id' => $this->student->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
        ]);
    }
}
