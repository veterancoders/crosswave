<?php

namespace App\Filament\Pages;

use App\Settings\DepositSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageDeposit extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = DepositSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static function shouldRegisterNavigation(): bool
    {
        return isAdmin();
    }
    protected function getFormSchema(): array
    {
        return [

            Section::make('Bitcoin Deposit Settings')->description('set address and barcode details')->schema([

                TextInput::make('bitcoin_address'),
                FileUpload::make('bitcoin_address_barcode')->required(),
            ]),
        ];
    }
}
