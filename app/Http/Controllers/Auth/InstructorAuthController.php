<?php

namespace App\Http\Controllers\Auth;

class InstructorAuthController extends PanelAuthController
{
    protected function guard(): string
    {
        return 'instructor';
    }

    protected function loginView(): string
    {
        return 'pages.auth.instructor-login';
    }

    protected function panelHome(): string
    {
        return 'instructor.dashboard';
    }

    protected function panelLabelKey(): string
    {
        return 'instructor.panel';
    }
}
