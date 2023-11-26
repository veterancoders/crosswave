<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletResource\Pages;
use App\Filament\Resources\WalletResource\Pages\BankTransferWalletWithdrawal;
use App\Filament\Resources\WalletResource\Pages\EthWalletWithdrawal;
use App\Filament\Resources\WalletResource\Pages\PaypalWalletWithdrawal;
use App\Filament\Resources\WalletResource\Pages\PersonalWallet;
use App\Filament\Resources\WalletResource\Pages\SortWallet;
use App\Filament\Resources\WalletResource\RelationManagers;
use App\Filament\Resources\WalletResource\Widgets\WalletOverview;
use App\Models\User;
use App\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Widgets\StatsOverviewWidget\Card as StatsOverviewWidgetCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;
    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Wallets';
    protected static ?string $navigationGroup = 'Wallets';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Card::make()->schema([
                    Select::make('user_id')->label('User')->options(User::all()->pluck('name', 'id'))->disabled(),

                    Forms\Components\TextInput::make('balance')
                        ->required()->numeric(),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('currency_code')->label('Currency'),
                Tables\Columns\TextColumn::make('balance')->weight('bold'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getWidgets(): array
    {
        return [
            WalletOverview::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
            'wallet' => PersonalWallet::route('/personalwallet'),

        ];
    }
}
