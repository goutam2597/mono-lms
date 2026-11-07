<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): Admin
    {
        return Admin::create(['name' => 'Super', 'email' => 'super@test.dev', 'password' => 'password', 'is_super_admin' => true, 'status' => 'active']);
    }

    private function managerWithPermissions(array $slugs): Admin
    {
        $role = Role::create(['name' => 'Limited Manager', 'slug' => 'limited-manager']);
        $ids = collect($slugs)->map(fn ($slug) => Permission::create(['name' => $slug, 'slug' => $slug, 'group' => 'Test'])->id);
        $role->permissions()->sync($ids);

        return Admin::create(['name' => 'M', 'email' => 'm@test.dev', 'password' => 'password', 'role_id' => $role->id, 'status' => 'active']);
    }

    public function test_super_admin_can_access_everything(): void
    {
        $admin = $this->superAdmin();

        $this->post(route('admin.login.attempt'), ['email' => $admin->email, 'password' => 'password']);

        $this->get(route('admin.admins.index'))->assertOk();
        $this->get(route('admin.roles.index'))->assertOk();
        $this->get(route('admin.courses.index'))->assertOk();
        $this->get(route('admin.languages.index'))->assertOk();
    }

    public function test_manager_without_permission_gets_403(): void
    {
        $manager = $this->managerWithPermissions(['students.manage']);

        $this->post(route('admin.login.attempt'), ['email' => $manager->email, 'password' => 'password']);

        $this->get(route('admin.students.index'))->assertOk();
        $this->get(route('admin.roles.index'))->assertForbidden();
        $this->get(route('admin.admins.index'))->assertForbidden();
        $this->get(route('admin.languages.index'))->assertForbidden();
    }

    public function test_permission_middleware_accepts_pipe_separated_any_of(): void
    {
        $manager = $this->managerWithPermissions(['reviews.moderate']);

        $this->actingAs($manager, 'admin');

        $this->get(route('admin.reviews.index'))->assertOk();
        $this->get(route('admin.courses.index'))->assertForbidden();
    }

    public function test_non_admin_users_are_rejected_by_admin_middleware(): void
    {
        $this->get(route('admin.admins.index'))->assertRedirect(route('admin.login'));
    }
}
