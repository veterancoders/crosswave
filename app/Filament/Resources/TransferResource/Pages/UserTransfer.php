<?php

namespace App\Filament\Resources\TransferResource\Pages;

use App\Filament\Resources\TransferResource;
use App\Models\Transferhistory;
use App\Models\User;
use App\Models\Wallet;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class UserTransfer extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = TransferResource::class;
    protected static function shouldRegisterNavigation(): bool
    {

        return isCustomer();
    }

    protected static string $view = 'filament.resources.transfer-resource.pages.user-transfer';
    public $user_id;
    public $wallet;
    public $user_wallet;
    public $amount;
    public $walletbalance;
    public function mount(): void
    {
        $this->form->fill();
    }
    protected function getFormSchema(): array
    {
        $currency_code =  CountryCode();

        return [
            Section::make('Transfer to other user')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('wallet')->label('Wallet')
                            ->options([
                                'main-wallet' => 'MAIN Wallet',
                                'eth-wallet' => 'ETH Wallet',
                                'trade-wallet' => 'TRADE Wallet',

                            ])->reactive()->afterStateUpdated(function ($state, callable $set) {

                                $walletbalance = Wallet::where('slug', $state)->where('holder_id', auth()->id())->first();

                                $set('walletbalance', $walletbalance?->balance);
                            })->required()->helperText('Select Wallet to transfer from'),
                        TextInput::make('walletbalance')->label('Balance')->required()->prefix($currency_code)->disabled(),

                    ]),

                    Grid::make(2)->schema([

                        Select::make('user_id')->label('User')->options(User::all()->pluck('name', 'id'))->searchable()->required()->helperText('Select User'),
                        Select::make('user_wallet')->label('Wallet')
                            ->options([
                                'main-wallet' => 'MAIN Wallet',
                                'eth-wallet' => 'ETH Wallet',
                                'trade-wallet' => 'TRADE Wallet',

                            ])->required()->helperText('Select Wallet to transfer into'),
                    ]),
                    TextInput::make('amount')->label('Transfer Amount')->gt(0)->numeric()->required()->prefix($currency_code),

                ])


        ];
    }

    public function submit()
    {

        $first = Auth::user();
        $last = User::find($this->user_id);

        if ($this->amount == 0) {
            $this->validate();
            $this->addError('walletbalance', 'Insufficient Balance.');

            Filament::notify('danger', 'Transfer Unsuccessful!');
        } elseif ($this->amount > $this->walletbalance) {

            $this->validate();
            $this->addError('walletbalance', 'Insufficient Balance.');
            Filament::notify('danger', 'Transfer Unsuccessful!');
        } else {

            $firstWallet = $first->getWallet($this->wallet);
            $lastWallet = $last->getWallet($this->user_wallet);
            $firstWallet->transfer($lastWallet, $this->amount);


            Transferhistory::create([
                'user_id' => auth()->id(),
                'reciepient_id' => $last->id,
                'transfer_id' => rand(00000, 99999),
                'transfer_from' => $this->wallet,
                'transfer_to' => $this->user_wallet,
                'amount' => $this->amount,
                'status' => 'Successful',
            ]);
            Filament::notify('success', 'Transfer Successful!');

            Notification::make()
                ->title('A transfer has been made to your wallet.')
                ->sendToDatabase($last);

            return redirect()->to('/admin/transfers/usertransfer');
        }
    }
}
