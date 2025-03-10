<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $fillable = ['nama_reagent', 'total_penggunaan', 'satuan'];

    public static function updateSummary()
    {
        self::updateSummaryWithFilters(null, null); // Tanpa filter
    }

    public static function updateSummaryWithFilters(?string $startDate = null, ?string $endDate = null)
    {
        $query = UsageHistory::groupBy('nama_reagent', 'satuan')
            ->selectRaw('nama_reagent, SUM(jumlah_penggunaan) as total_penggunaan', 'satuan');

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $summaries = $query->get();

        static::truncate();

        foreach ($summaries as $summary) {
            static::create([
                'nama_reagent' => $summary->nama_reagent,
                'total_penggunaan' => $summary->total_penggunaan,
                'satuan' => $summary->satuan,
            ]);
        }
    }
}
