<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_banner_is_hidden_when_disabled(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="cookie-consent"', false);
    }

    public function test_banner_renders_on_storefront_when_enabled(): void
    {
        Setting::set('cookie_consent_enabled', '1');
        Setting::set('cookie_consent_layout', 'bar');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="cookie-consent"', false)
            ->assertSee(__('app.cookie.title'))
            ->assertSee(__('app.cookie.accept'));
    }

    public function test_banner_respects_custom_copy_and_layout(): void
    {
        Setting::set('cookie_consent_enabled', '1');
        Setting::set('cookie_consent_layout', 'card');
        Setting::set('cookie_consent_title', 'Custom cookies notice');
        Setting::set('cookie_consent_policy_url', '/privacy');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Custom cookies notice')
            ->assertSee('sm:max-w-sm', false)
            ->assertSee('href="/privacy"', false)
            ->assertDontSee('fixed inset-x-0 bottom-0', false);
    }

    public function test_banner_respects_saved_consent_cookie_client_side(): void
    {
        // The banner markup is present but hidden; the inline script only
        // reveals it when no decision cookie/localStorage mirror exists.
        Setting::set('cookie_consent_enabled', '1');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('style="display:none"', false)
            ->assertSee("KEY + '=' + value", false)
            ->assertSee("'; path=/; max-age=' + (180 * 24 * 3600) + '; SameSite=Lax'", false);
    }

    public function test_super_admin_can_update_cookie_settings(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.cookie'))
            ->assertOk();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.cookie.update'), [
                'cookie_consent_enabled' => '1',
                'cookie_consent_show_decline' => '1',
                'cookie_consent_layout' => 'modal',
                'cookie_consent_title' => 'Cookies on Mono LMS',
                'cookie_consent_message' => 'We use essential cookies only.',
                'cookie_consent_accept_text' => 'OK',
                'cookie_consent_policy_url' => 'https://example.com/privacy',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('1', Setting::get('cookie_consent_enabled'));
        $this->assertEquals('modal', Setting::get('cookie_consent_layout'));
        $this->assertEquals('Cookies on Mono LMS', Setting::get('cookie_consent_title'));
        $this->assertEquals('', Setting::get('cookie_consent_decline_text'));
    }

    public function test_invalid_layout_is_rejected(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin2@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.cookie.update'), [
                'cookie_consent_layout' => 'popup',
            ])
            ->assertSessionHasErrors('cookie_consent_layout');
    }

    public function test_admin_without_permission_cannot_access_cookie_settings(): void
    {
        $admin = Admin::create([
            'name' => 'Plain Admin',
            'email' => 'plain-cookie@mono-lms.test',
            'password' => 'password',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.cookie'))
            ->assertForbidden();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.cookie.update'), ['cookie_consent_layout' => 'bar'])
            ->assertForbidden();
    }
}
