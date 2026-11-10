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
        $request->validate([
            'certification_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'national_id_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'certification_file.required' => 'Please upload your instructor certification or diploma.',
            'national_id_file.required' => 'Please upload your government-issued national ID or passport.',
        ]);

        $instructor = auth('instructor')->user();

        $certPath = $request->file('certification_file')->store('verifications/certificates', 'public');
        $idPath = $request->file('national_id_file')->store('verifications/national_ids', 'public');

        $instructor->update([
            'certification_file' => $certPath,
            'national_id_file' => $idPath,
            'verification_status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()->route('instructor.verification.show')->with('status', 'Verification documents submitted successfully! Our administrative team will review your application shortly.');
    }
}
