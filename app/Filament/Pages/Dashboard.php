<?php

namespace App\Filament\Pages;

use App\Filament\Resources\WalletResource\Widgets\WalletOverview;
use App\Filament\Widgets\DashboardChart;
use App\Filament\Widgets\DashboardChart2;
use App\Filament\Widgets\DashboardOverview;
use App\Filament\Widgets\LatestDeposits;
use App\Filament\Widgets\LatestInvestments;
use App\Filament\Widgets\StatsOverview;
use Bavix\Wallet\Models\Wallet;
use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BasePage
{
    protected function getWidgets(): array
    {
        
        if (isAdmin()) {
            return [
                StatsOverview::class,
                DashboardChart::class,
                DashboardChart2::class,
                LatestInvestments::class
            ];
        } elseif (isCustomer()) {
    

            $user = Auth::user();
            $userwallet1 = Wallet::where('slug', 'eth-wallet')->where('holder_id', auth()->id())->first();
            if (is_null($userwallet1)) {
    
               $user->createWallet([
                    'name' => 'ETH WALLET',
                    'slug' => 'eth-wallet',
                ]);
            };
            $userwallet2 = Wallet::where('slug', 'trade-wallet')->where('holder_id', auth()->id())->first();
            if (is_null($userwallet2)) {
                
               $user->createWallet([
                    'name' => 'TRADE WALLET',
                    'slug' => 'trade-wallet',
                ]);
            }
    
            return [
                StatsOverview::class,
                LatestInvestments::class
            ];
        }

        return [];
    }
}
