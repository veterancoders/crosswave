<?php

namespace App\Filament\Resources\TransferResource\Pages;

use App\Filament\Resources\TransferResource;
use App\Models\Transferhistory;
use App\Models\Wallet;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class WalletTransfer extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = TransferResource::class;

    protected static string $view = 'filament.resources.transfer-resource.pages.wallet-transfer';
    public $user_id;
    public $wallet_1;
    public $walletbalance_1;
    public $wallet_2;
    public $amount;

    protected static function shouldRegisterNavigation(): bool
    {

        return isCustomer();
    }

    public function mount(): void
    {
        $this->form->fill();
    }
    protected function getFormSchema(): array
    {
        
        $currency_code =  CountryCode();
        return [
            Section::make('Transfer between wallets')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('wallet_1')->label('Transfer Wallet')
                            ->options([
                                'usd-wallet' => 'USD Wallet',
                                'eth-wallet' => 'ETH Wallet',
                                'trade-wallet' => 'TRADE Wallet',

                            ])->reactive()->afterStateUpdated(function ($state, callable $set) {

                                $walletbalance = Wallet::where('slug', $state)->where('holder_id', auth()->id())->first();

                                $set('walletbalance_1', $walletbalance?->balance);
                            })->required()->helperText('Select Wallet to transfer from'),
                        TextInput::make('walletbalance_1')->label('Balance')->required()->prefix($currency_code)->disabled(),

                    ]),

                    Grid::make(2)->schema([
                        Select::make('wallet_2')->label('Recieving Wallet')
                            ->options([
                                'usd-wallet' => 'USD Wallet',
                                'eth-wallet' => 'ETH Wallet',
                                'trade-wallet' => 'TRADE Wallet',

                            ])->reactive()->afterStateUpdated(function ($state, callable $set) {

                                $walletbalance = Wallet::where('slug', $state)->where('holder_id', auth()->id())->first();

                                $set('walletbalance_2', $walletbalance?->balance);
                            })->required()->helperText('Select Wallet to recieve into'),
                        TextInput::make('walletbalance_2')->label('Balance')->required()->prefix($currency_code)->disabled(),

                    ]),

                    TextInput::make('amount')->gt(0)->label('Transfer Amount')->numeric()->required()->prefix($currency_code),

                ])


        ];
    }

    public function submit()
    {

        $user = Auth::user();

        if ($this->amount == 0) {
            $this->validate();
            $this->addError('walletbalance_1', 'Insufficient Balance!');
            Filament::notify('danger', 'Transfer Unsuccessful!');
        } elseif ($this->amount > $this->walletbalance_1) {
            $this->validate();
            $this->addError('walletbalance_1', 'Insufficient Balance!');
            Filament::notify('danger', 'Transfer Unsuccessful!');
        } elseif ($this->wallet_1 == $this->wallet_2) {
            $this->validate();
            $this->addError('wallet_2', 'You cannot transfer to the same wallet!');
            Filament::notify('danger', 'Transfer Unsuccessful!');
        } else {

            $firstWallet = $user->getWallet($this->wallet_1);
            $lastWallet = $user->getWallet($this->wallet_2);
            $firstWallet->transfer($lastWallet, $this->amount);
            Transferhistory::create([
                'user_id' => auth()->id(),
                'reciepient_id' => auth()->id(),
                'transfer_id' => rand(00000, 99999),
                'transfer_from' => $this->wallet_1,
                'transfer_to' => $this->wallet_2,
                'amount' => $this->amount,
                'status' => 'Successful',
            ]);
            Filament::notify('success', 'Transfer Successful!');

            Notification::make()
                ->title('A transfer has been made to your wallet.')
                ->sendToDatabase($user);

            return redirect()->to('/admin/transfers/walletransfer');
        }
    }
}
