<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestockHistoryResource\Pages;
use App\Filament\Resources\RestockHistoryResource\RelationManagers;
use App\Models\RestockHistory;
use App\Models\Pereaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestockHistoryResource extends Resource
{
    protected static ?string $model = RestockHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'History';
    protected static ?string $label = 'Restock History';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('nama_reagent')
                    ->label('Nama Reagent')
                    ->options(Pereaksi::all()->pluck('nama_reagent', 'nama_reagent')->toArray())
                    ->reactive()
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $pereaksi = Pereaksi::where('nama_reagent', $state)->first();
                        if ($pereaksi) {
                            $set('kode_reagent', $pereaksi->kode_reagent);
                            $set('jenis_reagent', $pereaksi->jenis_reagent);
                            $set('satuan', $pereaksi->satuan);
                        }
                    }),
                TextInput::make('kode_reagent')
                    ->label('Kode Reagent')
                    ->readOnly() // Ganti disabled() dengan readOnly()
                    ->required(),
                TextInput::make('jenis_reagent')
                    ->label('Jenis Reagent')
                    ->readOnly()
                    ->required(),
                TextInput::make('jumlah_restock')
                    ->label('Jumlah Restock')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                TextInput::make('satuan')
                    ->label('Satuan')
                    ->readOnly(),
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
                TextColumn::make('jumlah_restock')
                    ->label('Jumlah Penambahan Stock')
                    ->sortable()
                    ->searchable()
                    ->suffix(fn (RestockHistory $record) => ' ' . $record->satuan),
                TextColumn::make('created_at')
                    ->label('Tanggal Penambahan Stock')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('From'),
                        DatePicker::make('created_until')
                            ->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRestockHistories::route('/'),
            'create' => Pages\CreateRestockHistory::route('/create'),
            'edit' => Pages\EditRestockHistory::route('/{record}/edit'),
        ];
    }

}
