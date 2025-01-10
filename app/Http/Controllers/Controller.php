<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pereaksi;
use App\Events\StockUpdated;

class PereaksiController extends Controller
{
    public function updateStock(Request $request, $id)
    {
        $pereaksi = Pereaksi::findOrFail($id);

        // Update the stock
        $pereaksi->Stock = $request->input('Stock');
        $pereaksi->save();

        // Dispatch the StockUpdated event
        event(new StockUpdated($pereaksi));

        return redirect()->back()->with('message', 'Stock updated and notification sent if necessary.');
    }
}
