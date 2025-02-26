<?php

namespace App\Filament\Imports;

use App\Models\Pereaksi;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PereaksiImporter extends Importer
{
    protected static ?string $model = Pereaksi::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_reagent')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_reagent')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jenis_reagent')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('Stock')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Pereaksi
    {
        return Pereaksi::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'kode_reagent' => $this->data['kode_reagent'],
        ]);

        // return new Pereaksi();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pereaksi import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
