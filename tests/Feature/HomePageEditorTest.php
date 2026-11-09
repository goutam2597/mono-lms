<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use App\Services\HomePageContent;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageEditorTest extends TestCase
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
            'email' => 'admin@mono-lms.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_guest_cannot_access_home_page_editor(): void
    {
        $this->get(route('admin.homepage.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_home_page_editor_and_see_all_sections(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.homepage.index'));

        $response->assertOk();
        $response->assertSee('Home Page Editor');
        $response->assertSee('Hero Section');
        $response->assertSee('Statistics Ribbon');
        $response->assertSee('Top Categories');
        $response->assertSee('Featured Courses Grid');
        $response->assertSee('Transform Access Banner');
        $response->assertSee('Goals & Impact Metrics', false);
        $response->assertSee('Accelerate Cloud Success');
        $response->assertSee('Trending Courses');
        $response->assertSee('Instructors Showcase');
        $response->assertSee('Become An Instructor CTA Banner');
        $response->assertSee('Partner Universities & Companies', false);
        $response->assertSee('Student Testimonials');
        $response->assertSee('Latest News & Events', false);
    }

    public function test_admin_can_save_homepage_sections(): void
    {
        $payload = [
            'sections' => [
                'hero' => [
                    'enabled' => '1',
                    'badge' => 'CUSTOM HERO BADGE 2026',
                    'title_line1' => 'Transformative Learning',
                    'title_highlight' => 'Mastery',
                    'title_line2' => 'for Innovators',
                    'subtitle' => 'Customized LMS Hero subtitle for forward thinkers.',
                    'avatar_image' => 'images/custom-hero.webp',
                ],
                'banner_transform' => [
                    'enabled' => '1',
                    'heading' => 'Elevate Your Enterprise Skillset Today',
                    'description' => 'Personalized learning solutions built for your career.',
                    'button_text' => 'Explore All Catalogues',
                    'button_url' => '/custom-catalogues',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.homepage.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $content = app(HomePageContent::class)->all();

        $this->assertSame('CUSTOM HERO BADGE 2026', $content['hero']['badge']);
        $this->assertSame('Transformative Learning', $content['hero']['title_line1']);
        $this->assertSame('Mastery', $content['hero']['title_highlight']);
        $this->assertSame('images/custom-hero.webp', $content['hero']['avatar_image']);
        $this->assertSame('Elevate Your Enterprise Skillset Today', $content['banner_transform']['heading']);
    }

    public function test_homepage_reflects_saved_customizations_on_live_front(): void
    {
        // 1. Customize hero and transform banner
        app(HomePageContent::class)->save([
            'sections' => [
                'hero' => [
                    'enabled' => true,
                    'badge' => 'GLOBAL ACADEMIC NETWORK',
                    'title_line1' => 'Learn From Worldwide Leaders,',
                    'title_highlight' => 'Unleash',
                    'title_line2' => 'Your Potential',
                    'subtitle' => 'Access over 5,000 certified career masterclasses.',
                ],
                'banner_transform' => [
                    'enabled' => true,
                    'heading' => 'Revolutionize Access To Modern Education',
                    'description' => 'Sign up today to receive curated instructor recommendations.',
                    'button_text' => 'Browse All Degrees',
                    'button_url' => '/degrees',
                ],
            ],
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('GLOBAL ACADEMIC NETWORK');
        $response->assertSee('Learn From Worldwide Leaders,');
        $response->assertSee('Unleash');
        $response->assertSee('Revolutionize Access To Modern Education');
        $response->assertSee('Browse All Degrees');
    }

    public function test_disabling_section_hides_it_from_homepage(): void
    {
        // Disable transform banner and testimonials
        app(HomePageContent::class)->save([
            'sections' => [
                'banner_transform' => [
                    'enabled' => false,
                    'heading' => 'Should Not Be Seen',
                ],
                'testimonials' => [
                    'enabled' => false,
                    'heading' => 'Hidden Testimonials Heading',
                ],
            ],
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Should Not Be Seen');
        $response->assertDontSee('Hidden Testimonials Heading');
    }

    public function test_sidebar_includes_home_page_editor_link(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.homepage.index'));
        $response->assertSee('Home Page Editor');
    }
}
