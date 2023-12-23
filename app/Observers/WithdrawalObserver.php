<?php

namespace App\Observers;

use App\Models\Withdrawal;
use Filament\Facades\Filament;

class WithdrawalObserver
{
    /**
     * Handle the Withdrawal "created" event.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return void
     */
    public function created(Withdrawal $withdrawal)
    {
        $user = Filament::auth()->user();

        $usercurrency = $user->currency;

        $withdrawal->currency = $usercurrency;

        $withdrawal->save();
    }

    /**
     * Handle the Withdrawal "updated" event.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return void
     */
    public function updated(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Handle the Withdrawal "deleted" event.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return void
     */
    public function deleted(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Handle the Withdrawal "restored" event.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return void
     */
    public function restored(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Handle the Withdrawal "force deleted" event.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return void
     */
    public function forceDeleted(Withdrawal $withdrawal)
    {
        //
    }
}
