<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cancel unpaid course orders whose 60-minute payment reservation expired
// (mono_market pattern: scheduler + lazy sweep in the student orders pages).
Schedule::command('orders:cancel-expired')->everyMinute()->withoutOverlapping();

// Ostad-style cohort reminders: assignment deadlines (24h ahead) and live
// class notices (24h + 1h ahead). Dedup keys in NotificationService make
// frequent runs idempotent.
Schedule::command('notifications:assignment-deadlines')->everyFifteenMinutes()->withoutOverlapping();
Schedule::command('notifications:live-class-reminders')->everyFifteenMinutes()->withoutOverlapping();
