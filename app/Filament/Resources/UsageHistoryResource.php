<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsageHistoryResource\Pages;
use App\Filament\Resources\UsageHistoryResource\RelationManagers;
use App\Models\UsageHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsageHistoryResource extends Resource
{
    protected static ?string $model = UsageHistory::class;

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
                TextColumn::make('nama_analis')
                    ->label('Nama Analis')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('KODE')
                    ->label('Kode Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jenis_pereaksi')
                    ->label('Jenis Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_penggunaan')
                    ->label('Jumlah Penggunaan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Penggunaan')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
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
            'index' => Pages\ListUsageHistories::route('/'),
            'create' => Pages\CreateUsageHistory::route('/create'),
            'edit' => Pages\EditUsageHistory::route('/{record}/edit'),
        ];
    }
}
