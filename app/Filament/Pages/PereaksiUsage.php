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
            
            // Mengurangi stock dari tabel Pereaksi
            $pereaksi->Stock -= $data['jumlah'];
            $pereaksi->save();

            // StockUpdated::dispatch($pereaksi);
            
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
        if ($pereaksi->Stock === 0) {
            dd($pereaksi);
            \Mail::to(Auth::user()->email)->send(new OutOfStockNotification($pereaksi));
        }
    }

    // protected function saveRecord(array $data): void
    // {
    //     $pereaksi = Pereaksi::where('ITEM', $data['item'])->first();
    //     $pereaksi->Stock -= $data['jumlah'];
    //     $pereaksi->save();
    // }

    // public $nama, $pereaksi_id, $jenis_pereaksi, $jumlah;

    // protected static string $view = 'filament.pages.pereaksi-usage';
    // public function form(Forms\Form $form): Forms\Form
    // {
    //     return $form
    //         ->schema([
    //             TextInput::make('name')
    //                 ->label('Nama')
    //                 ->default(Auth::user()->name)  // Set the current logged-in user name
    //                 ->disabled(),  // Disable because it’s auto-filled

    //             Select::make('pereaksi_id')
    //                 ->label('Nama Pereaksi')
    //                 ->options(Pereaksi::all()->pluck('ITEM', 'id'))  // Fetching names from Pereaksi table
    //                 ->reactive()  // Allows dynamic change of the next field
    //                 ->afterStateUpdated(fn($state) => $this->setJenisPereaksi($state)),

    //             TextInput::make('jenis_pereaksi')
    //                 ->label('Jenis Pereaksi')
    //                 ->disabled()
    //                 ->default($this->jenis_pereaksi),

    //             TextInput::make('jumlah')
    //                 ->label('Jumlah (Gram)')
    //                 ->numeric()
    //                 ->required(),
    //         ]);
    // }

    // // Dynamically get jenis_pereaksi based on selected pereaksi_id
    // public function setJenisPereaksi($state)
    // {
    //     $pereaksi = Pereaksi::find($state);
    //     if ($pereaksi) {
    //         $this->jenis_pereaksi = $pereaksi->TYPE;  // Assuming 'TYPE' is the column for jenis pereaksi
    //     }
    // }

    // // Handle form submission
    // public function submit()
    // {
    //     $pereaksi = Pereaksi::find($this->pereaksi_id);
    //     if ($pereaksi && $pereaksi->Stock >= $this->jumlah) {
    //         // Decrease stock from the Pereaksi table
    //         $pereaksi->Stock -= $this->jumlah;
    //         $pereaksi->save();

    //         // You can add additional logic for recording the usage, etc.
    //         session()->flash('message', 'Pereaksi berhasil dicatat!');
    //     } else {
    //         session()->flash('error', 'Stock tidak mencukupi!');
    //     }
    // }

}
