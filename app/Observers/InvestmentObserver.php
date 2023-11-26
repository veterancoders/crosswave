<?php

namespace App\Observers;

use App\Models\Investment;

class InvestmentObserver
{
    /**
     * Handle the Investment "created" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function created(Investment $investment)
    {
        
        $investment->sendNotification('investment_created');
    }

    /**
     * Handle the Investment "updated" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function updated(Investment $investment)
    {
        //
    }

    /**
     * Handle the Investment "deleted" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function deleted(Investment $investment)
    {
        //
    }

    /**
     * Handle the Investment "restored" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function restored(Investment $investment)
    {
        //
    }

    /**
     * Handle the Investment "force deleted" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function forceDeleted(Investment $investment)
    {
        //
    }
}
