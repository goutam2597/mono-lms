<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstructorVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::set('otp_signup_enabled', '0');
    }

    public function test_newly_registered_instructor_is_not_active_and_cannot_create_courses(): void
    {
        // 1. Instructor registers
        $resp = $this->post(route('instructor.register.attempt'), [
            'name' => 'Prof Charles',
            'email' => 'charles@teach.dev',
            'password' => 'secret1234',
            'designation' => 'Professor of Genetics',
            'terms' => '1',
        ]);

        $resp->assertRedirect(route('instructor.dashboard'));

        $instructor = Instructor::where('email', 'charles@teach.dev')->first();
        $this->assertNotNull($instructor);
        $this->assertSame('pending_verification', $instructor->status);
        $this->assertSame('unverified', $instructor->verification_status);
        $this->assertFalse($instructor->isVerified());
        $this->assertFalse($instructor->canAddCourses());

        // 2. Instructor cannot appear in public directory
        $grid = $this->get(route('instructors.index'));
        $grid->assertOk();
        $grid->assertDontSee('Prof Charles');

        // 3. Instructor cannot access course creation form
        $this->actingAs($instructor, 'instructor');
        $createAttempt = $this->get(route('instructor.courses.create'));
        $createAttempt->assertRedirect(route('instructor.verification.show'));
        $createAttempt->assertSessionHas('error');

        // 4. Instructor cannot store courses
        $category = Category::create(['name' => 'Design', 'slug' => 'design']);
        $storeAttempt = $this->post(route('instructor.courses.store'), [
            'title' => 'Unapproved Course',
            'category_id' => $category->id,
            'price' => 50,
        ]);
        $storeAttempt->assertRedirect(route('instructor.verification.show'));
        $storeAttempt->assertSessionHas('error');
    }

    public function test_instructor_submits_verification_documents(): void
    {
        Storage::fake('public');

        $instructor = Instructor::create([
            'name' => 'Dr Jean Grey',
            'email' => 'jean@teach.dev',
            'password' => 'secret1234',
            'status' => 'pending_verification',
            'verification_status' => 'unverified',
        ]);

        $this->actingAs($instructor, 'instructor');

        $cert = UploadedFile::fake()->create('diploma.pdf', 500, 'application/pdf');
        $id = UploadedFile::fake()->image('national-id.jpg');

        $resp = $this->post(route('instructor.verification.submit'), [
            'certification_file' => $cert,
            'national_id_file' => $id,
        ]);

        $resp->assertRedirect(route('instructor.verification.show'));
        $resp->assertSessionHas('status');

        $instructor->refresh();
        $this->assertSame('pending', $instructor->verification_status);
        $this->assertNotNull($instructor->certification_file);
        $this->assertNotNull($instructor->national_id_file);

        Storage::disk('public')->assertExists($instructor->certification_file);
        Storage::disk('public')->assertExists($instructor->national_id_file);
    }

    public function test_admin_approves_instructor_verification(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Boss',
            'email' => 'admin@test.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $instructor = Instructor::create([
            'name' => 'Hank McCoy',
            'email' => 'hank@teach.dev',
            'password' => 'password',
            'status' => 'pending_verification',
            'verification_status' => 'pending',
            'certification_file' => 'verifications/certificates/dummy.pdf',
            'national_id_file' => 'verifications/national_ids/dummy.jpg',
        ]);

        $this->actingAs($admin, 'admin');

        $resp = $this->post(route('admin.instructors.approve-verification', $instructor));
        $resp->assertRedirect();
        $resp->assertSessionHas('success');

        $instructor->refresh();
        $this->assertSame('active', $instructor->status);
        $this->assertSame('approved', $instructor->verification_status);
        $this->assertNotNull($instructor->verified_at);
        $this->assertTrue($instructor->isVerified());
        $this->assertTrue($instructor->canAddCourses());

        // Now instructor appears in public directory
        $grid = $this->get(route('instructors.index'));
        $grid->assertOk();
        $grid->assertSee('Hank McCoy');
    }

    public function test_admin_rejects_instructor_verification(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Boss',
            'email' => 'admin@test.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $instructor = Instructor::create([
            'name' => 'Erik Lehnsherr',
            'email' => 'erik@teach.dev',
            'password' => 'password',
            'status' => 'pending_verification',
            'verification_status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin');

        $resp = $this->post(route('admin.instructors.reject-verification', $instructor), [
            'rejection_reason' => 'National ID photo was blurry and expired.',
        ]);

        $resp->assertRedirect();
        $resp->assertSessionHas('success');

        $instructor->refresh();
        $this->assertSame('inactive', $instructor->status);
        $this->assertSame('rejected', $instructor->verification_status);
        $this->assertSame('National ID photo was blurry and expired.', $instructor->rejection_reason);
    }
}
