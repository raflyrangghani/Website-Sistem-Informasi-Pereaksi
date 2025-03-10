<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Kernel;
use App\Models\Setting;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withCommands([
        \App\Console\Commands\SendStockAlert::class,
    ])
    ->withSchedule(function (Schedule $schedule) {
        $enabled = Setting::where('key', 'stock_alert_enabled')->value('value') ?? false;
        if ($enabled) {
            $schedule->command('stock:alert')->everyMinute();
        }
    })
    ->create();
