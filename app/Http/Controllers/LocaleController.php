<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the storefront language and remember the choice.
     */
    public function switchStore(Request $request, string $code)
    {
        $language = Language::active()->firstWhere('code', $code);

        abort_unless($language, 404);

        $request->session()->put('store_locale', $language->code);
        $request->session()->put('locale', $language->code);

        // Persist to the student account when signed in.
        $student = auth('student')->user();
        $student?->update(['locale' => $language->code]);

        return redirect()->back();
    }

    /**
     * Switch the admin panel language (independent session key).
     */
    public function switchAdmin(Request $request, string $code)
    {
        $language = Language::active()->firstWhere('code', $code);

        abort_unless($language, 404);

        $request->session()->put('admin_locale', $language->code);

        auth('admin')->user()?->update(['locale' => $language->code]);

        return redirect()->back();
    }

    /**
     * Switch the instructor panel language (independent session key).
     */
    public function switchInstructor(Request $request, string $code)
    {
        $language = Language::active()->firstWhere('code', $code);

        abort_unless($language, 404);

        $request->session()->put('instructor_locale', $language->code);

        auth('instructor')->user()?->update(['locale' => $language->code]);

        return redirect()->back();
    }

    /**
     * Switch the student panel language (independent session key).
     */
    public function switchStudent(Request $request, string $code)
    {
        $language = Language::active()->firstWhere('code', $code);

        abort_unless($language, 404);

        $request->session()->put('student_locale', $language->code);

        auth('student')->user()?->update(['locale' => $language->code]);

        return redirect()->back();
    }
}
