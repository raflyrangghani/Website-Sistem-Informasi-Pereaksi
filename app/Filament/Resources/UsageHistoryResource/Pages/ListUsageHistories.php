<?php

namespace App\Filament\Resources\UsageHistoryResource\Pages;

use App\Filament\Resources\UsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsageHistories extends ListRecords
{
    protected static string $resource = UsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
