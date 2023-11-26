<?php

namespace App\Filament\Resources\RefferalResource\Pages;

use App\Filament\Resources\RefferalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefferals extends ListRecords
{
    protected static string $resource = RefferalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
