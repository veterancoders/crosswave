<?php

namespace App\Observers;

use App\Models\WalletTransaction;
use Filament\Facades\Filament;

class WalletTransactionObserver
{
    /**
     * Handle the WalletTransaction "created" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function created(WalletTransaction $walletTransaction)
    {
        $user = Filament::auth()->user();

        $usercurrency = $user->currency;

        $walletTransaction->currency_code = $usercurrency;

        $walletTransaction->save();
    }

    /**
     * Handle the WalletTransaction "updated" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function updated(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the WalletTransaction "deleted" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function deleted(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the WalletTransaction "restored" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function restored(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the WalletTransaction "force deleted" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function forceDeleted(WalletTransaction $walletTransaction)
    {
        //
    }
}
