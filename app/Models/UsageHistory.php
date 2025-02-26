<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_analis',
        'kode_reagent',
        'nama_reagent',
        'jenis_reagent',
        'jumlah_penggunaan',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($usageHistory) {
            $pereaksi = Pereaksi::where('kode_reagent', $usageHistory->kode_reagent)->first();
            if ($pereaksi) {
                $pereaksi->Stock -= $usageHistory->jumlah_penggunaan;
                $pereaksi->save(); // Ini akan trigger observer untuk cek status dan kirim notifikasi
            }
        });
    }

    public function pereaksi()
    {
        return $this->belongsTo(Pereaksi::class, 'kode_reagent', 'kode_reagent');
    }
}
