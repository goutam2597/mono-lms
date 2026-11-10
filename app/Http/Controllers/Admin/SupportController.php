<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SupportTicket;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SupportController extends Controller
{
    /**
     * Display support tickets with searching, status tabs, and priority filters.
     */
    public function tickets(Request $request): View
    {
        $query = SupportTicket::latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('priority') && $request->query('priority') !== 'all') {
            $query->where('priority', $request->query('priority'));
        }

        $tickets = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'urgent_high' => SupportTicket::whereIn('priority', ['urgent', 'high'])->whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('pages.admin.support-tickets', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a newly created support ticket from the admin desk.
     */
    public function storeTicket(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['nullable', 'in:open,in_progress,resolved,closed'],
            'message' => ['required', 'string'],
        ]);

        $ticketNumber = 'TK-'.strtoupper(Str::random(3)).'-'.random_int(1000, 9999);
        while (SupportTicket::where('ticket_number', $ticketNumber)->exists()) {
            $ticketNumber = 'TK-'.strtoupper(Str::random(3)).'-'.random_int(1000, 9999);
        }

        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'user_name' => trim($data['user_name']),
            'user_email' => trim(strtolower($data['user_email'])),
            'subject' => trim($data['subject']),
            'priority' => $data['priority'],
            'status' => $data['status'] ?? 'open',
            'message' => trim($data['message']),
        ]);

        NotificationService::notifyAdmins(
            title: "Support Ticket #{$ticket->ticket_number} Created",
            message: "Ticket created for {$ticket->user_name} ({$ticket->user_email}): {$ticket->subject}",
            category: 'support',
            actionUrl: route('admin.tickets.index', ['search' => $ticket->ticket_number]),
            actionLabel: 'View Ticket',
            icon: 'support',
        );

        return back()->with('success', "Ticket #{$ticket->ticket_number} created successfully.");
    }

    /**
     * Update ticket attributes (status and priority).
     */
    public function updateTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'in:open,in_progress,resolved,closed'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
        ]);

        $ticket->update(array_filter($data, fn ($val) => ! is_null($val)));

        return back()->with('success', "Ticket #{$ticket->ticket_number} updated.");
    }

    /**
     * Record a staff reply on a support ticket, update its status, and notify the user.
     */
    public function replyTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'reply' => ['required', 'string'],
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'send_email' => ['nullable', 'boolean'],
        ]);

        $ticket->update([
            'reply' => trim($data['reply']),
            'status' => $data['status'],
            'replied_at' => now(),
        ]);

        // Attempt user in-app notification if they match a student/instructor
        $matchedUser = $ticket->findUser();
        if ($matchedUser) {
            NotificationService::send(
                notifiable: $matchedUser,
                title: "Staff response on ticket #{$ticket->ticket_number}",
                message: Str::limit($data['reply'], 160),
                category: 'support',
                actionUrl: null,
                actionLabel: null,
                icon: 'support',
                sendMail: $request->boolean('send_email', true),
            );
        } elseif ($request->boolean('send_email', true) && ! empty($ticket->user_email)) {
            // Direct email to requester
            try {
                Mail::raw(
                    "Hello {$ticket->user_name},\n\nOur support team has updated your ticket (#{$ticket->ticket_number} - {$ticket->subject}):\n\n{$data['reply']}\n\nCurrent Status: ".ucfirst(str_replace('_', ' ', $ticket->status))."\n\nThank you,\nMono LMS Support Team",
                    function ($mail) use ($ticket) {
                        $mail->to($ticket->user_email)
                            ->subject("Re: [Ticket #{$ticket->ticket_number}] {$ticket->subject}");
                    }
                );
            } catch (Throwable $e) {
                Log::warning("Could not send email for ticket reply #{$ticket->ticket_number}: ".$e->getMessage());
            }
        }

        return back()->with('success', "Staff response recorded and sent for ticket #{$ticket->ticket_number}.");
    }

    /**
     * Delete a single support ticket.
     */
    public function destroyTicket(SupportTicket $ticket): RedirectResponse
    {
        $num = $ticket->ticket_number;
        $ticket->delete();

        return back()->with('success', "Ticket #{$num} removed.");
    }

    /**
     * Perform bulk operations on support tickets (status update, delete).
     */
    public function bulkTickets(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:delete,status_open,status_in_progress,status_resolved,status_closed'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:support_tickets,id'],
        ]);

        $ids = $data['ids'];

        if ($data['action'] === 'delete') {
            $count = SupportTicket::whereIn('id', $ids)->delete();

            return back()->with('success', "{$count} tickets deleted successfully.");
        }

        $newStatus = str_replace('status_', '', $data['action']);
        $count = SupportTicket::whereIn('id', $ids)->update(['status' => $newStatus]);

        return back()->with('success', "Status of {$count} tickets changed to ".ucfirst(str_replace('_', ' ', $newStatus)).'.');
    }

    /**
     * Display contact messages with keyword searching and read/unread filters.
     */
    public function messages(Request $request): View
    {
        $query = ContactMessage::latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $tab = $request->query('tab', 'all');
        if ($tab === 'unread') {
            $query->where('is_read', false);
        } elseif ($tab === 'read') {
            $query->where('is_read', true);
        } elseif ($tab === 'replied') {
            $query->whereNotNull('replied_at');
        }

        $messages = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'read' => ContactMessage::where('is_read', true)->count(),
            'replied' => ContactMessage::whereNotNull('replied_at')->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
        ];

        return view('pages.admin.contact-messages', [
            'messages' => $messages,
            'stats' => $stats,
            'unreadCount' => $stats['unread'],
            'currentTab' => $tab,
        ]);
    }

    /**
     * Send a staff response to an inbound contact message via email.
     */
    public function replyMessage(Request $request, ContactMessage $message): RedirectResponse
    {
        $data = $request->validate([
            'reply' => ['required', 'string'],
            'subject' => ['nullable', 'string', 'max:255'],
            'send_email' => ['nullable', 'boolean'],
        ]);

        $replySubject = trim((string) ($data['subject'] ?? ''));
        if ($replySubject === '') {
            $replySubject = 'Re: '.$message->subject;
        }

        $message->update([
            'reply' => trim($data['reply']),
            'replied_at' => now(),
            'is_read' => true,
        ]);

        if ($request->boolean('send_email', true) && ! empty($message->email)) {
            try {
                Mail::raw(
                    "Hello {$message->name},\n\nThank you for contacting Mono LMS regarding \"{$message->subject}\".\n\n{$data['reply']}\n\nBest regards,\nMono LMS Support Desk",
                    function ($mail) use ($message, $replySubject) {
                        $mail->to($message->email)
                            ->subject($replySubject);
                    }
                );
            } catch (Throwable $e) {
                Log::warning("Could not send email for message reply #{$message->id}: ".$e->getMessage());
            }
        }

        return back()->with('success', "Reply recorded and sent to {$message->email}.");
    }

    /**
     * Toggle read/unread status on a contact message.
     */
    public function toggleMessageRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        $statusText = $message->is_read ? 'marked as read' : 'marked as unread';

        return back()->with('success', "Message {$statusText}.");
    }

    /**
     * Delete a single contact message.
     */
    public function destroyMessage(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }

    /**
     * Bulk actions for contact messages (delete, mark read, mark unread).
     */
    public function bulkMessages(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:delete,mark_read,mark_unread'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:contact_messages,id'],
        ]);

        $ids = $data['ids'];

        if ($data['action'] === 'delete') {
            $count = ContactMessage::whereIn('id', $ids)->delete();

            return back()->with('success', "Deleted {$count} messages successfully.");
        }

        if ($data['action'] === 'mark_read') {
            $count = ContactMessage::whereIn('id', $ids)->update(['is_read' => true]);

            return back()->with('success', "{$count} messages marked as read.");
        }

        if ($data['action'] === 'mark_unread') {
            $count = ContactMessage::whereIn('id', $ids)->update(['is_read' => false]);

            return back()->with('success', "{$count} messages marked as unread.");
        }

        return back();
    }

    /**
     * Legacy bulk destroy endpoint compatibility.
     */
    public function bulkDestroyMessages(Request $request): RedirectResponse
    {
        $request->merge(['action' => 'delete']);

        return $this->bulkMessages($request);
    }

    /**
     * Export contact messages to CSV.
     */
    public function exportMessages(Request $request): StreamedResponse
    {
        $messages = ContactMessage::latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="contact_messages_'.date('Y-m-d_His').'.csv"',
        ];

        return response()->stream(function () use ($messages) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Is Read', 'Replied At', 'Staff Reply', 'Created At']);

            foreach ($messages as $msg) {
                fputcsv($handle, [
                    $msg->id,
                    $msg->name,
                    $msg->email,
                    $msg->phone ?? '',
                    $msg->subject,
                    $msg->message,
                    $msg->is_read ? 'Yes' : 'No',
                    $msg->replied_at ? $msg->replied_at->toDateTimeString() : '',
                    $msg->reply ?? '',
                    $msg->created_at ? $msg->created_at->toDateTimeString() : '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
