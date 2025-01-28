<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\ReagentUsageWidget;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Providers\FilamentServiceProvider;

class AdminPanelProvider extends FilamentServiceProvider
{
    protected static function getNavigationGroups(): array
    {
        return [
            NavigationGroup::make('History')
                ->items([
                    NavigationItem::make('Usage History')
                        ->url('/usage-history'),
                    NavigationItem::make('Reagent Usage')
                        ->widget(ReagentUsageWidget::class),
                ]),
        ];
    }
}
