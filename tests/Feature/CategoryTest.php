<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function test_admin_can_create_category_with_uploaded_image(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();

        $this->actingAs($admin, 'admin');

        $image = UploadedFile::fake()->image('angular-logo.png', 120, 120);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Vue Development',
            'color' => '#42b883',
            'image' => $image,
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Vue Development')->firstOrFail();
        $this->assertNotNull($category->image);
        Storage::disk('public')->assertExists($category->image);
    }

    public function test_admin_can_update_and_remove_category_image(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $image = UploadedFile::fake()->image('old-logo.png', 100, 100);
        $path = $image->store('categories', 'public');

        $category = Category::create([
            'name' => 'React Development',
            'slug' => 'react-development',
            'color' => '#61dafb',
            'image' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        // Remove image
        $response = $this->put(route('admin.categories.update', $category), [
            'name' => 'React Development',
            'color' => '#61dafb',
            'remove_image' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $category->refresh();

        $this->assertNull($category->image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_home_page_renders_category_cards_cleanly_without_sparkle_rays(): void
    {
        Category::create([
            'name' => 'Angular Development',
            'slug' => 'angular-development',
            'color' => '#dd0031',
            'image' => 'categories/angular.svg',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Angular Development');
        $response->assertSee('categories/angular.svg');
        $response->assertSee('pb-3 sm:pb-3.5');
        // Single-line slider with dual arrows and pagination
        $response->assertSee('data-category-slider');
        $response->assertSee('data-slider-track');
        $response->assertSee('data-slider-prev');
        $response->assertSee('data-slider-next');
        $response->assertSee('data-slider-dots');
        // Sparkle rays path must NOT be present
        $response->assertDontSee('M10 2l1.5 2.5M14 5l-2 1.5M15 9.5h-2.5');
    }

    public function test_admin_panel_renders_as_standalone_full_width_layout(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('dashboard-sidebar');
        $response->assertSee('View Website');
        // Must NOT render public storefront header/text
        $response->assertDontSee('The Leader in Online Learning');
    }

    public function test_admin_can_bulk_delete_categories(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $c1 = Category::create(['name' => 'Cat 1', 'slug' => 'cat-1']);
        $c2 = Category::create(['name' => 'Cat 2', 'slug' => 'cat-2']);
        $c3 = Category::create(['name' => 'Cat 3', 'slug' => 'cat-3']);

        $response = $this->post(route('admin.categories.bulk-destroy'), [
            'ids' => [$c1->id, $c2->id],
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $c1->id]);
        $this->assertDatabaseMissing('categories', ['id' => $c2->id]);
        $this->assertDatabaseHas('categories', ['id' => $c3->id]);
    }

    public function test_admin_categories_index_has_modal_and_bulk_toolbar(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.categories.index'));
        $response->assertOk();
        $response->assertSee('id="cat-modal"', false);
        $response->assertSee('data-bulk-toolbar', false);
        $response->assertSee('id="check-all"', false);
    }
}
