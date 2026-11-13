<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $admin = auth('admin')->user();

        // Ensure admin has initial notifications populated from platform records if empty
        NotificationService::syncInitialNotifications($admin, 'admin');

        $filter = $request->query('filter', 'all');
        $query = $admin->notifications();

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
            'all' => $admin->notifications()->count(),
            'unread' => $admin->unreadNotifications()->count(),
            'verification' => $admin->notifications()->where('data->category', 'verification')->count(),
            'support' => $admin->notifications()->where('data->category', 'support')->count(),
            'smtp' => $admin->notifications()->where('data->category', 'smtp')->count(),
            'enrollment' => $admin->notifications()->where('data->category', 'enrollment')->count(),
            'assignment' => $admin->notifications()->where('data->category', 'assignment')->count(),
        ];

        return view('pages.admin.notifications', [
            'notifications' => $notifications,
            'counts' => $counts,
            'currentFilter' => $filter,
        ]);
    }

    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $admin = auth('admin')->user();
        $notification = $admin->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->query('redirect') && ! empty($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $admin = auth('admin')->user();
        $admin->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $admin = auth('admin')->user();
        $notification = $admin->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
