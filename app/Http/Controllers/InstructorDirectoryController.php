<?php

namespace App\Http\Controllers;

use App\Models\Instructor;

class InstructorDirectoryController extends Controller
{
    public function grid()
    {
        return view('pages.instructors.grid', [
            'instructors' => Instructor::where('status', 'active')->withCount('courses')->orderBy('name')->paginate(9),
        ]);
    }

    public function show(string $name)
    {
        // Instructor "slug" is the URL-encoded name in this directory.
        $instructor = Instructor::where('status', 'active')
            ->get()
            ->first(fn ($i) => \Illuminate\Support\Str::slug($i->name) === $name);

        abort_unless($instructor, 404);

        return view('pages.instructors.detail', [
            'instructor' => $instructor,
            'courses' => $instructor->courses()->published()->latest()->get(),
        ]);
    }
}
