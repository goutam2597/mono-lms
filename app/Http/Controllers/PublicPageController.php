<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageAdminMail;
use App\Mail\ContactMessageUserConfirmationMail;
use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Services\NotificationService;
use App\Support\MailHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PublicPageController extends Controller
{
    /**
     * Display a public page resolved by its permalink / slug.
     */
    public function show(Request $request, string $slug): View|RedirectResponse
    {
        $page = Page::findBySlugOrKey($slug);

        if (! $page) {
            abort(404);
        }

        // If page is draft, only allow administrators to preview it
        if (! $page->isPublished() && ! auth('admin')->check()) {
            abort(404);
        }

        // If the template is instructors, redirect to the instructors directory
        if ($page->template === 'instructors' && $slug !== 'instructors') {
            return redirect()->route('instructors.index');
        }

        $viewMap = [
            'about' => 'pages.about',
            'contact' => 'pages.contact',
            'privacy' => 'pages.privacy',
            'tnc' => 'pages.tnc',
            'standard' => 'pages.custom',
        ];

        $viewName = $viewMap[$page->template] ?? 'pages.custom';

        return view($viewName, [
            'page' => $page,
            'isDraftPreview' => ! $page->isPublished(),
        ]);
    }

    /**
     * Handle public contact form submissions from the Contact Us page.
     * Open to all users / visitors without login (general inquiry).
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $contactMessage = ContactMessage::create([
            'name' => trim($validated['name']),
            'email' => trim(strtolower($validated['email'])),
            'phone' => ! empty($validated['phone']) ? trim($validated['phone']) : null,
            'subject' => trim($validated['subject']),
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        // Apply SMTP configuration dynamically
        MailHelper::applySmtpConfig();

        // 1. Send in-app notification to all active administrators
        $admins = Admin::where('status', 'active')->get();
        if ($admins->isEmpty()) {
            $admins = Admin::all();
        }

        foreach ($admins as $admin) {
            try {
                NotificationService::send(
                    $admin,
                    'New Contact Inquiry: '.$contactMessage->subject,
                    "From {$contactMessage->name} ({$contactMessage->email}): ".Str::limit($contactMessage->message, 120),
                    'support',
                    route('admin.messages.index', ['search' => $contactMessage->email]),
                    'View Messages',
                    'mail',
                    false,
                    [
                        'contact_message_id' => $contactMessage->id,
                        'sender_name' => $contactMessage->name,
                        'sender_email' => $contactMessage->email,
                    ]
                );
            } catch (\Throwable $e) {
                Log::warning("Could not dispatch in-app notification to admin #{$admin->id}: ".$e->getMessage());
            }
        }

        // 2. Dispatch email copies to all active administrators via SMTP
        foreach ($admins as $admin) {
            if (! empty($admin->email)) {
                try {
                    Mail::to($admin->email)->send(new ContactMessageAdminMail($contactMessage));
                } catch (\Throwable $e) {
                    Log::warning("Could not dispatch contact admin email to {$admin->email}: ".$e->getMessage());
                }
            }
        }

        // 3. Dispatch automated confirmation copy to the user via SMTP
        if (! empty($contactMessage->email)) {
            try {
                Mail::to($contactMessage->email)->send(new ContactMessageUserConfirmationMail($contactMessage));
            } catch (\Throwable $e) {
                Log::warning("Could not dispatch contact confirmation email to {$contactMessage->email}: ".$e->getMessage());
            }
        }

        return back()->with('success', 'Thank you for reaching out! Your message has been received successfully. A confirmation copy has been sent to your email, and our team will review and respond shortly.');
    }
}
