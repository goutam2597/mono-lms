<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AiProvider;
use App\Models\Language;
use App\Models\Role;
use App\Models\Setting;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageAndAiSettingsTest extends TestCase
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
            'name' => 'Super Admin',
            'email' => 'admin@learnify.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_languages_index(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.languages.index'))
            ->assertOk()
            ->assertSee('Languages');
    }

    public function test_admin_can_create_update_toggle_and_set_default_language(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create new language
        $response = $this->post(route('admin.languages.store'), [
            'code' => 'es',
            'name' => 'Spanish',
            'native_name' => 'Español',
            'direction' => 'ltr',
            'flag' => 'es',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('languages', [
            'code' => 'es',
            'name' => 'Spanish',
            'native_name' => 'Español',
            'direction' => 'ltr',
        ]);

        $language = Language::where('code', 'es')->firstOrFail();

        // Update language
        $this->put(route('admin.languages.update', $language), [
            'name' => 'Castilian Spanish',
            'native_name' => 'Español Castellano',
            'direction' => 'ltr',
            'flag' => 'es',
        ])->assertRedirect();

        $this->assertDatabaseHas('languages', [
            'id' => $language->id,
            'name' => 'Castilian Spanish',
        ]);

        // Toggle language status
        $this->patch(route('admin.languages.toggle', $language))->assertRedirect();
        $this->assertFalse($language->fresh()->is_active);

        $this->patch(route('admin.languages.toggle', $language))->assertRedirect();
        $this->assertTrue($language->fresh()->is_active);

        // Make default
        $this->patch(route('admin.languages.default', $language))->assertRedirect();
        $this->assertTrue($language->fresh()->is_default);

        // Clean up test directory if created
        if (is_dir(lang_path('es'))) {
            array_map('unlink', glob(lang_path('es/*.*')));
            rmdir(lang_path('es'));
        }
    }

    public function test_admin_can_view_translations_and_update_translations(): void
    {
        $this->actingAs($this->admin, 'admin');

        $language = Language::create([
            'code' => 'de',
            'name' => 'German',
            'native_name' => 'Deutsch',
            'direction' => 'ltr',
            'is_active' => true,
            'is_default' => false,
            'sort_order' => 2,
        ]);

        $this->get(route('admin.languages.translations', $language))
            ->assertOk()
            ->assertSee('Translations');

        $response = $this->put(route('admin.languages.translations.update', $language), [
            'file' => 'courses',
            't' => [
                'title' => 'Kurse',
            ],
        ]);

        $response->assertRedirect();

        // Also test JSON saving
        $jsonResponse = $this->putJson(route('admin.languages.translations.update', $language), [
            'file' => 'courses',
            't' => [
                'title' => 'Kurse JSON',
            ],
        ]);

        $jsonResponse->assertOk()
            ->assertJson(['message' => 'Translations saved successfully.']);

        // Clean up test directory if created
        if (is_dir(lang_path('de'))) {
            array_map('unlink', glob(lang_path('de/*.*')));
            rmdir(lang_path('de'));
        }
    }

    public function test_admin_can_manage_ai_settings_and_providers(): void
    {
        $this->actingAs($this->admin, 'admin');

        // View AI Settings
        $this->get(route('admin.settings.ai'))
            ->assertOk()
            ->assertSee('AI Providers');

        // Store new provider
        $this->post(route('admin.settings.ai.store'), [
            'name' => 'OpenAI Test',
            'type' => 'openai',
            'base_url' => 'https://api.openai.com/v1',
            'api_key' => 'sk-test-key-12345',
            'selected_models' => ['gpt-4o-mini', 'gpt-4o'],
        ])->assertRedirect();

        $this->assertDatabaseHas('ai_providers', [
            'name' => 'OpenAI Test',
            'type' => 'openai',
        ]);

        $provider = AiProvider::where('name', 'OpenAI Test')->firstOrFail();

        // Update provider
        $this->put(route('admin.settings.ai.update', $provider), [
            'name' => 'OpenAI Test Updated',
            'type' => 'openai',
            'base_url' => 'https://api.openai.com/v1',
            'selected_models' => ['gpt-4o-mini'],
            'is_active' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('ai_providers', [
            'id' => $provider->id,
            'name' => 'OpenAI Test Updated',
        ]);

        // Update AI translator role
        $this->put(route('admin.settings.ai.translator'), [
            'selection' => $provider->id.'|gpt-4o-mini',
            'timeout_minutes' => 5,
        ])->assertRedirect();

        $this->assertEquals($provider->id.'|gpt-4o-mini', Setting::get('ai_translator_provider'));

        // Delete provider
        $this->delete(route('admin.settings.ai.destroy', $provider))->assertRedirect();
        $this->assertDatabaseMissing('ai_providers', ['id' => $provider->id]);
    }
}
