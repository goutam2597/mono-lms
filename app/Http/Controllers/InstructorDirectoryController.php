<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstructorDirectoryController extends Controller
{
    public function grid(Request $request)
    {
        $instructors = Instructor::where('status', 'active')
            ->withCount(['courses', 'enrollments', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('designation', 'like', '%'.$request->string('q').'%')
                ->orWhere('bio', 'like', '%'.$request->string('q').'%')))
            ->when($request->query('sort') === 'courses', fn ($q) => $q->orderByDesc('courses_count'))
            ->when($request->query('sort') !== 'latest', fn ($q) => $q->orderBy('name'))
            ->when($request->query('sort') === 'latest', fn ($q) => $q->latest())
            ->paginate(9)
            ->withQueryString();

        return view('pages.instructors.grid', [
            'instructors' => $instructors,
        ]);
    }

    public function show(string $name)
    {
        // Instructor "slug" is the URL-encoded name in this directory.
        $instructor = Instructor::where('status', 'active')
            ->get()
            ->first(fn ($i) => Str::slug($i->name) === $name);

        abort_unless($instructor, 404);

        return view('pages.instructors.detail', [
            'instructor' => $instructor,
            'courses' => $instructor->courses()->published()->latest()->get(),
        ]);
    }
}
