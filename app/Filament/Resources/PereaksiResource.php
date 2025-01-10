<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PereaksiResource\Pages;
use App\Filament\Resources\PereaksiResource\RelationManagers;
use App\Models\Pereaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;


class PereaksiResource extends Resource
{
    protected static ?string $model = Pereaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Pereaksi';
    protected static ?string $slug = 'stock-pereaksi';
    protected static ?string $label = 'Stock Pereaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('KODE')
                    ->required()
                    ->label('Kode Reagent'),
                TextInput::make('ITEM')
                    ->required()
                    ->label('Nama Reagent'),
                TextInput::make('TYPE')
                    ->required()
                    ->label('Jenis Reagent')
                    ->datalist([
                        'Irritant Chemicals',
                        'Harmful Chemicals',
                        'Toxic Chemicals',
                        'Oxidizing Chemicals',
                        'Flammable Chemicals',
                        'Corossive Chemicals',
                        'Microbiological Medium',
                        'Buffer Solution',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('KODE')
                    ->label('Kode Reagent')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ITEM')
                    ->label('Nama Reagent')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('TYPE')
                    ->label('Jenis Reagent')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Stock')
                    ->label('Stock')
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPereaksis::route('/'),
            'create' => Pages\CreatePereaksi::route('/create'),
            'edit' => Pages\EditPereaksi::route('/{record}/edit'),
        ];
    }
    protected function getTableQuery(): Builder
    {
        return Pereaksi::query()->orderBy('Kode');
    }
}
