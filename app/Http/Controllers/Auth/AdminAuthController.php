<?php

namespace App\Http\Controllers\Auth;

class AdminAuthController extends PanelAuthController
{
    protected function guard(): string
    {
        return 'admin';
    }

    protected function loginView(): string
    {
        return 'pages.auth.admin-login';
    }

    protected function panelHome(): string
    {
        return 'admin.dashboard';
    }

    protected function panelLabelKey(): string
    {
        return 'admin.panel';
    }
}
