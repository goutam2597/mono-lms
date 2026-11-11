<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AiProvider;
use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Language;
use App\Models\Setting;
use App\Services\AiTranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultilingualAndAiToolsTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Super',
            'email' => 'super@test.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);
    }

    private function instructor(): Instructor
    {
        return Instructor::create([
            'name' => 'Jane Instructor',
            'email' => 'instructor@test.dev',
            'password' => 'password',
            'status' => 'approved',
        ]);
    }

    public function test_has_translations_trait_resolves_correctly_with_fallback(): void
    {
        Language::create(['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_default' => true, 'is_active' => true]);
        Language::create(['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'direction' => 'rtl', 'is_default' => false, 'is_active' => true]);
        Language::create(['code' => 'hi', 'name' => 'Hindi', 'native_name' => 'हिन्दी', 'is_default' => false, 'is_active' => true]);

        $category = Category::create([
            'name' => 'Design',
            'slug' => 'design',
            'name_translations' => [
                'ar' => 'تصميم',
                'hi' => 'डिजाइन',
            ],
        ]);

        $this->assertSame('Design', $category->trans('name', 'en'));
        $this->assertSame('تصميم', $category->trans('name', 'ar'));
        $this->assertSame('डिजाइन', $category->trans('name', 'hi'));
        // Fallback for an unconfigured language
        $this->assertSame('Design', $category->trans('name', 'fr'));
    }

    public function test_admin_can_save_category_with_translations(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        Language::create(['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_default' => true, 'is_active' => true]);
        Language::create(['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'direction' => 'rtl', 'is_default' => false, 'is_active' => true]);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Programming',
            'color' => '#10b981',
            'translations' => [
                'ar' => ['name' => 'برمجة'],
            ],
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Programming')->firstOrFail();
        $this->assertSame('برمجة', $category->name_translations['ar'] ?? null);
    }

    public function test_admin_can_save_course_with_translations(): void
    {
        $admin = $this->superAdmin();
        $instructor = $this->instructor();
        $category = Category::create(['name' => 'Dev', 'slug' => 'dev']);

        $this->actingAs($admin, 'admin');

        Language::create(['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_default' => true, 'is_active' => true]);
        Language::create(['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'direction' => 'rtl', 'is_default' => false, 'is_active' => true]);

        $response = $this->post(route('admin.courses.store'), [
            'title' => 'Mastering Laravel',
            'subtitle' => 'From beginner to pro',
            'description' => 'Comprehensive Laravel course.',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 99.00,
            'level' => 'intermediate',
            'type' => 'recorded',
            'translations' => [
                'ar' => [
                    'title' => 'إتقان لارافيل',
                    'subtitle' => 'من المبتدئ إلى المحترف',
                    'description' => 'دورة شاملة في لارافيل.',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course = Course::where('slug', 'mastering-laravel')->firstOrFail();
        $this->assertSame('إتقان لارافيل', $course->trans('title', 'ar'));
        $this->assertSame('من المبتدئ إلى المحترف', $course->trans('subtitle', 'ar'));
        $this->assertSame('دورة شاملة في لارافيل.', $course->trans('description', 'ar'));
    }

    public function test_translate_endpoint_validates_request(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->postJson(route('admin.translate'), []);
        $response->assertStatus(422);
    }

    public function test_admin_course_edit_page_renders_styled_language_tabs_and_form(): void
    {
        $admin = $this->superAdmin();
        $instructor = $this->instructor();
        $category = Category::create(['name' => 'Dev', 'slug' => 'dev']);

        Language::create(['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_default' => true, 'is_active' => true]);
        Language::create(['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'direction' => 'rtl', 'is_default' => false, 'is_active' => true]);

        $course = Course::create([
            'title' => 'Mastering Laravel',
            'slug' => 'mastering-laravel',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 99.00,
            'level' => 'intermediate',
            'type' => 'recorded',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.courses.edit', $course));
        $response->assertOk();
        $response->assertSee('lang-tabs');
        $response->assertSee('lang-tab');
        $response->assertSee('lang-badge');
        $response->assertSee('id="course-form"', false);
        $response->assertSee('id="aiimg-modal"', false);
    }

    public function test_admin_blog_and_category_pages_render_ai_image_buttons(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $blogRes = $this->get(route('admin.blog.create'));
        $blogRes->assertOk();
        $blogRes->assertSee('lang-tabs');
        $blogRes->assertSee('id="aiimg-modal"', false);

        $catRes = $this->get(route('admin.categories.index'));
        $catRes->assertOk();
        $catRes->assertSee('lang-tabs');
        $catRes->assertSee('id="aiimg-modal"', false);
    }

    public function test_generate_image_endpoint_handles_missing_provider(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->postJson(route('admin.generate.image'), [
            'prompt' => 'A beautiful online course banner',
            'size' => '1792x1024',
            'count' => 1,
        ]);

        $response->assertStatus(422);
        $this->assertNotEmpty($response->json('message'));
    }

    public function test_generate_content_endpoint_validates_prompt(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->postJson(route('admin.generate'), []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['prompt']);
    }

    public function test_admin_course_edit_renders_text_generation_button_and_row(): void
    {
        $admin = $this->superAdmin();
        $instructor = $this->instructor();
        $category = Category::create(['name' => 'Dev', 'slug' => 'dev']);

        Language::create(['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_default' => true, 'is_active' => true]);

        $provider = AiProvider::create([
            'name' => 'SenseNova',
            'type' => 'openai',
            'base_url' => 'https://token.sensenova.ai/v1',
            'api_key' => 'test-key',
            'model' => 'sensenova-6.8-flash-lite',
            'is_active' => true,
        ]);
        Setting::set('ai_generator_provider', $provider->id.'|'.$provider->model);

        $course = Course::create([
            'title' => 'Mastering Laravel',
            'slug' => 'mastering-laravel',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 99.00,
            'level' => 'intermediate',
            'type' => 'recorded',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.courses.edit', $course));
        $response->assertOk();
        $response->assertSee('id="course-generate-ai"', false);
        $response->assertSee('id="aiWriteModal"', false);
        $response->assertSee('id="aiWriteHint"', false);
        $response->assertSee('id="aiWriteGenBtn"', false);
    }

    public function test_generate_content_mocked_success(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $provider = AiProvider::create([
            'name' => 'SenseNova',
            'type' => 'openai',
            'base_url' => 'https://token.sensenova.ai/v1',
            'api_key' => 'test-key',
            'model' => 'sensenova-6.8-flash-lite',
            'is_active' => true,
        ]);
        Setting::set('ai_generator_provider', $provider->id.'|'.$provider->model);

        $mockService = $this->createMock(AiTranslationService::class);
        $mockService->method('chatWithFallback')->willReturn(json_encode([
            'title' => 'Modern PHP 8.4 Deep Dive',
            'subtitle' => 'Master new language features with real projects',
            'description' => '<p>Learn everything new in PHP 8.4.</p>',
        ]));

        $this->app->instance(AiTranslationService::class, $mockService);

        $response = $this->postJson(route('admin.generate'), [
            'prompt' => 'A comprehensive guide to PHP 8.4 features',
            'type' => 'course',
        ]);

        $response->assertOk();
        $response->assertJson([
            'title' => 'Modern PHP 8.4 Deep Dive',
            'subtitle' => 'Master new language features with real projects',
            'description' => '<p>Learn everything new in PHP 8.4.</p>',
        ]);
    }
}
