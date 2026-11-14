<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Services\PhoneNumberService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Manage the geography catalogue (countries, states, cities) that powers the
 * phone dial codes and cascading location dropdowns. Same structure and flow
 * as the marketplace admin: countries → per-country states → per-state cities,
 * with add/edit modals and row action menus in the views.
 */
class LocationController extends Controller
{
    /* ------------------------- Countries ------------------------- */

    public function countries(): View
    {
        $this->authorizeAdmin();

        return view('pages.admin.settings.countries', [
            'countries' => Country::withCount(['states', 'cities'])
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function storeCountry(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $this->validateCountry($request);

        Country::create($data);
        PhoneNumberService::clearCache();

        return back()->with('success', 'Country added successfully.');
    }

    public function updateCountry(Request $request, Country $country): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $this->validateCountry($request, $country);

        $country->update($data);
        PhoneNumberService::clearCache();

        return back()->with('success', 'Country updated successfully.');
    }

    public function destroyCountry(Country $country): RedirectResponse
    {
        $this->authorizeAdmin();

        $country->delete();
        PhoneNumberService::clearCache();

        return back()->with('success', 'Country and its states/cities deleted.');
    }

    /* ------------------------- States ------------------------- */

    public function states(Request $request, Country $country): View
    {
        $this->authorizeAdmin();

        return view('pages.admin.settings.states', [
            'country' => $country,
            'states' => State::where('country_id', $country->id)
                ->withCount('cities')
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function storeState(Request $request, Country $country): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        State::create([
            'country_id' => $country->id,
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'State added successfully.');
    }

    public function updateState(Request $request, Country $country, State $state): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $state->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'State updated successfully.');
    }

    public function destroyState(Country $country, State $state): RedirectResponse
    {
        $this->authorizeAdmin();

        $state->delete();

        return back()->with('success', 'State and its cities deleted.');
    }

    /* ------------------------- Cities ------------------------- */

    public function cities(Request $request, Country $country, State $state): View
    {
        $this->authorizeAdmin();

        return view('pages.admin.settings.cities', [
            'country' => $country,
            'state' => $state,
            'cities' => City::where('state_id', $state->id)
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function storeCity(Request $request, Country $country, State $state): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        City::create([
            'state_id' => $state->id,
            'country_id' => $country->id,
            'name' => $data['name'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'City added successfully.');
    }

    public function updateCity(Request $request, Country $country, State $state, City $city): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $city->update([
            'name' => $data['name'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'City updated successfully.');
    }

    public function destroyCity(Country $country, State $state, City $city): RedirectResponse
    {
        $this->authorizeAdmin();

        $city->delete();

        return back()->with('success', 'City deleted.');
    }

    /* ------------------------- Shared ------------------------- */

    /**
     * Country rules: an enabled country needs a valid dialing code or phone
     * matching would silently skip it.
     *
     * @return array{name: string, code: string, phone_code: ?string, phone_example: ?string, is_active: bool}
     */
    private function validateCountry(Request $request, ?Country $country = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['required', 'string', 'size:2', $country
                ? Rule::unique('countries', 'code')->ignore($country->id)
                : 'unique:countries,code'],
            'phone_code' => ['nullable', 'string', 'max:10', PhoneNumberService::dialCodeRule()],
            'phone_example' => ['nullable', 'string', 'max:24'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $phoneCode = app(PhoneNumberService::class)->normalizeDialCode($data['phone_code'] ?? null);
        if ((bool) ($data['is_active'] ?? false) && ! $phoneCode) {
            throw ValidationException::withMessages([
                'phone_code' => 'An enabled country needs a valid dialing code (e.g. +880).',
            ]);
        }

        return [
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'phone_code' => $phoneCode,
            'phone_example' => trim((string) ($data['phone_example'] ?? '')) ?: null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }

    private function authorizeAdmin(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin && ($admin->isSuperAdmin() || $admin->hasPermission('locations.manage')), 403);
    }
}
