<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $leads = $query->paginate(12)->withQueryString();

        return view('pages.admin.leads', [
            'leads' => $leads,
            'stages' => [
                'new' => Lead::where('status', 'new')->count(),
                'contacted' => Lead::where('status', 'contacted')->count(),
                'interested' => Lead::where('status', 'interested')->count(),
                'enrolled' => Lead::where('status', 'enrolled')->count(),
                'closed' => Lead::where('status', 'closed')->count(),
            ],
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:new,contacted,interested,enrolled,closed'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $lead->update($data);

        return back()->with('success', "Lead {$lead->name} stage updated to {$data['status']}.");
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('success', 'Lead record removed.');
    }
}
