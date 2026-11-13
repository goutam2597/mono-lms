<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $instructor = auth('instructor')->user();

        // Ensure user has initial notifications populated from platform records if empty
        NotificationService::syncInitialNotifications($instructor, 'instructor');

        $filter = $request->query('filter', 'all');
        $query = $instructor->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif (in_array($filter, ['verification', 'support', 'smtp', 'enrollment', 'review', 'assignment'])) {
            $query->where('data->category', $filter);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('data->title', 'like', "%{$search}%")
                    ->orWhere('data->message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => $instructor->notifications()->count(),
            'unread' => $instructor->unreadNotifications()->count(),
            'verification' => $instructor->notifications()->where('data->category', 'verification')->count(),
            'support' => $instructor->notifications()->where('data->category', 'support')->count(),
            'smtp' => $instructor->notifications()->where('data->category', 'smtp')->count(),
            'enrollment' => $instructor->notifications()->where('data->category', 'enrollment')->count(),
            'review' => $instructor->notifications()->where('data->category', 'review')->count(),
            'assignment' => $instructor->notifications()->where('data->category', 'assignment')->count(),
        ];

        return view('pages.instructor.notifications', [
            'notifications' => $notifications,
            'counts' => $counts,
            'currentFilter' => $filter,
        ]);
    }

    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $instructor = auth('instructor')->user();
        $notification = $instructor->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->query('redirect') && ! empty($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $instructor = auth('instructor')->user();
        $instructor->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $instructor = auth('instructor')->user();
        $notification = $instructor->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
