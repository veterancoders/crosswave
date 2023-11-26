<?php

namespace App\Filament\Resources\WithdrawalResource\Pages;

use App\Filament\Resources\WithdrawalResource;
use App\Models\Paymentmethod;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Settings\DepositSettings;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Pages\Actions\Action as ActionsAction;
use Filament\Tables\Actions\Action;
use Stephenjude\PaymentGateway\DataObjects\PaymentData;
use Stephenjude\PaymentGateway\Facades\PaymentGateway;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class ListWithdrawals extends ListRecords
{
    protected static string $resource = WithdrawalResource::class;

    protected function getActions(): array
    {
        $currency_code =  CountryCode();
        $deposit_address =  app(DepositSettings::class);


        if (isAdmin()) {
            return [
                Actions\CreateAction::make(),
            ];
        }else{
            return [

                ActionsAction::make('Withdraw')
                ->action(function (array $data) {


                    $wallet = Wallet::where('holder_id', auth()->id())->where('slug', $data['wallet'])->first();

                    if ($wallet->balance == '0') {
                        Filament::notify('danger', 'Error! Your wallet balance is empty.');
                    } elseif ($data['amount'] > $wallet->balance) {

                        Filament::notify('danger', 'Error! Insufficient Balance');
                    } else {

                        Withdrawal::create(
                            [
                                'user_id' => auth()->id(),
                                'amount' => $data['amount'],
                                'payment_method' => $data['payment_method'],
                                'status' => 'processing',
                                'wallet' => $data['wallet'],
                                'details' => $data['details'],

                            ]
                        );
                        $session = session()->getId();
                        WalletTransaction::create(
                            [
                                'user_id' => auth()->id(),
                                'amount' => $data['amount'],
                                'reason' => 'Withdrawal',
                                'session_id' => $session,
                                'wallet' => $data['wallet'],
                                'status' => 'Successful',
                                'payment_method_id' =>  $data['payment_method'],

                            ]
                        );

                        Filament::notify('success', 'Your Withdrawal request has been submitted and is processing.');
                    }
                })->form([
                    Grid::make(2)->schema([
                        Select::make('wallet')->label('Withdraw From')
                            ->options([
                                'usd-wallet' => 'USD Wallet',
                                'eth-wallet' => 'ETH Wallet',
                                'trade-wallet' => 'TRADE Wallet',

                            ])->required()->reactive()->afterStateUpdated(function ($state, callable $set) {

                                $Walletbalance = Wallet::where('slug', $state)->where('holder_id', auth()->id())->first();

                                $set('wallet_balance', $Walletbalance->balance);
                            })->helperText('Select wallet to withdraw from'),

                        TextInput::make('wallet_balance')->label('Balance')->disabled()->prefix($currency_code),
                    ]),
                    TextInput::make('amount')->required()->numeric()->prefix($currency_code),

                    Select::make('payment_method')->label('Payment Method')
                        ->options(
                            Paymentmethod::pluck('name', 'name')
                        )->required()->reactive()->searchable(),

                    Textarea::make('details')->required()->hidden(fn (Closure $get) => $get('payment_method') == null)
                        ->helperText('Input your withdrawal details. Accurate Information is required according to selected withdrawal method')
                ]),


            ];
        
        }
    }
}
