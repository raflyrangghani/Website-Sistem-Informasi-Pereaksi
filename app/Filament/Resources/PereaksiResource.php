<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PereaksiResource\Pages;
use App\Filament\Resources\PereaksiResource\RelationManagers;
use App\Models\Pereaksi;
use App\Filament\Imports\PereaksiImporter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ImportAction;
use Filament\Forms\Components\Select;


class PereaksiResource extends Resource
{
    protected static ?string $model = Pereaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Reagent';
    protected static ?string $slug = 'reagent';
    protected static ?string $label = 'Reagent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_reagent')
                    ->required()
                    ->unique(table: Pereaksi::class) 
                    ->label('Kode Reagent')
                    ->placeholder('Masukkan kode reagent'),
                TextInput::make('nama_reagent')
                    ->required()
                    ->label('Nama Reagent')
                    ->placeholder('Masukkan nama reagent'),
                Select::make('jenis_reagent')
                    ->required()
                    ->label('Jenis Reagent')
                    ->options([
                        'Corrosive Chemicals' => 'Corrosive Chemicals',
                        'Flammable Chemicals' => 'Flammable Chemicals',
                        'Harmful Chemicals' => 'Harmful Chemicals',
                        'Irritant Chemicals' => 'Irritant Chemicals',
                        'Oxidizing Chemicals' => 'Oxidizing Chemicals',
                        'Toxic Chemicals' => 'Toxic Chemicals',
                    ])
                    ->placeholder('Masukkan jenis reagent'),
                TextInput::make('Stock')
                    ->required()
                    ->numeric()
                    ->label('Jumlah Stock')
                    ->placeholder('Masukkan jumlah stock'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_reagent')
                    ->label('Kode Reagent')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_reagent')
                    ->label('Nama Reagent')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jenis_reagent')
                    ->label('Jenis Reagent')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Stock')
                    ->label('Stock (ml/g)')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()->importer(PereaksiImporter::class)
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
        return Pereaksi::query()->orderBy('kode_reagent');
    }

    public static function getRecordRouteKeyName(): string
    {
        return 'kode_reagent'; // Gunakan ID sebagai parameter di URL
    }
}
