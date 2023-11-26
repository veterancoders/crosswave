<?php
/*
use App\Filament\Resources\WalletResource;
use App\Models\Deposit;
use App\Models\Refferal;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Omnipay\Omnipay;
use Stephenjude\PaymentGateway\DataObjects\PaymentData;
use Stephenjude\PaymentGateway\PaymentGateway;

 function isCustomer()
{

    if (Filament::auth()->user()->hasRole('filament_user')) {
        return true;
    }
    return false;
}
 function isAdmin()
{

    if (Filament::auth()->user()->hasRole('super_admin')) {
        return true;
    }

    return false;
}


/* function CountryCode()
{

    return app(GeneralSettings::class)->default_currency;
} 
function user_currency()
{

    return Auth::user()->currency;
}



function make_refferal_code()
{

    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $trackingidpre = '';
    for ($i = 0; $i < $length; $i++) {
        $trackingidpre .= $characters[rand(0, $charactersLength - 1)];
    }
    return $trackingidpre;
}


function start_payment($pay_provider, $amount, $wallet, $payId)
{

    $user = Auth::user();
    $provider = PaymentGateway::make($pay_provider);
    if ($pay_provider == 'paystack') {
        $currency = 'NGN';
    } else {
        $currency = 'USD';
    }

    $paymentSession = $provider->initializePayment([

        'currency' => $currency, // required
        'amount' => $amount, // required
        'email' => 'customer@email.com', // required
        'meta' => ['authId' => $user->id, 'name' => $user->name, 'phone' => $user->phone, 'wallet' => $wallet, 'payId' => $payId],
        'closure' => function (PaymentData $payment) {



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
    return redirect()->to($paymentSession->checkoutUrl);
    $paymentSession->expires;
}




function afterpayment($wallet, $amount, $userid, $refrence, $payId)
{

    /*  dd($wallet, $userid, $amount, $filament); 
    $user = User::find($userid);
    $getwallet = $user->getWallet($wallet);
    $getwallet->deposit($amount);

    $deposit = new Deposit();
    $deposit->user_id = $user->id;
    $deposit->deposit_type = 'Wallet Deposit';
    $deposit->refrence = $refrence;
    $deposit->wallet = $wallet;
    $deposit->status = 'Successful';
    $deposit->has_payment_proof = '0';
    $deposit->amount = $amount;
    $deposit->save();

    WalletTransaction::create(
        [
            'user_id' => $user->id,
            'amount' => $amount,
            'reason' => 'Withdrawal',
            'wallet' => $wallet,
            'status' => 'Successful',
            'payment_method_id' =>  $payId,

        ]
    );

    $refferalamount = $amount;
    $ref = '';

    if (Cookie::has('ref_code')) {

        $ref = Cookie::get('ref_code');
        $referrer = User::where('refferal_code', $ref)->first();
        $rp = 10;

        $chekuser = User::find($userid);

        if (!is_null($chekuser->reffered_code)) {

            if (!is_null($referrer)) {


                $checkrefferal = Refferal::where('user_id', $referrer->id)->where('reffered_user_id', $chekuser->id)->first();

                if (is_null($checkrefferal)) {

                    $profit = $refferalamount * $rp / 100;

                    $refferal = new Refferal();
                    $refferal->user_id = $referrer->id;
                    $refferal->reffered_user_id = $user->id;
                    $refferal->confirmed = 1;
                    $refferal->profit = $profit;
                    $refferal->save();

                    $referrer->getWallet('usd-wallet');
                    $referrer->deposit($profit);
                }
            } else {
                return null;
            }
        } else {

            return null;
        }
    }

    $recipient = User::find($userid);

    Notification::make()
        ->title('Wallet Transaction successful! Completed at ' . now()->format('d M Y' . ' ' . 'h:m:s'))
        ->sendToDatabase($recipient);

    event(new DatabaseNotificationsSent($recipient));

    return redirect()->to(WalletResource::getUrl('wallet'));
}


function paypal_checkout($amount, $wallet, $payId)
{


    $userid = auth()->id();
    $refrence = 'XXY56INVEST';
    $gateway = Omnipay::create('PayPal_Rest');

    // Initialise the gateway
    $gateway->initialize(array(
        'clientId' => 'AZYOqdpjZqfr2x4PSjqzTeSVm59z0doVO3lgALecdth14Sd4sUf9GFu8LaWlPZaJQC5FG3z52eU3MW0U',
        'secret'   => 'EK_eaWZucohU_i7q-Sk_K5MyCs8wH7sdoNMqrblHaI03Ke5d5y0ZQjditsofArSjonLgCc0fCI8yL4d-',
        'testMode' => true, // Or false when you are ready for live transactions
    ));

    try {
        $transaction = $gateway->purchase(array(
            'amount'        => $amount . '00',
            'currency'      => 'USD',
            'description'   => 'This is a deposit transaction.',
            'returnUrl' => WalletResource::getUrl('wallet'),
            'cancelUrl' => WalletResource::getUrl('wallet')
        ));
        $response = $transaction->send();
        $data = $response->getData();
        echo "Gateway purchase response data == " . print_r($data, true) . "\n";

        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } elseif ($response->isSuccessful()) {

            return afterpayment($wallet, $amount, $userid, $refrence, $payId);
        } else {
            // payment failed: display message to customer
            return redirect()->to(WalletResource::getUrl('wallet'));
            Filament::notify('danger', 'Unsuccessful');

            $recipient = Auth::user();

            Notification::make()
                ->title('Wallet Transaction Failed')
                ->sendToDatabase($recipient);

            event(new DatabaseNotificationsSent($recipient));
        }
    } catch (\Exception $e) {
        echo "Exception caught while attempting authorize.\n";
        echo "Exception type == " . get_class($e) . "\n";
        echo "Message == " . $e->getMessage() . "\n";
    }
}
*/