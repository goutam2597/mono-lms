<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['student', 'course'])
            ->when(in_array($request->query('status'), ['pending', 'approved', 'rejected']), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(10);

        return view('pages.admin.reviews', ['reviews' => $reviews]);
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        return back()->with('success', __('admin.reviews.approved'));
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        return back()->with('success', __('admin.reviews.rejected'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', __('admin.reviews.deleted'));
    }
}
