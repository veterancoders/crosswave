<?php

namespace App\Filament\Resources\RefferalResource\Pages;

use App\Filament\Resources\RefferalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefferal extends EditRecord
{
    protected static string $resource = RefferalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
