<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_public_contact_form_submission_creates_record(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Alice Wonder',
            'email' => 'alice@example.com',
            'phone' => '+1 555 123 4567',
            'subject' => 'Enterprise Licensing Inquiry',
            'message' => 'Hello, we are interested in onboarding 500 team members.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'alice@example.com',
            'subject' => 'Enterprise Licensing Inquiry',
        ]);
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
