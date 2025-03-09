<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SummaryResource\Pages;
use App\Filament\Resources\SummaryResource\RelationManagers;
use App\Models\Summary;
use App\Models\UsageHistory;
use App\Filament\Exports\SummaryExporter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\Select as FormSelect;

class SummaryResource extends Resource
{
    protected static ?string $model = Summary::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_reagent')
                    ->label('Nama Reagent')
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('total_penggunaan')
                    ->label('Total Penggunaan (Gram)')
                    ->required()
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_reagent')
                    ->label('Nama Reagent')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_penggunaan')
                    ->label('Total Penggunaan')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->label('Total Keseluruhan'),
                        Tables\Columns\Summarizers\Count::make()->label('Jumlah Entri'),
                    ])
                    ->suffix(fn (Summary $record) => ' ' . $record->satuan),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('From'),
                        DatePicker::make('end_date')
                            ->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Simpan filter ke session atau state sementara
                        if ($data['start_date'] || $data['end_date']) {
                            Summary::updateSummaryWithFilters($data['start_date'], $data['end_date']);
                        } else {
                            Summary::updateSummary(); // Reset ke semua data jika filter kosong
                        }
                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['start_date']) {
                            $indicators[] = 'Dari: ' . $data['start_date'];
                        }
                        if ($data['end_date']) {
                            $indicators[] = 'Sampai: ' . $data['end_date'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(SummaryExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export CSV/XLSX')
                    ->exporter(SummaryExporter::class)
                    ->fileName(fn (Export $export): string => "Laporan-Penggunaan-Reagent-{$export->getKey()}")
                    ->color('success') // Warna hijau
                    ->icon('heroicon-o-table-cells')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfMonth())
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->default(now()->endOfMonth())
                            ->required(),
                        FormSelect::make('jenis_reagent')
                            ->label('Jenis Reagent')
                            ->options(\App\Models\Pereaksi::pluck('jenis_reagent', 'jenis_reagent')->unique()->toArray())
                            ->multiple()
                            ->placeholder('Pilih jenis reagent'),
                        FormSelect::make('nama_reagent')
                            ->label('Nama Reagent')
                            ->options(Summary::pluck('nama_reagent', 'nama_reagent')->toArray())
                            ->multiple()
                            ->placeholder('Pilih nama reagent'),
                    ])
                    ->before(function (array $data) {
                        // Hitung ulang summary berdasarkan filter sebelum ekspor
                        $query = UsageHistory::groupBy('nama_reagent')
                            ->selectRaw('nama_reagent, SUM(jumlah_penggunaan) as total_penggunaan')
                            ->when($data['start_date'], fn ($q) => $q->where('created_at', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->where('created_at', '<=', $data['end_date']))
                            ->when($data['jenis_reagent'], fn ($q) => $q->whereHas('pereaksi', fn ($q) => $q->whereIn('jenis_reagent', $data['jenis_reagent'])))
                            ->when($data['nama_reagent'], fn ($q) => $q->whereIn('nama_reagent', $data['nama_reagent']));

                        $summaries = $query->get();

                        Summary::truncate();
                        foreach ($summaries as $summary) {
                            Summary::create([
                                'nama_reagent' => $summary->nama_reagent,
                                'total_penggunaan' => $summary->total_penggunaan,
                            ]);
                        }
                    }),
                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfMonth())
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->default(now()->endOfMonth())
                            ->required(),
                        FormSelect::make('jenis_reagent')
                            ->label('Jenis Reagent')
                            ->options(\App\Models\Pereaksi::pluck('jenis_reagent', 'jenis_reagent')->unique()->toArray())
                            ->multiple()
                            ->placeholder('Pilih jenis reagent'),
                        FormSelect::make('nama_reagent')
                            ->label('Nama Reagent')
                            ->options(Summary::pluck('nama_reagent', 'nama_reagent')->toArray())
                            ->multiple()
                            ->placeholder('Pilih nama reagent'),
                    ])
                    ->action(function (array $data) {
                        $query = UsageHistory::groupBy('nama_reagent')
                            ->selectRaw('nama_reagent, SUM(jumlah_penggunaan) as total_penggunaan')
                            ->when($data['start_date'], fn ($q) => $q->where('created_at', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->where('created_at', '<=', $data['end_date']))
                            ->when($data['jenis_reagent'], fn ($q) => $q->whereHas('pereaksi', fn ($q) => $q->whereIn('jenis_reagent', $data['jenis_reagent'])))
                            ->when($data['nama_reagent'], fn ($q) => $q->whereIn('nama_reagent', $data['nama_reagent']));

                        $summaries = $query->get();

                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.summary-report', [
                            'summaries' => $summaries,
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date']
                        ]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Laporan-Penggunaan-Reagent-' . now()->format('YmdHis') . '.pdf'
                        );
                    }),
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
            'index' => Pages\ListSummaries::route('/'),
            'create' => Pages\CreateSummary::route('/create'),
            'edit' => Pages\EditSummary::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (request()->has('tableFilters')) {
            $filters = request()->get('tableFilters');
            if (!empty($filters['date_range']['start_date']) || !empty($filters['date_range']['end_date'])) {
                Summary::updateSummaryWithFilters(
                    $filters['date_range']['start_date'] ?? null,
                    $filters['date_range']['end_date'] ?? null
                );
            }
        }

        return $query;
    }
}
