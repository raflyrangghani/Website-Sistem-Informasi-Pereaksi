<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pereaksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pereaksi';
    protected $primaryKey = 'KODE';
    protected $keyType = 'string';

    protected $fillable = [
        'KODE',
        'ITEM',
        'TYPE',
        'Stock',
        'Status'
    ];

    // Explicitly cast KODE to string if needed
    protected $casts = [
        'KODE' => 'string',
        'Stock' => 'integer',
    ];
    public function usageHistories()
    {
        return $this->hasMany(UsageHistory::class, 'KODE', 'KODE');
    }

    public function restockHistories()
    {
        return $this->hasMany(RestockHistory::class, 'KODE', 'KODE');
    }
    public function updateStatus()
    {
        if ($this->Stock === 0) {
            $this->Status = 'Out of Stock';
        } elseif ($this->Stock <= 500) {
            $this->Status = 'Under Stock';
        } else {
            $this->Status = 'In Stock';
        }
        
        return $this->save();
    }
    protected $guarded = [];
}
