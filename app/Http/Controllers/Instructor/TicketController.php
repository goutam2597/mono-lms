<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $instructor = auth('instructor')->user();

        $query = SupportTicket::where('user_email', $instructor->email)->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhere('reply', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', $request->query('status'));
        }

        $tickets = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => SupportTicket::where('user_email', $instructor->email)->count(),
            'open' => SupportTicket::where('user_email', $instructor->email)->where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('user_email', $instructor->email)->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('user_email', $instructor->email)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('pages.instructor.support-tickets', [
            'instructor' => $instructor,
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $instructor = auth('instructor')->user();

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        $ticketNumber = 'TK-'.strtoupper(Str::random(3)).'-'.random_int(1000, 9999);
        while (SupportTicket::where('ticket_number', $ticketNumber)->exists()) {
            $ticketNumber = 'TK-'.strtoupper(Str::random(3)).'-'.random_int(1000, 9999);
        }

        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'user_name' => $instructor->name,
            'user_email' => $instructor->email,
            'subject' => trim($data['subject']),
            'priority' => $data['priority'],
            'status' => 'open',
            'message' => trim($data['message']),
        ]);

        NotificationService::notifyAdmins(
            title: "New Support Ticket #{$ticket->ticket_number}",
            message: "Instructor {$instructor->name} submitted ticket: {$ticket->subject}",
            category: 'support',
            actionUrl: route('admin.tickets.index', ['search' => $ticket->ticket_number]),
            actionLabel: 'Review Ticket',
            icon: 'support',
        );

        return back()->with('success', "Ticket #{$ticket->ticket_number} submitted successfully. Our team will review and respond soon.");
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $instructor = auth('instructor')->user();
        if ($ticket->user_email !== $instructor->email) {
            abort(403);
        }

        $data = $request->validate([
            'followup' => ['required', 'string', 'min:5'],
        ]);

        $newMsg = $ticket->message."\n\n--- Follow-up by Instructor (".now()->format('M d, Y H:i').") ---\n".trim($data['followup']);

        $ticket->update([
            'message' => $newMsg,
            'status' => 'open',
        ]);

        NotificationService::notifyAdmins(
            title: "Follow-up on Ticket #{$ticket->ticket_number}",
            message: "Instructor {$instructor->name} posted a follow-up: ".Str::limit($data['followup'], 120),
            category: 'support',
            actionUrl: route('admin.tickets.index', ['search' => $ticket->ticket_number]),
            actionLabel: 'View Ticket',
            icon: 'support',
        );

        return back()->with('success', 'Follow-up message sent to support desk.');
    }

    public function close(SupportTicket $ticket): RedirectResponse
    {
        $instructor = auth('instructor')->user();
        if ($ticket->user_email !== $instructor->email) {
            abort(403);
        }

        $ticket->update(['status' => 'closed']);

        return back()->with('success', "Ticket #{$ticket->ticket_number} marked as closed.");
    }
}
