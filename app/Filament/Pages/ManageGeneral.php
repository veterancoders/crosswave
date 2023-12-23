<?php

namespace App\Filament\Pages;

use App\Models\Currency;
use App\Settings\GeneralSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static function shouldRegisterNavigation(): bool
    {
        return isAdmin();
    }
    protected function getFormSchema(): array
    {
        return [
            Section::make('Settings')->schema([
                Toggle::make('site_active')->label('Site Active'),
                TextInput::make('site_name')
                    ->label('Site Name')
                    ->required(),
                Select::make('default_currency')->label('Default Currency')->options(Currency::all()->pluck('name', 'code'))->default('USD'),
                TextInput::make('transfer_rate')
                ->label('Transfer Rate')->suffix('%')->helperText('Set the transfer rate in percentage')->numeric(),
            ])

        ];
    }
}
