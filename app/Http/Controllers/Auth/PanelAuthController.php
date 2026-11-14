<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\ValidTurnstile;
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

    /** Route name the logout handler returns to (defaults to guard().'.login'). */
    protected function loginRouteName(): string
    {
        return $this->guard().'.login';
    }

    public function showLogin()
    {
        if (Auth::guard($this->guard())->check()) {
            return redirect()->route($this->panelHome());
        }

        return view($this->loginView());
    }

    public function login(Request $request)
    {
        // Web-only bot gate shared by the student and instructor login forms.
        // Passes untouched while Turnstile is disabled. The field is merged in
        // so the rule also runs when the widget never rendered (Laravel skips
        // non-implicit rules for absent fields).
        $request->merge(['cf-turnstile-response' => $request->input('cf-turnstile-response')]);
        $request->validate(['cf-turnstile-response' => [new ValidTurnstile('login')]]);

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

        $isInactive = $this->guard() === 'instructor'
            ? ($user->status === 'inactive')
            : ($user->status !== 'active');

        if ($isInactive) {
            Auth::guard($this->guard())->logout();

            throw ValidationException::withMessages([
                'email' => __('auth.account_inactive'),
            ]);
        }

        $request->session()->regenerate();

        $intended = $request->session()->get('url.intended');
        if ($intended && (str_contains($intended, '/login') || str_contains($intended, '/register'))) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route($this->panelHome()));
    }

    public function logout(Request $request)
    {
        Auth::guard($this->guard())->logout();

        // Only forget this panel's locale session; other panel sessions
        // in the same browser must stay untouched.
        $request->session()->forget($this->guard().'_locale');
        $request->session()->regenerateToken();

        return redirect()->route($this->loginRouteName())->with('status', __('auth.logged_out'));
    }
}
