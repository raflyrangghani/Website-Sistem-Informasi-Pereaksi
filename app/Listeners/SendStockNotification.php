<?php

namespace App\Listeners;

use App\Events\StockUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStockNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StockUpdated $event): void
    {
        $pereaksi = $event->pereaksi;

        // Cek status stok
        if ($pereaksi->Stock == 0) {
            $status = 'Out of Stock';
            dd($pereaksi);
        } elseif ($pereaksi->Stock < 50) {
            $status = 'Understock';
        } else {
            return; // Tidak ada notifikasi jika stok normal
        }

        // Ambil email user yang akan diberi notifikasi
        $userEmails = \App\Models\User::pluck('email');

        // Kirim email
        foreach ($userEmails as $email) {
            Mail::to($email)->send(new ReagentStockNotification($pereaksi, $status));
        }
    }
}
