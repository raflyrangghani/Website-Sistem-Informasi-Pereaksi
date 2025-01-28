<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TimeWidget extends Widget
{
    protected static string $view = 'filament.widgets.time-widget';

    public function getCurrentTime(): string
    {
        return date('l, j F Y'); // Current day and time
    }
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
}
