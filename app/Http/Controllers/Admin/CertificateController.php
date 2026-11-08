<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(): View
    {
        $templates = CertificateTemplate::latest()->get();

        return view('pages.admin.certificate-templates', [
            'templates' => $templates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'template_type' => ['required', 'string', 'in:classic,modern,elegant'],
            'signature_title' => ['required', 'string', 'max:100'],
            'signer_name' => ['required', 'string', 'max:100'],
            'bg_color' => ['required', 'string', 'max:20'],
            'border_color' => ['required', 'string', 'max:20'],
        ]);

        CertificateTemplate::create($data);

        return back()->with('success', "Certificate template '{$data['title']}' created successfully.");
    }

    public function makeDefault(CertificateTemplate $template): RedirectResponse
    {
        CertificateTemplate::query()->update(['is_default' => false]);
        $template->update(['is_default' => true]);

        return back()->with('success', "'{$template->title}' is now the default certificate template.");
    }
}
