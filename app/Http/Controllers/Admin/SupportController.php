<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SupportTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function tickets(Request $request): View
    {
        $query = SupportTicket::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->query('priority'));
        }

        $tickets = $query->paginate(12)->withQueryString();

        return view('pages.admin.support-tickets', [
            'tickets' => $tickets,
            'stats' => [
                'open' => SupportTicket::where('status', 'open')->count(),
                'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
                'resolved' => SupportTicket::where('status', 'resolved')->count(),
            ],
        ]);
    }

    public function replyTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'reply' => ['required', 'string'],
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
        ]);

        $ticket->update($data);

        return back()->with('success', "Response recorded for ticket #{$ticket->ticket_number}.");
    }

    public function messages(Request $request): View
    {
        $messages = ContactMessage::latest()->paginate(12);

        return view('pages.admin.contact-messages', [
            'messages' => $messages,
            'unreadCount' => ContactMessage::where('is_read', false)->count(),
        ]);
    }

    public function toggleMessageRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', 'Message status updated.');
    }

    public function destroyMessage(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }
}
