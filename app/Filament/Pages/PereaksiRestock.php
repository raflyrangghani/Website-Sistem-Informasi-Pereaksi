<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pereaksi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use App\Models\RestockHistory;


class PereaksiRestock extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static string $view = 'filament.pages.pereaksi-restock';
    protected static ?string $navigationLabel = 'Reagent Restocking';
    protected static ?string $title = 'Reagent Restocking';
    protected static ?string $navigationGroup = 'Form';
    
    public $jenis_reagent = null;
    public $nama_reagent = null;
    public $kode_reagent = null; // Tambahkan untuk menyimpan kode_reagent
    public $jumlah;

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 2]) // Create a grid with 2 columns
                ->schema([
                    Select::make('nama_reagent')
                        ->label('Nama Reagent')
                        ->options(Pereaksi::all()->pluck('nama_reagent', 'nama_reagent')->toArray())
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->required()
                        ->afterStateUpdated(fn($state, callable $set) => $this->setJenisType($state, $set))
                        ->placeholder('Pilih nama reagent'),
                    TextInput::make('jenis_reagent')
                        ->label('Jenis Reagent')
                        ->disabled()
                        ->default($this->jenis_reagent),
                ]),
            TextInput::make('jumlah')
                ->label('Jumlah Restock (Gram)')
                ->numeric()
                ->required()
                ->minValue(1),
        ];
    }

    protected function setJenisType($state, callable $set)
    {
        $pereaksi = Pereaksi::where('nama_reagent', $state)->first();
        if ($pereaksi) {
            $this->jenis_reagent = $pereaksi->jenis_reagent;
            $this->nama_reagent = $pereaksi->nama_reagent;
            $this->kode_reagent = $pereaksi->kode_reagent; // Simpan kode_reagent
            $set('jenis_reagent', $pereaksi->jenis_reagent);
        }
    }

    public function submit()
    {
        $data = $this->form->getState();  // Mengambil data dari form

        $pereaksi = Pereaksi::where('nama_reagent', $data['nama_reagent'])->first();

        if ($pereaksi) {
            
            RestockHistory::create([
                'kode_reagent' => $pereaksi->kode_reagent, 
                'nama_reagent' => $pereaksi->nama_reagent,
                'jenis_reagent' => $pereaksi->jenis_reagent,
                'jumlah_restock' => $data['jumlah'],
            ]);

            Notification::make()
                ->title('Saved successfully')
                ->icon('heroicon-o-check-circle')
                ->iconColor('success')
                ->send();  // Menampilkan pesan sukses
        } else {
            Notification::make()
                ->title('Reagent not found')
                ->icon('heroicon-o-x-circle')
                ->iconColor('error')
                ->send();  // Menampilkan pesan error
            
        }
    }
}
