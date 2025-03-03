<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsageHistoryResource\Pages;
use App\Filament\Resources\UsageHistoryResource\RelationManagers;
use App\Models\UsageHistory;
use App\Models\User;
use App\Models\Pereaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UsageHistoryResource extends Resource
{
    protected static ?string $model = UsageHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'History';
    protected static ?string $label = 'Usage History';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('nama_analis')
                    ->label('Nama Analis')
                    ->options(User::all()->pluck('name', 'name')->toArray()) // Ambil nama dari tabel User
                    ->reactive()
                    ->searchable()
                    ->required()
                    ->default(auth()->user()->name), // Default ke nama user yang login
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
                        }
                    }),
                TextInput::make('kode_reagent')
                    ->label('Kode Reagent')
                    ->disabled()
                    ->required(),
                TextInput::make('jenis_reagent')
                    ->label('Jenis Reagent')
                    ->disabled(),
                Select::make('jumlah_penggunaan')
                    ->label('Jumlah Penggunaan (Gram)')
                    ->options([
                        4 => '4',
                        8 => '8',
                    ])
                    ->required(),
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
            'index' => Pages\ListUsageHistories::route('/'),
            'create' => Pages\CreateUsageHistory::route('/create'),
            'edit' => Pages\EditUsageHistory::route('/{record}/edit'),
        ];
    }
}
