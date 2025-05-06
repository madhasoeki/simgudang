<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('opname:generate')
    ->monthlyOn(1, '00:00')
    ->timezone('Asia/Jakarta')
    ->description('Generate monthly opname data');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
