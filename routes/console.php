<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command('audit-logs:prune')
    ->daily()
    ->at('01:00')
    ->withoutOverlapping()
    ->runInBackground();

