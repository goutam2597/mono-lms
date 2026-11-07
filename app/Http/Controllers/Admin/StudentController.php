<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
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

    public function edit(Student $student)
    {
        return view('pages.admin.students-form', ['student' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        $student->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ]));

        return redirect()->route('admin.students.index')->with('success', __('admin.students.updated'));
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', __('admin.students.deleted'));
    }
}
