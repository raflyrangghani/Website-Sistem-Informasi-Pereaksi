<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_analis',
        'KODE',
        'jenis_pereaksi',
        'jumlah_penggunaan',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($usageHistory) {
            $pereaksi = Pereaksi::where('KODE', $usageHistory->KODE)->first();
            if ($pereaksi) {
                $pereaksi->Stock -= $usageHistory->jumlah_penggunaan;
                $pereaksi->save(); // Ini akan trigger observer untuk cek status dan kirim notifikasi
            }
        });
    }

    public function pereaksi()
    {
        return $this->belongsTo(Pereaksi::class, 'KODE', 'KODE');
    }
}
