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
    public function pereaksi()
    {
        return $this->belongsTo(Pereaksi::class, 'KODE');
    }
}
