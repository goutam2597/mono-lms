<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\HomePageContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function edit(HomePageContent $homePageContent): View
    {
        $sections = $homePageContent->all();

        return view('pages.admin.homepage', [
            'sections' => $sections,
            'featuredCourses' => Course::with('instructor')->where('is_featured', true)->latest()->get(),
            'trendingCourses' => Course::with('instructor')->where('is_trending', true)->latest()->get(),
        ]);
    }

    public function update(Request $request, HomePageContent $homePageContent): RedirectResponse
    {
        $homePageContent->save($request->all());

        return back()->with('success', 'Homepage content updated successfully.');
    }
}
