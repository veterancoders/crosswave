<?php

namespace App\Filament\Resources;

use AmrShawky\LaravelCurrency\Facade\Currency as FacadeCurrency;
use App\Filament\Resources\InvestmentResource\Pages;
use App\Filament\Resources\InvestmentResource\RelationManagers;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Investment;
use App\Models\Payoutrequest;
use App\Models\Plan;
use App\Models\User;
use App\Models\Wallet;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Actions\Modal\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action as TablesActionsAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Commands\EllipseCommand;

class InvestmentResource extends Resource
{ 
    protected static ?string $model = Investment::class;
  
    protected static function getNavigationBadge(): ?string
    {
        if (isAdmin()) {
            return static::getModel()::count();
        } else {
            return static::getModel()::where('user_id', auth()->id())->count();
        }
    }


    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Investments';
    protected static ?string $navigationGroup = 'Transactions';

    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        $currency_code =  CountryCode();


        return $form

            ->schema([

                Card::make()
                    ->schema([

                        Hidden::make('user_id')->default(auth()->id())->visible(isCustomer()),

                        Select::make('user_id')->label('User')->options(User::all()->pluck('name', 'id'))->searchable()->required()->helperText('Select User')->visible(isAdmin())->disabledOn('edit'),
                        Grid::make(4)
                            ->schema([
                                Select::make('plan_id')->label('Plan')->options(Plan::all()->pluck('name', 'id'))
                                    ->searchable()->reactive()->afterStateUpdated(function ($state, callable $set) {

                                        $plan = Plan::find($state);

                                        $set('min_amount', $plan?->min);
                                        $set('max_amount', $plan?->max);
                                        $set('plan_features', $plan?->description);
                                        $set('invoice_period', $plan?->invoice_period);
                                        $set('invoice_interval', $plan?->invoice_interval);
                                    })->required()->helperText('Select Investment Plan')->columnSpan(2),

                                TextInput::make('invoice_period')->label('Invoice Period')->disabled()->columnSpan(1),
                                TextInput::make('invoice_interval')->label('Invoice Interval')->disabled()->columnSpan(1)
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('min_amount')->label('Minimum Investment Amount')->prefix($currency_code)->disabled(),
                                TextInput::make('max_amount')->label('Maximum Investment Amount')->prefix($currency_code)->disabled(),

                            ]),
                        Grid::make(4)
                            ->schema([
                                Select::make('wallet')->label('Wallet')
                                    ->options([
                                        'usd-wallet' => 'USD Wallet',
                                        'eth-wallet' => 'ETH Wallet',
                                        'trade-wallet' => 'TRADE Wallet',

                                    ])->reactive()->afterStateUpdated(function ($state, callable $set) {

                                        $walletbalance = Wallet::where('slug', $state)->where('holder_id', auth()->id())->first();

                                        $set('walletbalance', $walletbalance?->balance);
                                    })->required()->helperText('Select Wallet to Invest from')->columnSpan(1),
                                TextInput::make('walletbalance')->disabled()->prefix($currency_code)->hidden(isAdmin())->columnSpan(1),


                                TextInput::make('amount')->label('Investment Amount')->required()->gte('min_amount')->lte('max_amount')->prefix($currency_code)->helperText('Set investment amount.App Default currency is used. Investments are made from wallet.')->columnSpan(2),
                            ]),

                        Hidden::make('currency')->default($currency_code),
                        Hidden::make('status')->default('approved'),

                        Toggle::make('can_reinvest')->inline()->visible(isAdmin()),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('payout_amount')->label('Payout Amount')->disabled()->hiddenOn('create'),
                                DateTimePicker::make('start_date')->label('Date Started')->disabled()->hiddenOn('create'),
                                DateTimePicker::make('payout_date')->label('Payout Date')->disabled()->hiddenOn('create')
                            ]),
                    ])
            ]);
    }



    public static function table(Table $table): Table
    {


        $currency = Currency::where('code', CountryCode())->first();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')->label('user name')->weight('bold')->visible(isAdmin())->formatStateUsing(fn (string $state): string => __(User::find($state)->name)),
                Tables\Columns\TextColumn::make('plan_id')->label('Plan Name')->searchable()->formatStateUsing(fn (string $state): string => __(Plan::find($state)->name)),
                Tables\Columns\TextColumn::make('amount')->weight('bold')->prefix($currency->symbol),
                Tables\Columns\TextColumn::make('payout_amount')->weight('bold')->prefix($currency->symbol),

                BadgeColumn::make('status')->label('Status')->searchable()
                    ->colors([
                        'primary',
                        'success'  => static fn ($state): bool => $state === 'approved',
                        'danger' => static fn ($state): bool => $state === 'cancelled',
                        'warning' => static fn ($state): bool => $state === 'pending',
                    ]),
                ToggleColumn::make('can_reinvest')->visible(isAdmin()),
                Tables\Columns\TextColumn::make('created_at')->label('Date Created')
                    ->date(),
                Tables\Columns\TextColumn::make('payout_date')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'cancelled' => 'Cancelled',
                        'pending' => 'Pending',
                        'payedout' => 'Payed Out'
                    ])
            ])
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    TablesActionsAction::make('Update Status')
                        ->action(function (Model $record, array $data): void {

                            $record->status = $data['getstatus'];
                            $record->save();

                            $record->sendNotification('investment_created');
                        })
                        ->form([
                            Forms\Components\Select::make('getstatus')
                                ->label('Status')
                                ->options([
                                    'approved' => 'Approved',
                                    'cancelled' => 'Cancelled',
                                    'pending' => 'Pending',
                                ])
                                ->required(),
                        ])->icon('heroicon-s-pencil')->visible(isAdmin()),


                    TablesActionsAction::make('ReInvest')
                        ->action(function (Model $record, array $data): void {
                            if ($record->status == 'approved') {

                                Filament::notify('danger', 'Error! Investment Approved. Cannot be edited!');
                            } elseif ($record->status == 'payedout') {
                                Filament::notify('danger', 'Error! Payed out Investments cannot be edited');
                            } else {

                                if ($record->can_reinvest == '0') {
                                    Filament::notify('danger', 'Sorry, You cannot Reinvest this Investment!');
                                } elseif (is_null($record->can_reinvest)) {
                                    Filament::notify('danger', 'Sorry, You cannot Reinvest this Investment!');
                                } elseif ($record->reinvest_limit == '2') {

                                    Filament::notify('danger', 'Sorry, ReInvestment Limit Reached');
                                } else {

                                    $limit = $record->reinvest_limit;
                                    if ($record->status == 'pending') {
                                        Filament::notify('danger', 'Error! Investment Awaiting Approval.');
                                    } else {
                                        $record->status = 'pending';
                                        $record->reinvest_limit = $limit + 1;
                                        $record->save();

                                        Filament::notify('success', 'ReInvestment has been submitted. Awaiting Approval');
                                    }
                                }
                            }
                        })->requiresConfirmation()->icon('heroicon-s-pencil')->hidden(isAdmin(), fn ($record) => $record->status == 'payedout'),



                    TablesActionsAction::make('Payout')
                        ->action(function (Model $record, array $data): void {
                            $wallet = User::find($record->user_id);

                            $recipient = User::where('id', $record->user_id)->first();

                            if ($record->status == 'payedout') {
                                Filament::notify('danger', 'Error! User Investment payout has been processed already.');
                            } elseif ($record->status != 'approved') {
                                Filament::notify('danger', 'Error! Please approve Investment before processing payout.');
                            } else {


                                $wallet->deposit($record->payout_amount);


                                $record->status = 'payedout';
                                $record->save();

                                Notification::make()
                                    ->title('Your Investment has been Payedout')
                                    ->sendToDatabase($recipient);
                            }
                        })->requiresConfirmation()->icon('heroicon-s-pencil')->visible(isAdmin()),

                    TablesActionsAction::make('Request Payout')
                        ->action(function (Model $record, array $data): void {
                            if ($record->status == 'payedout') {
                                Filament::notify('danger', 'Error! Investment payout has been processed already.');
                            } elseif ($record->status == 'Payout requested') {
                                Filament::notify('danger', 'Error! Payout request already sent');
                            } else {
                                Payoutrequest::create([
                                    'investment_id' => $record->id,
                                    'user_id' => auth()->id(),
                                    'status' => 'Payout requested',
                                ]);
                                Filament::notify('success', 'Payout Requested');
                            }
                        })->requiresConfirmation()->icon('heroicon-s-pencil')
                        ->hidden(isAdmin(), fn ($record) => $record->status == 'payedout'),
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
        return InvestmentResource::getUrl('index');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvestments::route('/'),
            'create' => Pages\CreateInvestment::route('/create'),
            'view' => Pages\ViewInvestment::route('/{record}'),
            'edit' => Pages\EditInvestment::route('/{record}/edit'),
        ];
    }
}
