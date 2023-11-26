<?php

namespace App\Filament\Resources\PaymentmethodResource\Pages;

use App\Filament\Resources\PaymentmethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentmethod extends EditRecord
{
    protected static string $resource = PaymentmethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
