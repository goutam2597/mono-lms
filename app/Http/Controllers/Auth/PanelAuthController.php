<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Shared login/logout behaviour for every panel guard. Each panel keeps its
 * own session, so signing into one panel never signs you out of another.
 */
abstract class PanelAuthController extends Controller
{
    abstract protected function guard(): string;

    abstract protected function loginView(): string;

    abstract protected function panelHome(): string;

    abstract protected function panelLabelKey(): string;

    public function showLogin()
    {
        if (Auth::guard($this->guard())->check()) {
            return redirect()->route($this->panelHome());
        }

        return view($this->loginView());
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard($this->guard())->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.invalid_credentials'),
            ]);
        }

        $user = Auth::guard($this->guard())->user();

        if ($user->status !== 'active') {
            Auth::guard($this->guard())->logout();

            throw ValidationException::withMessages([
                'email' => __('auth.account_inactive'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route($this->panelHome()));
    }

    public function logout(Request $request)
    {
        Auth::guard($this->guard())->logout();

        // Only forget this panel's locale session; other panel sessions
        // in the same browser must stay untouched.
        $request->session()->forget($this->guard().'_locale');
        $request->session()->regenerateToken();

        return redirect()->route($this->guard().'.login')->with('status', __('auth.logged_out'));
    }
}
