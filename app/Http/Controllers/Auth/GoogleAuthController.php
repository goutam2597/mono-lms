<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(string $panel): RedirectResponse
    {
        if (! $this->panelAllowed($panel)) {
            return redirect()->route($panel === 'student' ? 'login' : 'instructor.login')
                ->withErrors(['email' => __('Google login is not available right now.')]);
        }

        $this->configureGoogle($panel);

        return Socialite::driver('google')
            ->redirectUrl($this->callbackUrl($panel))
            ->redirect();
    }

    public function callback(Request $request, string $panel): RedirectResponse
    {
        if (! $this->panelAllowed($panel)) {
            return redirect()->route($panel === 'student' ? 'login' : 'instructor.login')
                ->withErrors(['email' => __('Google login is not available right now.')]);
        }

        $this->configureGoogle($panel);

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl($this->callbackUrl($panel))
                ->user();
        } catch (\Throwable $e) {
            return redirect()->route($panel === 'student' ? 'login' : 'instructor.login')
                ->withErrors(['email' => __('Google sign-in failed. Please try again.')]);
        }

        if ($panel === 'student') {
            return $this->loginStudent($request, $googleUser);
        }

        return $this->loginInstructor($request, $googleUser);
    }

    private function loginStudent(Request $request, $googleUser): RedirectResponse
    {
        $student = Student::where('email', $googleUser->getEmail())->first();

        if (! $student) {
            $student = Student::create([
                'name' => $googleUser->getName() ?: ($googleUser->user['given_name'] ?? 'Student'),
                'email' => $googleUser->getEmail(),
                'password' => bin2hex(random_bytes(16)),
                'avatar' => $this->storeGoogleAvatar($googleUser),
                'status' => 'active',
            ]);
        } else {
            $this->mergeGoogleProfile($student, $googleUser);
        }

        if ($student->status !== 'active') {
            return redirect()->route('login')->withErrors(['email' => __('auth.account_inactive')]);
        }

        Auth::guard('student')->login($student, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('student.dashboard'));
    }

    private function loginInstructor(Request $request, $googleUser): RedirectResponse
    {
        $instructor = Instructor::where('email', $googleUser->getEmail())->first();

        if (! $instructor) {
            // Auto-provision on first Google sign-in: newly registered instructors
            // require credential verification before they can add courses or be listed.
            $instructor = Instructor::create([
                'name' => $googleUser->getName() ?: ($googleUser->user['given_name'] ?? 'Instructor'),
                'email' => $googleUser->getEmail(),
                'password' => bin2hex(random_bytes(16)),
                'avatar' => $this->storeGoogleAvatar($googleUser),
                'status' => 'pending_verification',
                'verification_status' => 'unverified',
            ]);
        }

        if ($instructor->status === 'inactive') {
            return redirect()->route('instructor.login')->withErrors(['email' => __('auth.account_inactive')]);
        }

        $this->mergeGoogleProfile($instructor, $googleUser);

        Auth::guard('instructor')->login($instructor, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('instructor.dashboard'));
    }

    /**
     * Link the Google account to an existing user and backfill any profile
     * fields the user has not set yet. Existing data is never overwritten.
     */
    private function mergeGoogleProfile(Student|Instructor $user, $googleUser): void
    {
        $updates = [];

        if (! $user->google_id) {
            $updates['google_id'] = $googleUser->getId();
        }

        if (blank($user->avatar) && filled($googleUser->getAvatar())) {
            $stored = $this->storeGoogleAvatar($googleUser);

            if ($stored !== null) {
                $updates['avatar'] = $stored;
            }
        }

        if (blank($user->name) && filled($googleUser->getName())) {
            $updates['name'] = $googleUser->getName();
        }

        if ($updates !== []) {
            $user->forceFill($updates)->save();
        }
    }

    /**
     * Download the Google profile photo into the local public disk so the
     * avatar is served from our own storage instead of an external URL.
     */
    private function storeGoogleAvatar($googleUser): ?string
    {
        $url = $googleUser->getAvatar();

        if (blank($url)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get($url);
        } catch (\Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $extension = str_contains((string) $response->header('Content-Type'), 'png') ? 'png' : 'jpg';
        $path = 'avatars/google/'.date('Y/m').'/'.Str::random(32).'.'.$extension;

        Storage::disk('public')->put($path, $response->body());

        return $path;
    }

    private function configureGoogle(string $panel): void
    {
        config([
            'services.google.client_id' => Setting::get('google_client_id'),
            'services.google.client_secret' => Setting::get('google_client_secret'),
            'services.google.redirect' => $this->callbackUrl($panel),
        ]);
    }

    private function panelAllowed(string $panel): bool
    {
        if (Setting::enabled('google_login_enabled') === false) {
            return false;
        }

        if (blank(Setting::get('google_client_id')) || blank(Setting::get('google_client_secret'))) {
            return false;
        }

        $this->configureGoogle($panel);

        return Setting::enabled($panel === 'student' ? 'google_allow_student' : 'google_allow_instructor');
    }

    private function callbackUrl(string $panel): string
    {
        return route('login.google.callback', ['panel' => $panel]);
    }
}
