<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class InstructorController extends Controller
{
    public function index()
    {
        return view('pages.admin.instructors', [
            'instructors' => Instructor::withCount('courses')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('pages.admin.instructors-form', ['instructor' => new Instructor()]);
    }

    public function store(Request $request)
    {
        Instructor::create($this->validated($request) + ['status' => 'active']);

        return redirect()->route('admin.instructors.index')->with('success', __('admin.instructors.created'));
    }

    public function edit(Instructor $instructor)
    {
        return view('pages.admin.instructors-form', ['instructor' => $instructor]);
    }

    public function update(Request $request, Instructor $instructor)
    {
        $data = $this->validated($request, $instructor);

        if (! $request->filled('password')) {
            unset($data['password']);
        }

        $instructor->update($data);

        return redirect()->route('admin.instructors.index')->with('success', __('admin.instructors.updated'));
    }

    public function destroy(Instructor $instructor)
    {
        if ($instructor->courses()->exists()) {
            return back()->with('error', __('admin.instructors.has_courses'));
        }

        $instructor->delete();

        return redirect()->route('admin.instructors.index')->with('success', __('admin.instructors.deleted'));
    }

    private function validated(Request $request, ?Instructor $instructor = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('instructors', 'email')->ignore($instructor?->id)],
            'password' => [$instructor ? 'nullable' : 'required', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
