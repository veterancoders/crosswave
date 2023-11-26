<?php

namespace App\Filament\Resources\InvestmentResource\Pages;

use App\Filament\Resources\InvestmentResource;
use App\Models\Plan;
use App\Models\User;
use App\Models\Wallet;
use Filament\Facades\Filament;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditInvestment extends EditRecord
{
    protected static string $resource = InvestmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeValidate(): void
    {
        if ($this->record->status == 'payedout') {
            Filament::notify('danger', 'Sorry, You cannot Edit A payed out Investment');
            throw ValidationException::withMessages([]);
        }
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {




        if (isAdmin()) {
            $wallet = Wallet::where('holder_id', $this->record->user_id)->where('slug', $this->data['wallet'])->first();


            if ($this->data['amount'] > $wallet->balance) {


                Filament::notify('danger', 'Error! Insufficient Balance');

                throw ValidationException::withMessages([]);
            } else {
                $wallet = User::find($this->record->user_id);

                if ($this->data['wallet'] == 'usd-wallet') {

                    if ($this->data['amount'] > $this->record->amount) {
                        $balance = $this->data['amount'] - $this->record->amount;

                        $wallet->getWallet('usd-wallet');
                        $wallet->withdraw($balance);
                    } elseif ($this->data['amount'] < $this->record->amount) {
                        $balance = $this->record->amount - $this->data['amount'];

                        $wallet->getWallet('usd-wallet');
                        $wallet->deposit($balance);
                    }
                } elseif ($this->data['wallet'] == 'eth-wallet') {

                    if ($this->data['amount'] > $this->record->amount) {
                        $balance = $this->data['amount'] - $this->record->amount;

                        $ethwallet =  $wallet->getWallet('eth-wallet');
                        $ethwallet->withdraw($balance);
                    } elseif ($this->data['amount'] < $this->record->amount) {
                        $balance = $this->record->amount - $this->data['amount'];

                        $ethwallet =  $wallet->getWallet('eth-wallet');
                        $ethwallet->deposit($balance);
                    }
                } elseif ($this->data['wallet'] == 'trade-wallet') {
                    if ($this->data['amount'] > $this->record->amount) {
                        $balance = $this->data['amount'] - $this->record->amount;

                        $tradewallet = $wallet->getWallet('trade-wallet');
                        $tradewallet->withdraw($balance);
                    } elseif ($this->data['amount'] < $this->record->amount) {
                        $balance = $this->record->amount - $this->data['amount'];

                        $tradewallet = $wallet->getWallet('trade-wallet');
                        $tradewallet->deposit($balance);
                    }
                }
                $record->update($data);
                $plan = Plan::where('id', $this->data['plan_id'])->first();
                $profit = $this->data['amount'] * $plan->profit_percent / 100;

                $this->record->payout_amount = $profit + $this->data['amount'];
                $this->record->save();

                $record->update($data);

                return $record;
            }
        } else {

            $wallet = Wallet::where('holder_id', $this->record->user_id)->where('slug',  $this->data['wallet'])->first();


            if ($this->data['amount'] > $wallet->balance) {

                Filament::notify('danger', 'Error! Insufficient Balance');

                throw ValidationException::withMessages([]);
            } else {



                $wallet = User::find($this->record->user_id);

                if ($this->data['wallet'] == 'usd-wallet') {

                    if ($this->data['amount'] > $this->record->amount) {
                        $balance = $this->data['amount'] - $this->record->amount;

                        $wallet->getWallet('usd-wallet');
                        $wallet->withdraw($balance);
                    } elseif ($this->data['amount'] < $this->record->amount) {
                        $balance = $this->record->amount - $this->data['amount'];

                        $wallet->getWallet('usd-wallet');
                        $wallet->deposit($balance);
                    }
                } elseif ($this->data['wallet'] == 'eth-wallet') {

                    if ($this->data['amount'] > $this->record->amount) {
                        $balance = $this->data['amount'] - $this->record->amount;

                        $ethwallet =  $wallet->getWallet('eth-wallet');
                        $ethwallet->withdraw($balance);
                    } elseif ($this->data['amount'] < $this->record->amount) {
                        $balance = $this->record->amount - $this->data['amount'];

                        $ethwallet =  $wallet->getWallet('eth-wallet');
                        $ethwallet->deposit($balance);
                    }
                } elseif ($this->data['wallet'] == 'trade-wallet') {
                    if ($this->data['amount'] > $this->record->amount) {
                        $balance = $this->data['amount'] - $this->record->amount;

                        $tradewallet = $wallet->getWallet('trade-wallet');
                        $tradewallet->withdraw($balance);
                    } elseif ($this->data['amount'] < $this->record->amount) {
                        $balance = $this->record->amount - $this->data['amount'];

                        $tradewallet = $wallet->getWallet('trade-wallet');
                        $tradewallet->deposit($balance);
                    }
                }
                $record->update($data);
                $plan = Plan::where('id', $this->data['plan_id'])->first();
                $profit = $this->data['amount'] * $plan->profit_percent / 100;

                $this->record->payout_amount = $profit + $this->data['amount'];
                $this->record->save();


                return $record;
            }
        }
    }
}
