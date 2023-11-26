<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TransferOverview;
use App\Filament\Widgets\TransferWidget;
use Filament\Pages\Page;

class Transfer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transfer';

    protected static ?string $navigationGroup = 'Transfers';
    protected static function shouldRegisterNavigation(): bool
    {

        return !isAdmin();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransferOverview::class,
        ];
    }

}
