<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use App\Models\UsageHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TotalUsagePereaksi extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                UsageHistory::query()
                    ->select(
                        'KODE',
                        'jenis_pereaksi',
                        DB::raw('SUM(jumlah_penggunaan) as total_penggunaan'),
                        DB::raw('MIN(id) as id') // Menambahkan id untuk primary key
                    )
                    ->groupBy('KODE', 'jenis_pereaksi')
            )
            ->columns([
                TextColumn::make('KODE')
                    ->label('Kode Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jenis_pereaksi')
                    ->label('Nama Pereaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_penggunaan')
                    ->label('Total Penggunaan')
                    ->sortable()
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->defaultSort('total_penggunaan', 'desc')
            ->recordUrl(null); // Menonaktifkan link pada baris tabel
    }

    public function getTableRecordKey(mixed $record): string
    {
        return (string) $record->KODE; // Menggunakan KODE sebagai unique identifier
    }
    protected static ?int $sort = 3;
}
