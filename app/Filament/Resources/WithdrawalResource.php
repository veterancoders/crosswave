<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Filament\Resources\WithdrawalResource\Pages\BankWithdrawal;
use App\Filament\Resources\WithdrawalResource\Pages\EthTransferWithdrawal;
use App\Filament\Resources\WithdrawalResource\Pages\EthWithdrawal;
use App\Filament\Resources\WithdrawalResource\Pages\PaypalTransferWithdrawal;
use App\Filament\Resources\WithdrawalResource\Pages\PayPalWithdrawal;
use App\Filament\Resources\WithdrawalResource\RelationManagers;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Paymentmethod;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Settings\GeneralSettings;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;
    protected static function getNavigationBadge(): ?string
    {
        if (isAdmin()) {
            return static::getModel()::count();
        } else {
            return static::getModel()::where('user_id', auth()->id())->count();
        }
    }

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        $currency_code =  CountryCode();
        return $form
            ->schema([
                Card::make()
                    ->schema([

                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->label('User')
                                ->options(User::all()->pluck('name', 'id'))->searchable()->required()->visible(isAdmin()),

                            Select::make('wallet')->label('Wallet')
                                ->options([
                                    'main-wallet' => 'MAIN Wallet',
                                    'eth-wallet' => 'ETH Wallet',
                                    'trade-wallet' => 'TRADE Wallet',

                                ])->required()->disabledOn('edit')
                        ]),
                        Hidden::make('status')->default('approved'),
                        Grid::make(2)->schema([
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(Paymentmethod::pluck('name', 'name'))
                                ->required()->reactive()->searchable(),

                            Forms\Components\TextInput::make('amount')
                                ->required()->prefix($currency_code),
                        ]),
                        Textarea::make('details')->required()->hidden(fn (Closure $get) => $get('payment_method') == null)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $currency_code =  CountryCode();

        $currency = Currency::where('code', $currency_code)->first();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')->weight('bold')->label('User')->visible(isAdmin())->formatStateUsing(fn (string $state): string => __(User::find($state)->email)),
                Tables\Columns\TextColumn::make('payment_method')->searchable(),
                Tables\Columns\TextColumn::make('wallet'),
                Tables\Columns\TextColumn::make('amount')->weight('bold')->prefix($currency->symbol),

                BadgeColumn::make('status')->label('Status')->searchable()
                    ->colors([
                        'primary',
                        'success'  => static fn ($state): bool => $state === 'approved',
                        'danger' => static fn ($state): bool => $state === 'cancelled',
                        'warning' => static fn ($state): bool => $state === 'Processing',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),

            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'cancelled' => 'Cancelled',
                        'processing' => 'Processing',

                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('Update Status')
                        ->action(function (Model $record, array $data): void {

                            if ($record->status == 'approved') {
                                Filament::notify('danger', 'Error! Approved Withdrawal cannot be edited');
                            } else {
                                $wallet = User::find($record->user_id);
                                if ($data['getstatus'] == 'approved') {

                                    if ($record->amount > $wallet->balance) {
                                        Filament::notify('danger', 'Error! User Balance is Insufficient.');
                                    } else {
                                      
                                        if ($record->wallet == 'usd-wallet') {
                                            $wallet->getWallet('usd-wallet');
                                            $wallet->withdraw($record->amount);
                                        } elseif ($record->wallet == 'eth-wallet') {
                            
                                            $ethwallet =  $wallet->getWallet('eth-wallet');
                                            $ethwallet->withdraw($record->amount);
                                        } elseif ($record->wallet == 'trade-wallet') {
                                            $tradewallet = $wallet->getWallet('trade-wallet');
                                            $tradewallet->withdraw($record->amount);
                                        }

                                        $record->status = 'approved';
                                        $record->save();

                                        Notification::make()
                                            ->title('Your Withdrawal has been processed')
                                            ->sendToDatabase($wallet);
                                    }
                                } else {
                                    $record->status = $data['getstatus'];
                                    $record->save();
                                    if ($data['getstatus'] == 'cancelled') {

                                        Notification::make()
                                            ->title('Your Withdrawal request has been cancelled')
                                            ->sendToDatabase($wallet);
                                    }
                                }
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('getstatus')
                                ->label('Status')
                                ->options([
                                    'approved' => 'Approved',
                                    'cancelled' => 'Cancelled',
                                    'processing' => 'Processing',
                                ])
                                ->required(),
                        ])->icon('heroicon-s-pencil')->visible(isAdmin()),
                    Tables\Actions\DeleteAction::make(),
                ]),


            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        if (isAdmin()) {
            return parent::getEloquentQuery()->latest();
        } elseif (isCustomer()) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->id())->latest();
        }
    }
    protected function getRedirectUrl(): string
    {
        return WithdrawalResource::getUrl('index');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),

            'create' => Pages\CreateWithdrawal::route('/create'),
        ];
    }
}
