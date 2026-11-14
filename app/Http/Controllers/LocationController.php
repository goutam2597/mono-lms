<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\JsonResponse;

/**
 * Lightweight JSON feeds powering the cascading country → state → city
 * dropdowns. Public (read-only, active rows only) so guest-facing forms can
 * use them without authentication.
 */
class LocationController extends Controller
{
    public function states(): JsonResponse
    {
        $countryId = request()->integer('country_id');

        if (! $countryId) {
            return response()->json([]);
        }

        return response()->json(
            State::where('country_id', $countryId)
                ->active()
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }

    public function cities(): JsonResponse
    {
        $stateId = request()->integer('state_id');

        if (! $stateId) {
            return response()->json([]);
        }

        return response()->json(
            City::where('state_id', $stateId)
                ->active()
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }
}
