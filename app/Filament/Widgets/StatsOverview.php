<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InvestmentResource;
use App\Filament\Resources\PlanResource;
use App\Filament\Resources\WithdrawalResource;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        if(isAdmin()){
            $users = User::all()->count();
        
      
            $withdrawals = Withdrawal::all()->count();
            return [
                Card::make('Total Users', $users),
          
                Card::make('Total Withdrawals', $withdrawals)->url(WithdrawalResource::getUrl('index')),
    
            ];
        }
  
    }
}
