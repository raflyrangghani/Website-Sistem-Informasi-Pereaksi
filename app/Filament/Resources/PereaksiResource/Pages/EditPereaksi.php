<?php

namespace App\Filament\Resources\PereaksiResource\Pages;

use App\Filament\Resources\PereaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPereaksi extends EditRecord
{
    protected static string $resource = PereaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
