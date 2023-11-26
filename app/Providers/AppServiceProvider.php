<?php

namespace App\Providers;

use App\Filament\Resources\InvestmentResource;
use App\Filament\Resources\TransferResource;
use App\Filament\Resources\WalletResource;
use App\Settings\FooterSettings;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Filament::serving(function () {

            Filament::registerNavigationGroups([

                NavigationGroup::make()
                    ->label('Transactions')
                    ->icon('heroicon-o-collection'),
                NavigationGroup::make()
                    ->label('Wallets')
                    ->icon('heroicon-s-cog'),
                NavigationGroup::make()
                    ->label('Transfers')
                    ->icon('heroicon-s-cog'),
                NavigationGroup::make()
                    ->label('Accounts')
                    ->icon('heroicon-s-collection'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-s-cog'),
                NavigationGroup::make()
                    ->label('Refferals')
                    ->icon('heroicon-s-collection'),
                NavigationGroup::make()
                    ->label('Filament Shield')
                    ->icon('heroicon-s-cog'),
                NavigationGroup::make()
                    ->label('For Developers')
                    ->icon('heroicon-s-chip'),
                NavigationGroup::make()
                    ->label('Private Pages')
                    ->icon('heroicon-s-chip'),
            ]);

            if (!isAdmin()) {
                Filament::registerNavigationItems([
                    NavigationItem::make('MyWallet')
                        ->url(WalletResource::getUrl('wallet'))
                        ->icon('heroicon-o-document-text')

                ]);
            }
        });

        Filament::pushMeta([
            new HtmlString(' <link rel="icon" type="image/png" sizes="32x32" href="">'),
        ]);

        Config::set('filament.brand', app(GeneralSettings::class)->site_name);
    }
}
