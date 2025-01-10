<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pereaksi extends Model
{
    use HasFactory;
    protected $table = 'pereaksi';
    protected $primaryKey = 'KODE';
    protected $keyType = 'string';

    // Explicitly cast KODE to string if needed
    protected $casts = [
        'KODE' => 'string',
    ];
    protected $guarded = [];
}
