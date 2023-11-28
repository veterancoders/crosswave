<?php

namespace App\Filament\Pages;

use App\Filament\Resources\WalletResource\Widgets\WalletOverview;
use App\Filament\Widgets\DashboardChart;
use App\Filament\Widgets\DashboardChart2;
use App\Filament\Widgets\WalletTransaction as WalletTransactions;
use App\Filament\Widgets\Convert;
use App\Filament\Widgets\Dashboard2Overview;
use App\Filament\Widgets\StatsOverview;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Paymentmethod;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Settings\DepositSettings;
use Bavix\Wallet\Models\Wallet;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard\Step;
use Filament\Pages\Actions\Action;
use Filament\Pages\Dashboard as BasePage;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn as ColumnsTextColumn;
use Filament\TextColumn;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Cookie;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Stephenjude\PaymentGateway\DataObjects\PaymentData;
use Stephenjude\PaymentGateway\PaymentGateway;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class Dashboard extends BasePage implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected function getWidgets(): array
    {
      
        if (isAdmin()) {
            return [
                StatsOverview::class,
                DashboardChart::class,
                DashboardChart2::class,

            ];
        } elseif (isCustomer()) {

            return [
                Dashboard2Overview::class,
                Convert::class,
                WalletTransactions::class,
            ];
        }

        return [];
    }

    protected function getActions(): array
    {
        if (!isAdmin()) {
            $currency_code =  CountryCode();
            $deposit_address =  app(DepositSettings::class);
            return [
                Action::make('Add Money')
                    ->action(function (array $data) {

                        if ($data['payment_method_id'] == '1') {
                            /*    $currency_code =  CountryCode(); */
                            $payId = $data['payment_method_id'];
                            $amount = $data['amount'];
                            $wallet = $data['wallet_type'];
                            $pay_provider = 'paystack';

                            return start_payment($pay_provider, $amount, $wallet, $payId);
                        } elseif ($data['payment_method_id'] == '2') {
                            $payId = $data['payment_method_id'];
                            $amount = $data['amount'];
                            $wallet = $data['wallet_type'];
                            $pay_provider = 'flutterwave';

                            return start_payment($pay_provider, $amount, $wallet, $payId);
                        } elseif ($data['payment_method_id'] == '3') {
                            $payId = $data['payment_method_id'];
                            $amount = $data['amount'];
                            $wallet = $data['wallet_type'];

                            session(['payId' => $payId]);
                            session(['amount' => $amount]);
                            session(['wallet' => $wallet]);
                            return redirect()->route('paypal_chekout');
                        } elseif ($data['payment_method_id'] == '4') {
                            $payId = $data['payment_method_id'];
                            $amount = $data['amount'];
                            $wallet = $data['wallet_type'];
                            $provider = 'klasha';

                            return start_payment($provider, $amount, $wallet, $payId);
                        } elseif ($data['payment_method_id'] == '5') {
                            $payId = $data['payment_method_id'];
                            $amount = $data['amount'];
                            $wallet = $data['wallet_type'];
                            $provider = 'stripe';

                            return start_payment($provider, $amount, $wallet, $payId);
                        } elseif ($data['payment_method_id'] == '6') {
                            $walletype = $data['wallet_type'];

                            $deposit = new Deposit();
                            $deposit->user_id = auth()->id();
                            $deposit->deposit_type = 'Wallet Deposit';
                            $deposit->wallet = $walletype;
                            $deposit->payment_proof = $data['payment_proof'];
                            $deposit->has_payment_proof = '1';
                            $deposit->status = 'Pending';

                            $ref = '';
                            if (Cookie::has('ref_code')) {

                                $deposit->has_cookie = 1;
                                $ref = Cookie::get('ref_code');
                                $deposit->stored_cookie = $ref;
                            };
                            $deposit->amount = $data['amount'];
                            $deposit->save();

                            Filament::notify('success', 'Added Sussessfully. Deposit awaiting Admin approval');
                        } elseif ($data['payment_method_id'] == '7') {

                            $walletype = $data['wallet_type'];

                            $deposit = new Deposit();
                            $deposit->user_id = auth()->id();
                            $deposit->deposit_type = 'Wallet Deposit';
                            $deposit->wallet = $walletype;
                            $deposit->payment_proof = $data['payment_proof'];
                            $deposit->has_payment_proof = '1';
                            $deposit->status = 'Pending';

                            $ref = '';
                            if (Cookie::has('ref_code')) {

                                $deposit->has_cookie = 1;
                                $ref = Cookie::get('ref_code');
                                $deposit->stored_cookie = $ref;
                            };


                            $deposit->amount = $data['amount'];
                            $deposit->save();

                            Filament::notify('success', 'Added Sussessfully. Deposit awaiting Admin approval');
                        } else {



                            $provider = PaymentGateway::make('paystack');

                            $paymentSession = $provider->initializePayment([
                                'currency' => 'NGN', // required
                                'amount' => 100, // required
                                'email' => 'customer@email.com', // required
                                'meta' => ['name' => 'Stephen Jude', 'phone' => '0818148498'],
                                'closure' => function (PaymentData $payment) {
                                    /* 
                     * Payment verification happens immediately after the customer makes payment. 
                     * The payment data gotten from the verification will be injected into this closure.
                     */
                                    logger('payment details', [
                                        'currency' => $payment->currency,
                                        'amount' => $payment->amount,
                                        'status' => $payment->status,
                                        'reference' => $payment->reference,
                                        'provider' => $payment->provider,
                                        'date' => $payment->date,
                                    ]);
                                },
                            ]);

                            $paymentSession->provider;
                            $paymentSession->checkoutUrl;
                            $paymentSession->expires;
                        }
                    })
                    ->steps([
                        Step::make('Payment Method')
                            ->description('Select A payment method')
                            ->schema([
                                Grid::make(2)->schema([
                                    Select::make('wallet_type')->label('Deposit Into')
                                        ->options([
                                            'main-wallet' => 'MAIN Wallet',
                                            'eth-wallet' => 'ETH Wallet',
                                            'trade-wallet' => 'TRADE Wallet',

                                        ])->required()->reactive()->afterStateUpdated(function ($state, callable $set) {

                                            $set('wallet_type_check', $state);

                                            $Walletbalance = Wallet::where('slug', $state)->where('holder_id', auth()->id())->first();

                                            /*       dd($Walletbalance->balance); */
                                            $set('wallet_balance', $Walletbalance->balance);
                                        })->helperText('Select wallet to deposit into'),

                                    TextInput::make('wallet_balance')->label('Balance')->disabled()->prefix($currency_code),
                                ]),

                                Select::make('payment_method_id')->label('Payment Method')
                                    ->options(Paymentmethod::where('is_active', 1)->pluck('name', 'id'))->searchable()->required()->reactive()->afterStateUpdated(function ($state, callable $set) {

                                        $payment = Paymentmethod::find($state);

                                        $set('paymentmethod_check', $payment?->name);
                                    }),
                            ]),
                        Step::make('Amount')
                            ->description('Add amount to deposit.')
                            ->schema([
                                TextInput::make('amount')->label('Amount')->numeric()->required()->integer()->maxLength(11)->minValue(50)->helperText('Minimum deposit is 50USD')->reactive()->afterStateUpdated(function ($state, callable $set) {

                                    $set('amount_check', $state);
                                })->prefix($currency_code),
                            ]),
                        Step::make('Summary')
                            ->description('Review your details')
                            ->schema([

                                TextInput::make('wallet_type_check')->disabled()->label('Wallet Type'),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('paymentmethod_check')->label('Payment Method')->disabled(),
                                        TextInput::make('amount_check')->label('Amount')->disabled()->prefix($currency_code),
                                    ]),
                                ViewField::make('bankdetails')->view('filament.pages.bankdetails')->hidden(fn (Closure $get) => $get('payment_method_id') != '7'),

                                FileUpload::make('payment_proof')->label('Payment Prove')->helperText('A screenshot of the payment made to the bank is required')->required()
                                    ->hidden(fn (Closure $get) => $get('payment_method_id') != '7'),

                                TextInput::make('bitcoin_wallet_address')->label('Bitcoin Address')->default($deposit_address->bitcoin_address)->disabled()->hidden(fn (Closure $get) => $get('payment_method_id') != '6')->suffixAction(CopyAction::make()),
                                FileUpload::make('bitcoin_wallet_barcode')->default($deposit_address->bitcoin_address_barcode)
                                    ->hidden(fn (Closure $get) => $get('payment_method_id') != '6')->disabled()->helperText('Scan the barcode to make payments'),

                                FileUpload::make('payment_proof')->directory('payment-proffs')->label('Payment Prove')->helperText('A screenshot of the payment made to the address is required')->required()
                                    ->hidden(fn (Closure $get) => $get('payment_method_id') != '6'),


                            ])

                    ]),


                Action::make('Withdraw')
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
                                    'main-wallet' => 'MAIN Wallet',
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
        } else {

            return [];
        }
    }
}
