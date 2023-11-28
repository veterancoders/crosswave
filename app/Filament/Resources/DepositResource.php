<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepositResource\Pages;
use App\Filament\Resources\DepositResource\Pages\CreateDeposit;
use App\Filament\Resources\DepositResource\RelationManagers;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Refferal;
use App\Models\User;
use App\Models\Wallet;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?int $navigationSort = 2;


    protected static function getNavigationBadge(): ?string
    {
        if (isAdmin()) {
            return static::getModel()::count();
        } else {
            return static::getModel()::where('user_id', auth()->id())->count();
        }
    }

    public static function form(Form $form): Form
    {
        $currency_code =  CountryCode();
        return $form
            ->schema([
                Card::make()->schema([

                    Grid::make(2)->schema([
                        Select::make('user_id')
                            ->required()->options(User::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('deposit_type')
                            ->options([
                                'Wallet Deposit' => 'Wallet Deposit',

                            ]),
                    ]),

                    Hidden::make('refrence')->default('Null'),
                    Hidden::make('has_payment_proof')->default(0),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('amount')
                            ->required()->integer()->prefix($currency_code),
                        Select::make('wallet')->label('Wallet')
                            ->options([
                                'main-wallet' => 'MAIN Wallet',
                                'eth-wallet' => 'ETH Wallet',
                                'trade-wallet' => 'TRADE Wallet',

                            ])->required()->helperText('Select Wallet to deposit into.')
                    ]),
                    Select::make('status')
                        ->options([
                            'Successful' => 'Successful',
                            'Cancelled' => 'Cancelled',
                            'Pending' => 'Pending',
                        ])->required(),
                    FileUpload::make('payment_proof')->disabled()->enableDownload()->enableOpen()->hiddenOn('create'),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        $currency_code =  CountryCode();

        $currency = Currency::where('code', $currency_code)->first();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('deposit_type'),
                Tables\Columns\TextColumn::make('amount')->prefix($currency->symbol),
                Tables\Columns\TextColumn::make('wallet'),
                BooleanColumn::make('has_payment_proof'),
                Tables\Columns\TextColumn::make('status'),
                BadgeColumn::make('status')->label('Status')->searchable()
                    ->colors([
                        'primary',
                        'success'  => static fn ($state): bool => $state === 'Successful',
                        'danger' => static fn ($state): bool => $state === 'Cancelled',
                        'warning' => static fn ($state): bool => $state === 'Pending',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),

            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Successful' => 'Successful',
                        'Cancelled' => 'Cancelled',
                        'Pending' => 'Pending',

                    ])
            ])

            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    ActionsAction::make('Update Status')
                        ->action(function (Model $record, array $data): void {

                            if ($record->status == 'Successful') {
                                Filament::notify('danger', 'Error! You cannot edit this deposit.');
                            } else {
                                if ($data['getstatus'] == 'Successful') {
                                    $wallet = User::find($record->user_id);

                                    if ($record->has_payment_proof != '1') {
                                        Filament::notify('danger', 'Error! This Deposit cannot be edited');
                                    } else {


                                        if ($record->wallet == 'usd-wallet') {
                                            $wallet->getWallet('usd-wallet');
                                            $wallet->deposit($record->amount);
                                        } elseif ($record->wallet == 'eth-wallet') {

                                            $ethwallet =  $wallet->getWallet('eth-wallet');
                                            $ethwallet->deposit($record->amount);
                                        } elseif ($record->wallet == 'trade-wallet') {
                                            $tradewallet = $wallet->getWallet('trade-wallet');
                                            $tradewallet->deposit($record->amount);
                                        } 

                                       $record->status = 'Successful';
                                        $record->save();

                                        if (!is_null($record->stored_cookie)) {
                                            $ref = $record->stored_cookie;
                                            $referrer = User::where('refferal_code', $ref)->first();//user that reffered id=41
                                        

                                            $rp = 10;
                                            $refferalamount = $record->amount;

                                            $chekuser = User::find($record->user_id);//logged in user id=45
                                           
                                            if (!is_null($chekuser->reffered_code)) {

                                                if (!is_null($referrer)) {

                                                    $checkrefferal = Refferal::where('user_id', $referrer->id)->where('reffered_user_id', $chekuser->id)->first();

                                                    if ($checkrefferal->has_completed_transaction == 0) {

                                                        $profit = $refferalamount * $rp / 100;

                                                        $checkrefferal->has_completed_transaction = 1;
                                                        $checkrefferal->profit = $profit;
                                                        $checkrefferal->save();

                                                        $referrer->getWallet('usd-wallet');
                                                        $referrer->deposit($profit);

                                                        $recipient = $referrer;

                                                        Notification::make()
                                                            ->title('Hurray! A referral has completed a transaction')
                                                            ->sendToDatabase($recipient);

                                                        event(new DatabaseNotificationsSent($recipient));
                                                    }
                                                }
                                            }
                                            Notification::make()
                                                ->title('Your Deposit has been approved')
                                                ->sendToDatabase($wallet);
                                        }
                                    }
                                } else {

                                    $record->status = $data['getstatus'];
                                    $record->save();
                                }
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('getstatus')
                                ->label('Status')
                                ->options([
                                    'Successful' => 'Successful',
                                    'Cancelled' => 'Cancelled',
                                    'Pending' => 'Pending',
                                ])
                                ->required(),
                        ])->icon('heroicon-s-pencil')->visible(isAdmin()),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])/* ->reorderable('id') */;
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
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'create' => Pages\CreateDeposit::route('/create'),

        ];
    }
}
