<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        return view('pages.admin.roles', [
            'roles' => Role::withCount(['admins', 'permissions'])->with('permissions')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.admin.roles-form', [
            'role' => new Role(),
            'permissions' => Permission::all()->groupBy('group'),
        ]);
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $this->validated($request)['name'], 'slug' => '']);

        $role->update(['slug' => Str::slug($role->name)]);
        $role->permissions()->sync($this->permissionIds($request));

        return redirect()->route('admin.roles.index')->with('success', __('admin.roles.created'));
    }

    public function edit(Role $role)
    {
        return view('pages.admin.roles-form', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::all()->groupBy('group'),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $role->update([
            'name' => $this->validated($request, $role)['name'],
            'slug' => Str::slug($this->validated($request, $role)['name']),
        ]);
        $role->permissions()->sync($this->permissionIds($request));

        return redirect()->route('admin.roles.index')->with('success', __('admin.roles.updated'));
    }

    public function destroy(Role $role)
    {
        if ($role->admins()->exists()) {
            return back()->with('error', __('admin.roles.admins_with_role').' — '.__('common.confirm_delete'));
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('admin.roles.deleted'));
    }

    private function validated(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role?->id)],
        ]);
    }

    private function permissionIds(Request $request): array
    {
        return Permission::whereIn('id', (array) $request->input('permissions', []))->pluck('id')->all();
    }
}
