<?php

namespace App\Filament\Resources\InvestmentResource\Pages;

use App\Filament\Resources\InvestmentResource;
use App\Models\Plan;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateInvestment extends CreateRecord
{
    protected static string $resource = InvestmentResource::class;


    protected function beforeCreate(): void
    {
        if (isAdmin()) {
            $wallet = Wallet::where('holder_id', $this->data['user_id'])->where('slug', $this->data['wallet'])->first();

        
            if ($this->data['amount'] > $wallet->balance) {


                Filament::notify('danger', 'Error! Insufficient Balance');

                throw ValidationException::withMessages([]);
            }
        } else {

            $wallet = Wallet::where('holder_id', $this->data['user_id'])->where('slug', $this->data['wallet'])->first();


            if ($this->data['amount'] > $wallet->balance) {

                Filament::notify('danger', 'Error! Insufficient Balance');

                throw ValidationException::withMessages([]);
            }
        }
    }

    protected function afterCreate(): void
    {

        $wallet = User::find($this->record->user_id);

        if ($this->record->wallet == 'usd-wallet') {
            $wallet->getWallet('usd-wallet');
            $wallet->withdraw($this->record->amount);
        } elseif ($this->record->wallet == 'eth-wallet') {

            $ethwallet =  $wallet->getWallet('eth-wallet');
            $ethwallet->withdraw($this->record->amount);
        } elseif ($this->record->wallet == 'trade-wallet') {
            $tradewallet = $wallet->getWallet('trade-wallet');
            $tradewallet->withdraw($this->record->amount);
        }


        $plan = Plan::where('id', $this->record->plan_id)->first();
        $profit = $this->record->amount * $plan->profit_percent / 100;

        $this->record->payout_amount = $profit + $this->record->amount;
        $this->record->save();
        $this->record->start_date = Carbon::now();

        $this->record->save();
        if ($plan->invoice_interval == 'Day') {
            $this->record->payout_date = Carbon::now()->addDays($plan->invoice_period);
            $this->record->save();
        } elseif ($plan->invoice_interval == 'Week') {
            $this->record->payout_date = Carbon::now()->addWeeks($plan->invoice_period);
            $this->record->save();
        } elseif ($plan->invoice_interval == 'Month') {
            $this->record->payout_date = Carbon::now()->addMonths($plan->invoice_period);
            $this->record->save();
        } else {
            $this->record->payout_date = Carbon::now()->addDays($plan->invoice_period);
            $this->record->save();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
