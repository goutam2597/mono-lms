<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permission catalog (slug => [label, group]) and the starter roles.
     * The super admin bypasses every check; these role grants are what the
     * super admin can curate from the admin panel.
     */
    public function run(): void
    {
        $permissions = [
            'admins.manage' => ['Manage admin accounts', 'Admins'],
            'roles.manage' => ['Manage roles & permissions', 'Admins'],
            'instructors.manage' => ['Manage instructors', 'Instructors'],
            'students.manage' => ['Manage students', 'Students'],
            'categories.manage' => ['Manage categories', 'Courses'],
            'courses.manage' => ['Manage courses', 'Courses'],
            'courses.publish' => ['Publish / unpublish courses', 'Courses'],
            'reviews.moderate' => ['Moderate reviews', 'Courses'],
            'blog.manage' => ['Manage blog posts', 'Content'],
            'homepage.manage' => ['Manage homepage editor & content', 'Content'],
            'pages.manage' => ['Manage static & custom pages', 'Content'],
            'media.manage' => ['Manage media library', 'Content'],
            'plans.manage' => ['Manage membership plans', 'Membership'],
            'languages.manage' => ['Manage languages', 'Settings'],
        ];

        $permissionIds = [];
        foreach ($permissions as $slug => [$name, $group]) {
            $permissionIds[$slug] = Permission::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'group' => $group]
            )->id;
        }

        $roles = [
            'Super Admin' => array_keys($permissions),
            'Manager' => ['instructors.manage', 'students.manage', 'categories.manage', 'courses.manage', 'courses.publish', 'reviews.moderate', 'blog.manage', 'homepage.manage', 'pages.manage', 'media.manage', 'plans.manage'],
            'Support' => ['students.manage', 'reviews.moderate'],
        ];

        foreach ($roles as $name => $slugs) {
            $role = Role::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $role->permissions()->sync(collect($slugs)->map(fn ($slug) => $permissionIds[$slug]));
        }
    }
}
