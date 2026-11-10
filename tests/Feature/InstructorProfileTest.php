<?php

namespace Tests\Feature;

use App\Models\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstructorProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_update_all_profile_details_including_avatar(): void
    {
        Storage::fake('public');

        $instructor = Instructor::create([
            'name' => 'Original Instructor',
            'email' => 'instructor.orig@mono-lms.test',
            'password' => 'password',
            'phone' => '123456789',
            'designation' => 'Junior Instructor',
            'bio' => 'Teaching basic subjects.',
            'status' => 'active',
            'verification_status' => 'approved',
        ]);

        $this->actingAs($instructor, 'instructor');

        $page = $this->get(route('instructor.profile'));
        $page->assertOk();
        $page->assertSee('Profile Photo');
        $page->assertSee('Original Instructor');
        // Notification bell should NOT point to admin messages in instructor panel
        $page->assertDontSee(route('admin.messages.index'));

        // Upload avatar & update all fields
        $avatar = UploadedFile::fake()->image('headshot.jpg', 300, 300);

        $response = $this->put(route('instructor.profile.update'), [
            'name' => 'Dr Original Instructor Updated',
            'email' => 'instructor.new@mono-lms.test',
            'phone' => '+1 555 987 6543',
            'designation' => 'Principal AI Researcher & Professor',
            'bio' => 'Over 15 years experience coaching industry professionals.',
            'avatar' => $avatar,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $instructor->refresh();
        $this->assertSame('Dr Original Instructor Updated', $instructor->name);
        $this->assertSame('instructor.new@mono-lms.test', $instructor->email);
        $this->assertSame('+1 555 987 6543', $instructor->phone);
        $this->assertSame('Principal AI Researcher & Professor', $instructor->designation);
        $this->assertSame('Over 15 years experience coaching industry professionals.', $instructor->bio);
        $this->assertNotNull($instructor->avatar);

        Storage::disk('public')->assertExists($instructor->avatar);

        // Remove avatar
        $removeResp = $this->put(route('instructor.profile.update'), [
            'name' => $instructor->name,
            'email' => $instructor->email,
            'remove_avatar' => '1',
        ]);

        $removeResp->assertRedirect();
        $instructor->refresh();
        $this->assertNull($instructor->avatar);
    }

    public function test_instructor_verification_page_is_accessible_and_full_width(): void
    {
        $instructor = Instructor::create([
            'name' => 'FullWidth Tester',
            'email' => 'fullwidth@test.dev',
            'password' => 'password',
            'status' => 'pending_verification',
            'verification_status' => 'unverified',
        ]);

        $this->actingAs($instructor, 'instructor');

        $page = $this->get(route('instructor.verification.show'));
        $page->assertOk();
        // Verification sidebar label should be 'Verification'
        $page->assertSee('Verification');
        $page->assertDontSee('max-w-4xl mx-auto');
        $page->assertDontSee(route('admin.messages.index'));
    }
}
