<?php

namespace App\Filament\Pages;

use App\Models\Pereaksi;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
use App\Models\UsageHistory;
use App\Events\StockUpdated; 

class PereaksiUsage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static string $view = 'filament.pages.pereaksi-usage';
    protected static ?string $navigationLabel = 'Penggunaan Pereaksi';
    protected static ?string $title = 'Penggunaan Pereaksi';
    protected static ?string $navigationGroup = 'Form';
    public $type = null;
    public $item;
    public $jumlah;
    public $nama;
    public function mount()
    {
        $this->nama = Auth::user()->name;  // Ambil nama pengguna yang sedang login
    }
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('nama')
                ->label('Nama Analis')
                ->disabled()
                ->default($this->nama),
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
            Select::make('jumlah')
                ->label('Jumlah Penggunaan (Gram)')
                ->options([
                    4 => '4',
                    8 => '8',
                ])
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
        
        if ($pereaksi && $pereaksi->Stock >= $data['jumlah']) {
            
            UsageHistory::create([
                'nama_analis' => $this->nama,
                'KODE' => $data['item'],
                'jenis_pereaksi' => $this->type,
                'jumlah_penggunaan' => $data['jumlah'],
            ]);
    
            session()->flash('message', 'Pereaksi berhasil dicatat!');  // Menampilkan pesan sukses
            // Cek apakah stok pereaksi habis
            
        } else {
            session()->flash('error', 'Stock tidak mencukupi!');  // Menampilkan pesan error jika stock tidak cukup
        }
    }

}
