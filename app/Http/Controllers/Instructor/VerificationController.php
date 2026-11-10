<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /**
     * Show instructor verification status and submission form.
     */
    public function show(): View
    {
        $instructor = auth('instructor')->user();

        return view('pages.instructor.verification', [
            'instructor' => $instructor,
        ]);
    }

    /**
     * Handle verification document submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        $instructor = auth('instructor')->user();

        $request->validate([
            'certification_file' => [$instructor->certification_file ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'national_id_file' => [$instructor->national_id_file ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'certification_file.required' => 'Please upload your instructor certification or diploma.',
            'national_id_file.required' => 'Please upload your government-issued national ID or passport.',
        ]);

        $updates = [
            'verification_status' => 'pending',
            'rejection_reason' => null,
        ];

        if ($request->hasFile('certification_file')) {
            $updates['certification_file'] = $request->file('certification_file')->store('verifications/certificates', 'public');
        }

        if ($request->hasFile('national_id_file')) {
            $updates['national_id_file'] = $request->file('national_id_file')->store('verifications/national_ids', 'public');
        }

        $instructor->update($updates);

        return redirect()->route('instructor.verification.show')->with('status', 'Verification documents submitted successfully! Our administrative team will review your application shortly.');
    }
}
