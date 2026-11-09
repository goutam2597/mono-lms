<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Media;
use App\Support\PublicMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
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

    public function test_guest_cannot_access_media_library(): void
    {
        $response = $this->get(route('admin.media.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_media_library(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.media.index'));
        $response->assertOk();
        $response->assertSee(__('admin.media.heading'));
    }

    public function test_admin_can_upload_media_via_store(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $file1 = UploadedFile::fake()->image('banner1.jpg', 600, 400);
        $file2 = UploadedFile::fake()->image('banner2.png', 400, 300);

        $response = $this->post(route('admin.media.store'), [
            'files' => [$file1, $file2],
        ]);

        $response->assertRedirect(route('admin.media.index'));
        $this->assertDatabaseCount('media', 2);

        $media = Media::where('name', 'banner1.jpg')->firstOrFail();
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_admin_can_quick_upload_media_via_json(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $file = UploadedFile::fake()->image('sample.webp', 500, 500);

        $response = $this->postJson(route('admin.media.upload'), [
            'files' => [$file],
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'url', 'path', 'size', 'width', 'height'],
            ],
        ]);

        $this->assertDatabaseHas('media', ['name' => 'sample.webp']);
    }

    public function test_admin_can_fetch_media_json_list_with_search(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        Media::create([
            'disk' => 'public',
            'path' => 'media/2026/11/laravel-course.png',
            'name' => 'laravel-course.png',
            'size' => 1024,
            'admin_id' => $admin->id,
        ]);

        Media::create([
            'disk' => 'public',
            'path' => 'media/2026/11/python-course.png',
            'name' => 'python-course.png',
            'size' => 2048,
            'admin_id' => $admin->id,
        ]);

        // Fetch all
        $response = $this->getJson(route('admin.media.json'));
        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        // Search for laravel
        $searchResponse = $this->getJson(route('admin.media.json', ['q' => 'laravel']));
        $searchResponse->assertOk();
        $searchResponse->assertJsonCount(1, 'data');
        $searchResponse->assertJsonFragment(['name' => 'laravel-course.png']);
    }

    public function test_admin_can_delete_media(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/2026/11/to-delete.png',
            'name' => 'to-delete.png',
            'size' => 1024,
            'admin_id' => $admin->id,
        ]);
        Storage::disk('public')->put($media->path, 'test content');

        $response = $this->delete(route('admin.media.destroy', $media));
        $response->assertRedirect(route('admin.media.index'));

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($media->path);
    }

    public function test_admin_can_bulk_delete_media(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $m1 = Media::create(['disk' => 'public', 'path' => 'media/1.png', 'name' => '1.png', 'size' => 100, 'admin_id' => $admin->id]);
        $m2 = Media::create(['disk' => 'public', 'path' => 'media/2.png', 'name' => '2.png', 'size' => 100, 'admin_id' => $admin->id]);
        $m3 = Media::create(['disk' => 'public', 'path' => 'media/3.png', 'name' => '3.png', 'size' => 100, 'admin_id' => $admin->id]);

        $response = $this->post(route('admin.media.bulk-destroy'), [
            'ids' => [$m1->id, $m2->id],
        ]);

        $response->assertRedirect(route('admin.media.index'));
        $this->assertDatabaseMissing('media', ['id' => $m1->id]);
        $this->assertDatabaseMissing('media', ['id' => $m2->id]);
        $this->assertDatabaseHas('media', ['id' => $m3->id]);
    }

    public function test_admin_can_choose_media_image_for_category(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/2026/11/category-icon.svg',
            'name' => 'category-icon.svg',
            'size' => 512,
            'admin_id' => $admin->id,
        ]);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Design & UI',
            'color' => '#137547',
            'media_image' => $media->path,
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Design & UI')->firstOrFail();
        $this->assertEquals($media->path, $category->image);
        $this->assertStringContainsString('category-icon.svg', $category->imageUrl());
    }

    public function test_admin_can_set_and_remove_category_image_via_media_selector(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/2026/11/cat-sample.png',
            'name' => 'cat-sample.png',
            'size' => 512,
            'admin_id' => $admin->id,
        ]);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Web Dev',
            'image' => $media->path,
        ]);
        $response->assertRedirect(route('admin.categories.index'));

        $cat = Category::where('name', 'Web Dev')->firstOrFail();
        $this->assertEquals($media->path, $cat->image);

        $updateResponse = $this->put(route('admin.categories.update', $cat), [
            'name' => 'Web Dev',
            'image' => '',
        ]);
        $updateResponse->assertRedirect(route('admin.categories.index'));
        $cat->refresh();
        $this->assertNull($cat->image);
    }

    public function test_public_media_helper_resolves_urls(): void
    {
        $this->assertEquals('https://example.com/photo.jpg', PublicMedia::url('https://example.com/photo.jpg'));
        $this->assertStringContainsString('storage/media/test.jpg', PublicMedia::url('media/test.jpg'));
        $this->assertStringContainsString('images/logo.png', PublicMedia::url('', 'images/logo.png'));
    }
}
