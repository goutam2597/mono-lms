<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('pages.admin.admins', [
            'admins' => Admin::with('role')->latest()->paginate(10),
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return view('pages.admin.admins-form', [
            'admin' => new Admin,
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Admin::create($data + ['is_super_admin' => $request->boolean('is_super_admin')]);

        $redirect = $request->input('redirect_to') ?: route('admin.admins.index');

        return redirect($redirect)->with('success', __('admin.admins.created'));
    }

    public function edit(Admin $admin)
    {
        return view('pages.admin.admins-form', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $this->validated($request, $admin);

        if (! $request->filled('password')) {
            unset($data['password']);
        }

        $admin->update($data + ['is_super_admin' => $request->boolean('is_super_admin')]);

        $redirect = $request->input('redirect_to') ?: route('admin.admins.index');

        return redirect($redirect)->with('success', __('admin.admins.updated'));
    }

    public function destroy(Request $request, Admin $admin)
    {
        if ($admin->id === $request->user('admin')->id) {
            return back()->with('error', __('admin.admins.cannot_delete_self'));
        }

        $admin->delete();

        $redirect = $request->input('redirect_to') ?: route('admin.admins.index');

        return redirect($redirect)->with('success', __('admin.admins.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:admins,id'],
        ]);

        $currentId = $request->user('admin')->id;
        $ids = array_values(array_diff($data['ids'], [$currentId]));

        $count = Admin::whereIn('id', $ids)->delete();

        $skippedSelf = in_array($currentId, $data['ids']);
        if ($skippedSelf) {
            return redirect()->route('admin.admins.index')->with('success', "Deleted {$count} administrators (your own account was skipped).");
        }

        return redirect()->route('admin.admins.index')->with('success', "Deleted {$count} administrators successfully.");
    }

    private function validated(Request $request, ?Admin $admin = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin?->id)],
            'password' => [$admin ? 'nullable' : 'required', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
