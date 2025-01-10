<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestockHistoryResource\Pages;
use App\Filament\Resources\RestockHistoryResource\RelationManagers;
use App\Models\RestockHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestockHistoryResource extends Resource
{
    protected static ?string $model = RestockHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'History';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('KODE')
                    ->label('Kode Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_pereaksi')
                    ->label('Nama Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_restock')
                    ->label('Jumlah Penambahan Stock')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Penambahan Stock')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
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
            ->actions([
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
            'index' => Pages\ListRestockHistories::route('/'),
            'create' => Pages\CreateRestockHistory::route('/create'),
            'edit' => Pages\EditRestockHistory::route('/{record}/edit'),
        ];
    }
}
