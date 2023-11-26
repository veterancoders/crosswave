<?php

namespace App\Filament\Resources\PayoutrequestResource\Pages;

use App\Filament\Resources\PayoutrequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayoutrequests extends ListRecords
{
    protected static string $resource = PayoutrequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
   

}
