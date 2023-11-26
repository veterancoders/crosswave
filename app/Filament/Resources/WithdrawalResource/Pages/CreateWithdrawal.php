<?php

namespace App\Filament\Resources\WithdrawalResource\Pages;

use App\Filament\Resources\WithdrawalResource;
use App\Models\User;
use App\Models\Wallet;
use Filament\Facades\Filament;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateWithdrawal extends CreateRecord
{
    protected static string $resource = WithdrawalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function beforeCreate(): void
    {

        $wallet = Wallet::where('holder_id', $this->data['user_id'])->where('slug', $this->data['wallet'])->first();

      
        if ($this->data['amount'] > $wallet->balance) {


            Filament::notify('danger', 'Error! Insufficient Balance');

            throw ValidationException::withMessages([]);
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
    }
}
