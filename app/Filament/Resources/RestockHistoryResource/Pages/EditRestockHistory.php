<?php

namespace App\Filament\Resources\RestockHistoryResource\Pages;

use App\Filament\Resources\RestockHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestockHistory extends EditRecord
{
    protected static string $resource = RestockHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
