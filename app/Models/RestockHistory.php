<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'KODE',
        'nama_pereaksi',
        'jumlah_restock',
    ];
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($restockHistory) {
            $pereaksi = Pereaksi::where('KODE', $restockHistory->KODE)->first();
            if ($pereaksi) {
                $pereaksi->Stock += $restockHistory->jumlah_restock;
                $pereaksi->save(); // Ini akan trigger observer untuk update status
            }
        });
    }

    public function pereaksi()
    {
        return $this->belongsTo(Pereaksi::class, 'KODE', 'KODE');
    }
}
