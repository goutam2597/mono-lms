<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Role;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');
        $search = $request->query('search');

        $users = collect();
        $currentAdminId = (int) auth('admin')->id();

        if (! $roleFilter || $roleFilter === 'student') {
            $studentQuery = Student::latest();
            if ($search) {
                $studentQuery->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            }
            foreach ($studentQuery->get() as $s) {
                $users->push([
                    'id' => $s->id,
                    'raw_id' => $s->id,
                    'type' => 'student',
                    'composite_id' => 'student:'.$s->id,
                    'name' => $s->name,
                    'email' => $s->email,
                    'phone' => $s->phone ?? '',
                    'avatar' => $s->avatar,
                    'avatar_url' => $s->avatarUrl(),
                    'role' => 'Student',
                    'status' => $s->status ?? 'active',
                    'created_at' => $s->created_at,
                    'update_url' => route('admin.students.update', $s),
                    'delete_url' => route('admin.students.destroy', $s),
                    'is_current' => false,
                ]);
            }
        }

        if (! $roleFilter || $roleFilter === 'instructor') {
            $instructorQuery = Instructor::latest();
            if ($search) {
                $instructorQuery->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            }
            foreach ($instructorQuery->get() as $i) {
                $users->push([
                    'id' => $i->id,
                    'raw_id' => $i->id,
                    'type' => 'instructor',
                    'composite_id' => 'instructor:'.$i->id,
                    'name' => $i->name,
                    'email' => $i->email,
                    'phone' => $i->phone ?? '',
                    'designation' => $i->designation ?? '',
                    'bio' => $i->bio ?? '',
                    'avatar' => $i->avatar,
                    'avatar_url' => $i->avatarUrl(),
                    'role' => 'Instructor',
                    'status' => $i->status ?? 'active',
                    'created_at' => $i->created_at,
                    'update_url' => route('admin.instructors.update', $i),
                    'delete_url' => route('admin.instructors.destroy', $i),
                    'is_current' => false,
                ]);
            }
        }

        if (! $roleFilter || $roleFilter === 'admin') {
            $adminQuery = Admin::with('role')->latest();
            if ($search) {
                $adminQuery->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            }
            foreach ($adminQuery->get() as $a) {
                $users->push([
                    'id' => $a->id,
                    'raw_id' => $a->id,
                    'type' => 'admin',
                    'composite_id' => 'admin:'.$a->id,
                    'name' => $a->name,
                    'email' => $a->email,
                    'phone' => $a->phone ?? '',
                    'role_id' => $a->role_id,
                    'is_super_admin' => (bool) $a->is_super_admin,
                    'avatar' => $a->avatar,
                    'avatar_url' => $a->avatarUrl(),
                    'role' => $a->isSuperAdmin() ? 'Super Admin' : ($a->role?->name ?? 'Admin'),
                    'status' => $a->status ?? 'active',
                    'created_at' => $a->created_at,
                    'update_url' => route('admin.admins.update', $a),
                    'delete_url' => route('admin.admins.destroy', $a),
                    'is_current' => ($a->id === $currentAdminId),
                ]);
            }
        }

        $sortedUsers = $users->sortByDesc('created_at')->values();

        return view('pages.admin.users', [
            'users' => $sortedUsers,
            'roles' => Role::orderBy('name')->get(),
            'storeUrls' => [
                'student' => route('admin.students.store'),
                'instructor' => route('admin.instructors.store'),
                'admin' => route('admin.admins.store'),
            ],
            'stats' => [
                'total' => Student::count() + Instructor::count() + Admin::count(),
                'students' => Student::count(),
                'instructors' => Instructor::count(),
                'admins' => Admin::count(),
            ],
        ]);
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['string'],
        ]);

        $deleted = 0;
        $skippedSelf = false;
        $skippedInstructors = 0;
        $currentAdminId = (int) $request->user('admin')?->id;

        foreach ($data['ids'] as $item) {
            $parts = explode(':', $item, 2);
            if (count($parts) !== 2) {
                continue;
            }
            [$type, $id] = $parts;
            $id = (int) $id;

            if ($type === 'student') {
                if (Student::where('id', $id)->delete()) {
                    $deleted++;
                }
            } elseif ($type === 'instructor') {
                $inst = Instructor::withCount('courses')->find($id);
                if ($inst) {
                    if ($inst->courses_count > 0) {
                        $skippedInstructors++;
                    } else {
                        $inst->delete();
                        $deleted++;
                    }
                }
            } elseif ($type === 'admin') {
                if ($id === $currentAdminId) {
                    $skippedSelf = true;
                } else {
                    if (Admin::where('id', $id)->delete()) {
                        $deleted++;
                    }
                }
            }
        }

        $msg = "Deleted {$deleted} user(s) successfully.";
        if ($skippedSelf) {
            $msg .= ' (Your own administrator account was skipped).';
        }
        if ($skippedInstructors > 0) {
            $msg .= " ({$skippedInstructors} instructor(s) skipped because they have published courses).";
        }

        return redirect()->route('admin.users.index')->with('success', $msg);
    }

    public function destroy(Request $request, string $type, int $id): RedirectResponse
    {
        $currentAdminId = (int) $request->user('admin')?->id;

        if ($type === 'student') {
            Student::findOrFail($id)->delete();

            return redirect()->route('admin.users.index')->with('success', __('admin.students.deleted'));
        } elseif ($type === 'instructor') {
            $inst = Instructor::withCount('courses')->findOrFail($id);
            if ($inst->courses_count > 0) {
                return back()->with('error', __('admin.instructors.has_courses'));
            }
            $inst->delete();

            return redirect()->route('admin.users.index')->with('success', __('admin.instructors.deleted'));
        } elseif ($type === 'admin') {
            if ($id === $currentAdminId) {
                return back()->with('error', __('admin.admins.cannot_delete_self'));
            }
            Admin::findOrFail($id)->delete();

            return redirect()->route('admin.users.index')->with('success', __('admin.admins.deleted'));
        }

        return redirect()->route('admin.users.index')->with('error', 'Unknown user account type.');
    }
}
