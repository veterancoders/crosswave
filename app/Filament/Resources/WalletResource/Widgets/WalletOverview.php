<?php

namespace App\Filament\Resources\WalletResource\Widgets;

use App\Models\User;
use App\Models\Wallet;
use App\Settings\GeneralSettings;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Filament\Resources\DepositResource;
use App\Filament\Resources\WithdrawalResource;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Bavix\Wallet\Models\Wallet as ModelsWallet;

class WalletOverview extends BaseWidget
{

    protected static ?string $pollingInterval = null;




    protected function getCards(): array
    {
        $currency_code =  CountryCode();

        $user = Auth::user();
        $userwallet1 = ModelsWallet::where('slug', 'eth-wallet')->where('holder_id', auth()->id())->first();
        if (is_null($userwallet1)) {

            $wallet = $user->createWallet([
                'name' => 'ETH WALLET',
                'slug' => 'eth-wallet',
            ]);
        };
        $userwallet2 = ModelsWallet::where('slug', 'trade-wallet')->where('holder_id', auth()->id())->first();
        if (is_null($userwallet2)) {
            
            $wallet = $user->createWallet([
                'name' => 'TRADE WALLET',
                'slug' => 'trade-wallet',
            ]);
        }

        $wallet2 = $user->getWallet('eth-wallet');
        $wallet3 = $user->getWallet('trade-wallet');
           //Wallet 2
       $wallet_2= $wallet2->balance;
       //Wallet 3
      $wallet_3 = $wallet3->balance;

        $wallet1 = $user->balance;
      
        return [
            Card::make($user->wallet->name, $wallet1  . '' . $currency_code)->extraAttributes([
                'class' => 'cursor-pointer',
            ])->icon('heroicon-s-user'),
            Card::make($wallet2->name, $wallet_2  . '' . $currency_code)->extraAttributes([
                'class' => 'cursor-pointer',

            ])->icon('heroicon-s-user'),

            Card::make($wallet3->name, $wallet_3 . '' . $currency_code)->extraAttributes([
                'class' => 'cursor-pointer',

            ])->icon('heroicon-s-user'),

          
        ];
    }
}
