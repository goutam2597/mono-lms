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
    public function index(Request $request)
    {
        $status = $request->query('status');
        $verification = $request->query('verification');

        $query = Instructor::withCount('courses')->latest();

        if ($verification) {
            $query->where('verification_status', $verification);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $counts = [
            'all' => Instructor::count(),
            'pending' => Instructor::where('verification_status', 'pending')->count(),
            'approved' => Instructor::where('verification_status', 'approved')->count(),
            'unverified' => Instructor::where('verification_status', 'unverified')->count(),
            'rejected' => Instructor::where('verification_status', 'rejected')->count(),
        ];

        return view('pages.admin.instructors', [
            'instructors' => $query->paginate(15)->withQueryString(),
            'activeFilter' => $verification ?: ($status ?: 'all'),
            'counts' => $counts,
        ]);
    }

    public function create()
    {
        return view('pages.admin.instructors-form', ['instructor' => new Instructor]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if (($data['status'] ?? 'active') === 'active') {
            $data['verification_status'] = 'approved';
            $data['verified_at'] = now();
        }

        Instructor::create($data);

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

        if (($data['status'] ?? '') === 'active' && $instructor->verification_status !== 'approved') {
            $data['verification_status'] = 'approved';
            $data['verified_at'] = now();
            $data['rejection_reason'] = null;
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

    public function approveVerification(Instructor $instructor): RedirectResponse
    {
        $instructor->update([
            'status' => 'active',
            'verification_status' => 'approved',
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Instructor \"{$instructor->name}\" has been verified and activated.");
    }

    public function rejectVerification(Request $request, Instructor $instructor): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $instructor->update([
            'status' => 'inactive',
            'verification_status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return back()->with('success', "Instructor \"{$instructor->name}\" verification was rejected.");
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
            'status' => ['required', 'in:active,inactive,pending_verification'],
            'verification_status' => ['nullable', 'in:unverified,pending,approved,rejected'],
        ]);
    }
}
