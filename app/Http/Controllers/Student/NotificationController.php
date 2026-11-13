<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $student = auth('student')->user();

        // Ensure user has initial notifications populated from platform records if empty
        NotificationService::syncInitialNotifications($student, 'student');

        $filter = $request->query('filter', 'all');
        $query = $student->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif (in_array($filter, ['system', 'support', 'smtp', 'enrollment', 'quiz', 'certificate', 'assignment', 'live_class', 'leaderboard'])) {
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
            'all' => $student->notifications()->count(),
            'unread' => $student->unreadNotifications()->count(),
            'system' => $student->notifications()->where('data->category', 'system')->count(),
            'support' => $student->notifications()->where('data->category', 'support')->count(),
            'smtp' => $student->notifications()->where('data->category', 'smtp')->count(),
            'enrollment' => $student->notifications()->where('data->category', 'enrollment')->count(),
            'assignment' => $student->notifications()->where('data->category', 'assignment')->count(),
            'live_class' => $student->notifications()->where('data->category', 'live_class')->count(),
            'leaderboard' => $student->notifications()->where('data->category', 'leaderboard')->count(),
        ];

        return view('pages.student.notifications', [
            'notifications' => $notifications,
            'counts' => $counts,
            'currentFilter' => $filter,
        ]);
    }

    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $student = auth('student')->user();
        $notification = $student->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->query('redirect') && ! empty($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $student = auth('student')->user();
        $student->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $student = auth('student')->user();
        $notification = $student->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
