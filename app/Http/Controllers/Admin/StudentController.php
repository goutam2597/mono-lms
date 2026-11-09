<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::withCount('enrollments')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('email', 'like', '%'.$request->string('q').'%')))
            ->latest()
            ->paginate(10);

        return view('pages.admin.students', ['students' => $students]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        Student::create($validated);

        $redirect = $request->input('redirect_to') ?: route('admin.students.index');

        return redirect($redirect)->with('success', __('admin.students.created') ?? 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return view('pages.admin.students-form', ['student' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $student->update($data);

        $redirect = $request->input('redirect_to') ?: route('admin.students.index');

        return redirect($redirect)->with('success', __('admin.students.updated'));
    }

    public function destroy(Request $request, Student $student)
    {
        $student->delete();

        $redirect = $request->input('redirect_to') ?: route('admin.students.index');

        return redirect($redirect)->with('success', __('admin.students.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:students,id'],
        ]);

        $count = Student::whereIn('id', $data['ids'])->delete();

        $redirect = $request->input('redirect_to') ?: route('admin.students.index');

        return redirect($redirect)->with('success', "Deleted {$count} students successfully.");
    }
}
