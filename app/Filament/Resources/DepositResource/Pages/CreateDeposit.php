<?php

namespace App\Filament\Resources\DepositResource\Pages;

use App\Filament\Resources\DepositResource;
use App\Models\User;
use App\Models\Wallet;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDeposit extends CreateRecord
{
    protected static string $resource = DepositResource::class;

    protected function afterCreate(): void
    {
        $wallet = User::find($this->record->user_id);

        $recipient = User::where('id', $this->record->user_id)->first();


        if ($this->record->status == 'Successful') {


            if ($this->record->wallet == 'usd-wallet') {
                $wallet->getWallet('usd-wallet');
                $wallet->deposit($this->record->amount);
            } elseif ($this->record->wallet == 'eth-wallet') {

                $ethwallet =  $wallet->getWallet('eth-wallet');
                $ethwallet->deposit($this->record->amount);
            } elseif ($this->record->wallet == 'trade-wallet') {
                $tradewallet = $wallet->getWallet('trade-wallet');
                $tradewallet->deposit($this->record->amount);
            }



            Notification::make()
                ->title('Your Deposit has been approved')
                ->sendToDatabase($recipient);
        }


    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
