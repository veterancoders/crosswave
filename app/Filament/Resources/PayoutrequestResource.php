<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayoutrequestResource\Pages;
use App\Filament\Resources\PayoutrequestResource\RelationManagers;
use App\Models\Currency;
use App\Models\Investment;
use App\Models\Payoutrequest;
use App\Models\User;
use App\Models\Wallet;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Forms;
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

class PayoutrequestResource extends Resource
{
    protected static ?string $model = Payoutrequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Payout Requests';
    protected static ?string $navigationGroup = 'Wallets';

    protected static function getNavigationBadge(): ?string
    {
        if (isAdmin()) {
            return static::getModel()::count();
        } else {
            return null;
        }
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->maxLength(250),
                Forms\Components\TextInput::make('plan_id')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->maxLength(250),
            ]);
    }

    public static function table(Table $table): Table
    {
        $currency_code =  CountryCode();

        $currency = Currency::where('code', $currency_code)->first();
        return $table
            ->columns([
               
                Tables\Columns\TextColumn::make('user.email')->label('User'),

                Tables\Columns\TextColumn::make('investment.amount')->label('Investment Amount')->prefix($currency->symbol),

                Tables\Columns\TextColumn::make('investment.payout_amount')->label('Payout Amount')->prefix($currency->symbol),
                BadgeColumn::make('investment.status')->label('Investment Status')->searchable()
                    ->colors([
                        'primary',
                        'success'  => static fn ($state): bool => $state === 'approved',
                        'danger' => static fn ($state): bool => $state === 'cancelled',
                        'warning' => static fn ($state): bool => $state === 'pending',
                    ]),

                BadgeColumn::make('status')->label('Status')->searchable()
                    ->colors([
                        'primary',
                        'success'  => static fn ($state): bool => $state === 'Payeout Approved',
                        'danger' => static fn ($state): bool => $state === 'cancelled',
                        'warning' => static fn ($state): bool => $state === 'Payout requested',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
                Tables\Columns\TextColumn::make('investment.payout_date')
                    ->date()->label('Payout date'),
            ])

            ->actions([

                Tables\Actions\ActionGroup::make([


                    Action::make('Update Status')
                        ->action(function (Model $record, array $data): void {


                            $user = User::find($record->investment->user_id);

                            if ($record->status == 'Payeout Approved') {

                                Filament::notify('danger', 'Error! Payout Approved ');
                            } elseif($data['getstatus'] == 'cancelled'){

                                $record->status = $data['getstatus'];
                                $record->save();
                                Notification::make()
                                ->title('Your Payout request has been cancelled.')
                                ->sendToDatabase($user);
                            }else {

                                $investment =  Investment::find($record->investment_id);

                                $user->deposit($record->investment->payout_amount);


                                $investment->status = 'payedout';
                                $investment->save();

                                $record->status = $data['getstatus'];
                                $record->save();

                                Notification::make()
                                    ->title('Your Investment has been Payedout')
                                    ->sendToDatabase($user);
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('getstatus')
                                ->label('Status')
                                ->options([
                                    'Payeout Approved' => 'Payeout Approved',
                                    'cancelled' => 'cancelled',
                                ])
                                ->required(),
                        ])->icon('heroicon-s-pencil')->visible(isAdmin()),
                    Tables\Actions\DeleteAction::make(),
                ]),

            ])
            ->filters([
                SelectFilter::make('investment.status')
                    ->options([
                        'approved' => 'Approved',
                        'cancelled' => 'Cancelled',
                        'pending' => 'Pending',
                        'payedout' => 'Payed Out'
                    ])->label('Investment status'),
                SelectFilter::make('status')
                    ->options([
                        'Payeout Approved' => 'Payeout Approved',
                        'cancelled' => 'Cancelled',
                        'Payout requested' => 'Payout requested',

                    ])
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
        } else {

            return parent::getEloquentQuery()->latest()->where('user_id', auth()->id());
        }
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayoutrequests::route('/'),
        ];
    }
}
