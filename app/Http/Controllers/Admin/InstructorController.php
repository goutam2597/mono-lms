<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
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
        return view('pages.admin.instructors-form', ['instructor' => new Instructor]);
    }

    public function store(Request $request)
    {
        Instructor::create($this->validated($request));

        $redirect = $request->input('redirect_to') ?: route('admin.instructors.index');

        return redirect($redirect)->with('success', __('admin.instructors.created'));
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

        $redirect = $request->input('redirect_to') ?: route('admin.instructors.index');

        return redirect($redirect)->with('success', __('admin.instructors.updated'));
    }

    public function destroy(Request $request, Instructor $instructor)
    {
        if ($instructor->courses()->exists()) {
            return back()->with('error', __('admin.instructors.has_courses'));
        }

        $instructor->delete();

        $redirect = $request->input('redirect_to') ?: route('admin.instructors.index');

        return redirect($redirect)->with('success', __('admin.instructors.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:instructors,id'],
        ]);

        $instructors = Instructor::whereIn('id', $data['ids'])->withCount('courses')->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($instructors as $inst) {
            if ($inst->courses_count > 0) {
                $skipped++;

                continue;
            }
            $inst->delete();
            $deleted++;
        }

        if ($skipped > 0) {
            return redirect()->route('admin.instructors.index')->with(
                'success',
                "Deleted {$deleted} instructors ({$skipped} skipped because they have courses)."
            );
        }

        return redirect()->route('admin.instructors.index')->with('success', "Deleted {$deleted} instructors successfully.");
    }

    private function validated(Request $request, ?Instructor $instructor = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('instructors', 'email')->ignore($instructor?->id)],
            'password' => [$instructor ? 'nullable' : 'required', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'designation' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
