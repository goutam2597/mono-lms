<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Instructor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('instructor')->orderBy('start_time')->get();
        $instructors = Instructor::where('status', 'active')->get();

        return view('pages.admin.events', [
            'events' => $events,
            'instructors' => $instructors,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'start_time' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:15'],
            'meeting_link' => ['nullable', 'url'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $data['is_published'] = true;

        Event::create($data);

        return back()->with('success', "Event '{$data['title']}' scheduled successfully.");
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'start_time' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:15'],
            'meeting_link' => ['nullable', 'url'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $event->update($data);

        return back()->with('success', "Event '{$event->title}' updated successfully.");
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return back()->with('success', 'Event cancelled and removed.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:events,id'],
        ]);

        $count = Event::whereIn('id', $data['ids'])->delete();

        return back()->with('success', "Deleted {$count} events successfully.");
    }
}
