<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\OutOfStockPereaksi;
use App\Filament\Widgets\TotalUsagePereaksi;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | string | array
    {
        return 3;
    }
    public function getWidgets(): array
    {
        return [
            WelcomeWidget::class,
            TimeWidget::class,
            OutOfStockPereaksi::class,
            TotalUsagePereaksi::class,
        ];
    }
}
