<?php

namespace App\Filament\Resources\PaymentmethodResource\Pages;

use App\Filament\Resources\PaymentmethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentmethods extends ListRecords
{
    protected static string $resource = PaymentmethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
