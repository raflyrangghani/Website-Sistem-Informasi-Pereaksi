<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PereaksiResource\Pages;
use App\Filament\Resources\PereaksiResource\RelationManagers;
use App\Models\Pereaksi;
use App\Filament\Imports\PereaksiImporter;
use App\Filament\Exports\PereaksiExporter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Actions\Exports\Models\Export;
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
                    ->unique(
                        table: Pereaksi::class,
                        column: 'kode_reagent',
                        ignorable: fn ($record) => $record
                    ) 
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
                        'Corrosive' => 'Corrosive',
                        'Flammable' => 'Flammable',
                        'Harmful' => 'Harmful',
                        'Irritant' => 'Irritant',
                        'Oxidizing' => 'Oxidizing',
                        'Toxic' => 'Toxic',
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
                SelectFilter::make('jenis_reagent')
                    ->label('Jenis Reagent')
                    ->options(Pereaksi::distinct()->pluck('jenis_reagent', 'jenis_reagent')->toArray())
                    ->multiple()
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['values'], fn (Builder $q) => $q->whereIn('jenis_reagent', $data['values'])))
                    ->placeholder('Pilih jenis reagent'),
                Filter::make('stock_range')
                    ->form([
                        TextInput::make('stock_min')
                            ->label('Stok Minimum')
                            ->numeric()
                            ->placeholder('Masukkan stok minimum'),
                        TextInput::make('stock_max')
                            ->label('Stok Maksimum')
                            ->numeric()
                            ->placeholder('Masukkan stok maksimum'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['stock_min'], fn (Builder $q) => $q->where('Stock', '>=', $data['stock_min']))
                            ->when($data['stock_max'], fn (Builder $q) => $q->where('Stock', '<=', $data['stock_max']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['stock_min']) {
                            $indicators[] = 'Stok Min: ' . $data['stock_min'];
                        }
                        if ($data['stock_max']) {
                            $indicators[] = 'Stok Max: ' . $data['stock_max'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('edit_multiple')
                        ->label('Edit Multiple')
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Select::make('jenis_reagent')
                                ->label('Jenis Reagent')
                                ->options([
                                    'Corrosive' => 'Corrosive',
                                    'Flammable' => 'Flammable',
                                    'Harmful' => 'Harmful',
                                    'Irritant' => 'Irritant',
                                    'Oxidizing' => 'Oxidizing',
                                    'Toxic' => 'Toxic',
                                ])
                                ->placeholder('Pilih jenis reagent'),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'jenis_reagent' => $data['jenis_reagent'] ?? $record->jenis_reagent,
                                    'Stock' => $data['Stock'] ?? $record->Stock,
                                ]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export CSV/XLSX')
                    ->exporter(PereaksiExporter::class)
                    ->fileName(fn (Export $export): string => "Stock Opname-{$export->getKey()}")
                    ->color('success')
                    ->icon('heroicon-o-table-cells'),
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
