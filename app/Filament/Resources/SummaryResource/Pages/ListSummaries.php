<?php

namespace App\Filament\Resources\SummaryResource\Pages;

use App\Filament\Resources\SummaryResource;
use App\Models\Summary;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSummaries extends ListRecords
{
    protected static string $resource = SummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('refresh')
            //     ->label('Refresh')
            //     ->action(function () {
            //         Summary::updateSummary();
            //     })
            //     ->requiresConfirmation()
            //     ->color('primary'),
        ];
    }
}
