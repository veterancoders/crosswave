<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
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

class Dashboard2Overview extends BaseWidget
{
    protected static ?string $pollingInterval = null;    
    protected function getCards(): array
    {
        $currency_code =  CountryCode();

        $user = Auth::user();
   

        $wallet1 = $user->balance;
        $withdrawals = Withdrawal::where('user_id', auth()->id())->count();
        $deposits = Deposit::where('user_id', auth()->id())->count();
        return [
            Card::make($user->wallet->name, $wallet1  . '' . $currency_code)->extraAttributes([
                'class' => 'cursor-pointer',
            ])->icon('heroicon-s-user'),
  Card::make('Total Deposits', $deposits)->url(DepositResource::getUrl('index')),
            Card::make('Total Withdrawals', $withdrawals)->url(WithdrawalResource::getUrl('index')),  
           
        ];
    }
}
