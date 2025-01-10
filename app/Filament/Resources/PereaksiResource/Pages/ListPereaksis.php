<?php

namespace App\Filament\Resources\PereaksiResource\Pages;

use App\Filament\Resources\PereaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPereaksis extends ListRecords
{
    protected static string $resource = PereaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
