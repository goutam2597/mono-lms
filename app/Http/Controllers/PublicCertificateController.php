<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicCertificateController extends Controller
{
    /**
     * Show the public certificate verification page.
     */
    public function show(?string $code = null): View
    {
        $certificate = null;
        $searched = false;

        if ($code) {
            $searched = true;
            $certificate = Certificate::where('certificate_number', trim($code))
                ->with(['student', 'course.instructor', 'template'])
                ->first();
        }

        return view('pages.certificates.verify', [
            'code' => $code,
            'certificate' => $certificate,
            'searched' => $searched,
        ]);
    }

    /**
     * Handle search form submission.
     */
    public function search(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100'],
        ]);

        return redirect()->route('certificates.verify.show', ['code' => trim($validated['code'])]);
    }
}
