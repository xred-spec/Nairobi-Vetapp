<?php

use App\Jobs\CancelacionesJob;
use App\Jobs\ReportJob;
use App\Jobs\ReportWeeklyJob;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::job(app(ReportJob::class))->everyMinute();
Schedule::job(app(CancelacionesJob::class))->dailyAt('19:00')->skip(fn() => now()->isSunday());
Schedule::job(app(ReportJob::class))->dailyAt('19:00')->skip(fn() => now()->isSunday());
Schedule::job(app(ReportWeeklyJob::class))->saturdays()->at('19:00');