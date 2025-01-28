<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pereaksi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
use App\Models\RestockHistory;

class PereaksiRestock extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.pereaksi-restock';
    protected static ?string $navigationLabel = 'Penambahan Stok Pereaksi';
    protected static ?string $title = 'Penambahan Stok Pereaksi';
    protected static ?string $navigationGroup = 'Form';
    public $type;
    public $item;
    public $jumlah;

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 2]) // Create a grid with 2 columns
                ->schema([
                    Select::make('item')
                        ->label('Nama Pereaksi')
                        ->options(Pereaksi::all()->pluck('ITEM', 'KODE')->toArray())
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->required()
                        ->afterStateUpdated(fn($state) => $this->setJenisType($state)),

                    TextInput::make('type')
                        ->label('Jenis Pereaksi')
                        ->disabled()
                        ->default($this->type),
                ]),
            TextInput::make('jumlah')
                ->label('Jumlah Restock (Gram)')
                ->numeric()
                ->required(),
        ];
    }
    protected function setJenisType($state)
    {
        $pereaksi = Pereaksi::find($state);
        if ($pereaksi) {
            $this->type = $pereaksi->TYPE;
        }
    }
    public function submit()
    {
        $data = $this->form->getState();  // Mengambil data dari form

        $pereaksi = Pereaksi::where('KODE', $data['item'])->first();  // Mencari pereaksi berdasarkan KODE
        if ($pereaksi) {
            
            RestockHistory::create([
                'KODE' => $data['item'],
                'nama_pereaksi' => $pereaksi->ITEM,
                'jumlah_restock' => $data['jumlah'],
            ]);

            session()->flash('message', 'Pereaksi berhasil direstock!');  // Menampilkan pesan sukses
        } else {
            session()->flash('error', 'Pereaksi tidak ditemukan!');  // Menampilkan pesan error jika pereaksi tidak ditemukan
        }
    }
}
