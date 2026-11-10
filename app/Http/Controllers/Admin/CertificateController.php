<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $templates = CertificateTemplate::withCount('certificates')->latest()->get();
        $issued = Certificate::with(['student', 'course', 'template'])->latest()->paginate(15);

        return view('pages.admin.certificate-templates', [
            'templates' => $templates,
            'issued' => $issued,
            'defaultTemplate' => CertificateTemplate::getDefault(),
            'totalIssued' => Certificate::count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'template_type' => ['required', 'string', 'in:template_3,template_7,template_8,template_9,classic,modern,elegant'],
            'signature_title' => ['required', 'string', 'max:100'],
            'signer_name' => ['required', 'string', 'max:100'],
            'bg_color' => ['required', 'string', 'max:20'],
            'border_color' => ['required', 'string', 'max:20'],
            'accent_color' => ['nullable', 'string', 'max:20'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'show_qr_code' => ['nullable', 'boolean'],
        ]);

        $data['accent_color'] = $data['accent_color'] ?: '#738a6e';
        $data['show_qr_code'] = $request->boolean('show_qr_code', true);

        CertificateTemplate::create($data);

        return back()->with('success', "Certificate template '{$data['title']}' created successfully.");
    }

    public function update(Request $request, CertificateTemplate $template): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'template_type' => ['required', 'string', 'in:template_3,template_7,template_8,template_9,classic,modern,elegant'],
            'signature_title' => ['required', 'string', 'max:100'],
            'signer_name' => ['required', 'string', 'max:100'],
            'bg_color' => ['required', 'string', 'max:20'],
            'border_color' => ['required', 'string', 'max:20'],
            'accent_color' => ['nullable', 'string', 'max:20'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'show_qr_code' => ['nullable', 'boolean'],
        ]);

        $data['accent_color'] = $data['accent_color'] ?: '#738a6e';
        $data['show_qr_code'] = $request->boolean('show_qr_code', true);

        $template->update($data);

        return back()->with('success', "Certificate template '{$template->title}' updated successfully.");
    }

    public function destroy(CertificateTemplate $template): RedirectResponse
    {
        if ($template->is_default) {
            return back()->with('error', 'The default certificate template cannot be deleted. Set another template as default first.');
        }

        if (CertificateTemplate::count() <= 1) {
            return back()->with('error', 'At least one certificate template must remain in the platform.');
        }

        $title = $template->title;
        $template->delete();

        return back()->with('success', "Certificate template '{$title}' removed.");
    }

    public function makeDefault(CertificateTemplate $template): RedirectResponse
    {
        CertificateTemplate::query()->update(['is_default' => false]);
        $template->update(['is_default' => true]);

        return back()->with('success', "'{$template->title}' is now the default certificate template.");
    }

    /**
     * Preview sample certificate with this template in full-screen print view.
     */
    public function preview(CertificateTemplate $template): View
    {
        $student = Student::whereNotNull('avatar')->first() ?? Student::first();

        $dummy = new Certificate([
            'certificate_number' => 'CERT-DEMO-'.strtoupper(bin2hex(random_bytes(3))),
            'student_id' => $student?->id,
            'student_name' => $student?->name ?: 'Alexandre Montgomery',
            'course_title' => 'Executive Full-Stack Engineering & System Architecture',
            'instructor_name' => $template->signer_name ?: 'Dr. Alexander Wright',
            'issue_date' => now(),
            'grade' => 'Highest Academic Distinction (98.5%)',
            'score' => 98.5,
        ]);
        $dummy->id = 0;
        $dummy->setRelation('template', $template);
        if ($student) {
            $dummy->setRelation('student', $student);
        }

        return view('pages.student.certificate-print', [
            'certificate' => $dummy,
            'template' => $template,
        ]);
    }
}
