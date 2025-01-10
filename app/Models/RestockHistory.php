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
    public function pereaksi()
    {
        return $this->belongsTo(Pereaksi::class, 'KODE');
    }
}
