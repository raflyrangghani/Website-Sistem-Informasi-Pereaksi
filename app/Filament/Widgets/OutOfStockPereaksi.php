<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Pereaksi;

class OutOfStockPereaksi extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pereaksi::query()->where('Stock', 0)
            )
            ->columns([
                TextColumn::make('KODE')
                    ->label('Kode Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ITEM')
                    ->label('Nama Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'In Stock' => 'success',
                        'Under Stock' => 'warning',
                        'Out of Stock' => 'danger',
                    })
            ]);
    }
}
