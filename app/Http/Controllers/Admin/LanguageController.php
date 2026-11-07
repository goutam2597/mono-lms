<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        return view('pages.admin.languages', [
            'languages' => Language::orderBy('sort_order')->get(),
        ]);
    }

    public function toggle(Language $language)
    {
        if ($language->is_default) {
            return back()->with('error', __('admin.languages.cannot_disable_default'));
        }

        $language->update(['is_active' => ! $language->is_active]);
        Language::clearCache();

        return back()->with('success', __('admin.languages.updated'));
    }

    public function makeDefault(Language $language)
    {
        Language::where('is_default', true)->update(['is_default' => false]);
        $language->update(['is_default' => true, 'is_active' => true]);
        Language::clearCache();

        return back()->with('success', __('admin.languages.default_changed'));
    }
}
