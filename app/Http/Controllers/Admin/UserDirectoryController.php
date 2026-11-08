<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');
        $search = $request->query('search');

        $users = collect();

        if (! $roleFilter || $roleFilter === 'student') {
            $studentQuery = Student::latest();
            if ($search) {
                $studentQuery->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            }
            foreach ($studentQuery->get() as $s) {
                $users->push([
                    'id' => $s->id,
                    'name' => $s->name,
                    'email' => $s->email,
                    'avatar' => $s->avatar,
                    'role' => 'Student',
                    'status' => $s->status,
                    'created_at' => $s->created_at,
                    'edit_url' => route('admin.students.edit', $s),
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
                    'name' => $i->name,
                    'email' => $i->email,
                    'avatar' => $i->avatar,
                    'role' => 'Instructor',
                    'status' => $i->status,
                    'created_at' => $i->created_at,
                    'edit_url' => route('admin.instructors.edit', $i),
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
                    'name' => $a->name,
                    'email' => $a->email,
                    'avatar' => null,
                    'role' => $a->isSuperAdmin() ? 'Super Admin' : ($a->role?->name ?? 'Admin'),
                    'status' => $a->status,
                    'created_at' => $a->created_at,
                    'edit_url' => route('admin.admins.edit', $a),
                ]);
            }
        }

        $sortedUsers = $users->sortByDesc('created_at')->values();

        return view('pages.admin.users', [
            'users' => $sortedUsers,
            'stats' => [
                'total' => Student::count() + Instructor::count() + Admin::count(),
                'students' => Student::count(),
                'instructors' => Instructor::count(),
                'admins' => Admin::count(),
            ],
        ]);
    }
}
