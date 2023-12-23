<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransferResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TransferOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Transfer internationally to other users', 'USER TRANSFER (International)')->url(TransferResource::getUrl('usertransfer')),
            Card::make('Transfer locally to other users', 'USER TRANSFER (Local)')->url(TransferResource::getUrl('usertransfer')),
            Card::make('Transfer between wallets', 'WALLET TRANSFER')->url(TransferResource::getUrl('walletransfer')),
          
        ];
    }
}
