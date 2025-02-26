<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_reagent',
        'nama_reagent',
        'jenis_reagent',
        'jumlah_restock',
    ];
    
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($restockHistory) {
            $pereaksi = Pereaksi::where('kode_reagent', $restockHistory->kode_reagent)->first();
            if ($pereaksi) {
                $pereaksi->Stock += $restockHistory->jumlah_restock;
                $pereaksi->save(); // Ini akan trigger observer untuk update status
            }
        });
    }

    public function pereaksi()
    {
        return $this->belongsTo(Pereaksi::class, 'kode_reagent', 'kode_reagent');
    }
}
