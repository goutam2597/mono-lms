<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * One shared forgot/reset flow for all three panels. The panel is selected
 * with ?panel=student|instructor|admin (default: student) and maps to that
 * guard's password broker, so tokens never cross user types.
 */
class PasswordResetController extends Controller
{
    private const PANELS = ['student', 'instructor', 'admin'];

    public function showForgot(Request $request)
    {
        return view('pages.auth.forgot-password', [
            'panel' => $this->panel($request),
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $panel = $this->panel($request);

        Password::broker($panel)->sendResetLink(
            $request->only('email')
        );

        // Always show the same message: never reveal which emails exist.
        return back()->with('status', __('auth.reset_sent'));
    }

    public function showReset(Request $request, string $token)
    {
        return view('pages.auth.set-password', [
            'panel' => $this->panel($request),
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $panel = $this->panel($request);

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::broker($panel)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route($panel === 'student' ? 'login' : $panel.'.login')->with('status', __('auth.reset_success'));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    private function panel(Request $request): string
    {
        $panel = $request->input('panel', $request->query('panel', 'student'));

        return in_array($panel, self::PANELS, true) ? $panel : 'student';
    }
}
