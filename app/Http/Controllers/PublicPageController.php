<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Send in-app notification to administrators
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                NotificationService::send(
                    $admin,
                    'New Inbound Contact Message: '.$validated['subject'],
                    "From {$validated['name']} ({$validated['email']}): ".Str::limit($validated['message'], 100),
                    'support',
                    route('admin.messages.index'),
                    'View Messages',
                    'mail',
                    false,
                    ['contact_message_id' => $contactMessage->id]
                );
            }
        } catch (\Throwable $e) {
            // Log but don't break user submission
            report($e);
        }

        return back()->with('success', 'Thank you for reaching out! Your message has been received successfully. Our team will review and respond shortly.');
    }
}
