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
