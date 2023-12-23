<?php

use App\Filament\Resources\WalletResource;
use App\Filament\Resources\WalletResource\Pages\PersonalWallet;
use App\Http\Controllers\InstallerController;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Refferal;
use App\Models\Session;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Filament\Facades\Filament;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;
use Stephenjude\PaymentGateway\DataObjects\PaymentData;
use Stephenjude\PaymentGateway\PaymentGateway;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {

    if (Cookie::has('ref_code')) {

        return null;
    } else {
        setcookie('ref_code', $request->ref);
    }
});

Route::get('payment/verify', function () {

    if (request()->has('reference')) {

        $reference = request()->get('reference');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_7fa5e9c593c1513af2fb37f4b031df1b1d5aaa2c",
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data =  json_decode($response, true);



            if ($data['status'] == true) {
                $amount = $data['data']['amount'];
                $length = strlen($amount);
                $amount_whole = substr($amount, 0, $length - 2);
                $amount_decimal = substr($amount, -2);
                $walletAmount = $amount_whole . '.' . $amount_decimal;

                $user = Auth::user();


                $session = Session::where('user_id', auth()->id())->first();


                if ($session->content == 'usd-wallet') {

                    $user->getWallet('usd-wallet');
                    $user->deposit($walletAmount);
                } elseif ($session->content == 'eth-wallet') {

                    $ethwallet =  $user->getWallet('eth-wallet');
                    $ethwallet->deposit($walletAmount);
                } elseif ($session->content == 'trade-wallet') {
                    $tradewallet = $user->getWallet('trade-wallet');
                    $tradewallet->deposit($walletAmount);
                }

                /*  $wallet = Auth::user(); */

                // $wallet = Wallet::where('user_id', auth()->id())->first();

                /* 
                $walletTransaction = WalletTransaction::where('wallet_id', $wallet->id)->latest('created_at')->first();
                $walletTransaction->status = 'Transaction Success';
                $walletTransaction->save(); */

                $deposit = new Deposit();
                $deposit->user_id = auth()->id();
                $deposit->deposit_type = 'Wallet Deposit';
                $deposit->refrence = $reference;
                $deposit->wallet = $session->content;
                $deposit->status = 'Successful';
                $deposit->has_payment_proof = '0';
                $deposit->amount = $walletAmount;
                $deposit->save();


                $refferalamount = $walletAmount;
                $ref = '';

                if (Cookie::has('ref_code')) {

                    $ref = Cookie::get('ref_code');
                    $referrer = User::where('refferal_code', $ref)->first();
                    $rp = 10;

                    $chekuser = Auth::user();

                    if (!is_null($chekuser->reffered_code)) {

                        if (!is_null($referrer)) {


                            $checkrefferal = Refferal::where('user_id', $referrer->id)->where('reffered_user_id', auth()->id())->first();

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
                            } else {

                                return null;
                            }
                        } else {
                            return null;
                        }
                    } else {

                        return null;
                    }
                }

                $recipient = auth()->user();

                Notification::make()
                    ->title('Wallet Transaction successful! Completed at ' . now()->format('d M Y' . ' ' . 'h:m:s'))
                    ->sendToDatabase($recipient);

                event(new DatabaseNotificationsSent($recipient));
            } else {
                $session = Session::where('user_id', auth()->id())->first();
                $amount = $data['data']['amount'];
                $length = strlen($amount);
                $amount_whole = substr($amount, 0, $length - 2);
                $amount_decimal = substr($amount, -2);
                $walletAmount = $amount_whole . '.' . $amount_decimal;

                $deposit = new Deposit();
                $deposit->user_id = auth()->id();
                $deposit->deposit_type = 'Wallet Deposit';
                $deposit->refrence = $reference;
                $deposit->wallet = $session->content;
                $deposit->status = 'Cancelled';
                $deposit->amount = $walletAmount;
                $deposit->save();

                $recipient = auth()->user();

                Notification::make()
                    ->title('Wallet Transaction Unsuccessful! Cancelled at ' . now()->format('d M Y' . ' ' . 'h:m:s'))
                    ->sendToDatabase($recipient);

                event(new DatabaseNotificationsSent($recipient));
            }



            return redirect()->to(WalletResource::getUrl('wallet'));
        }

        //return redirect('');

    }
})->name('payment.verify');

Route::get('/test', function () {

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
            'amount'        => '100.00',
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


    //Stripe========================================================
    //=============================================================

    /*  $gateway = Omnipay::create('Stripe');

    // Initialise the gateway
    $gateway->initialize(array(
        'apiKey' => 'sk_test_51MLwBfEDObzsIEo0dYBHFRhPMkrc9ddSaPvQt19UyvBGu2L6QVMfjXpg9wzNCUSn3gKLG9NQBmW1DYpRNtupjVbm009xLWNleC',
    ));

    // Create a credit card object
    // This card can be used for testing.
    $card = new CreditCard(array(
        'firstName'    => 'Example',
        'lastName'     => 'Customer',
        'number'       => '5555555555554444',
        'expiryMonth'  => '01',
        'expiryYear'   => '2026',
        'cvv'          => '123',
     
    ));
    try {
        // Do a purchase transaction on the gateway
        $transaction = $gateway->purchase(array(
            'amount'                   => '10.00',
            'currency'                 => 'USD',
            'card'                     => $card,
            'returnUrl' => WalletResource::getUrl('wallet'),
            'cancelUrl' => WalletResource::getUrl('wallet')
        ));
        $response = $transaction->send();
        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } elseif ($response->isSuccessful()) {
            echo "Purchase transaction was successful!\n";
            $sale_id = $response->getTransactionReference();
            echo "Transaction reference = " . $sale_id . "\n";

            $balance_transaction_id = $response->getBalanceTransactionReference();
            echo "Balance Transaction reference = " . $balance_transaction_id . "\n";
        }
    } catch (\Exception $e) {
        echo "Exception caught while attempting authorize.\n";
        echo "Exception type == " . get_class($e) . "\n";
        echo "Message == " . $e->getMessage() . "\n";
    } */


    //PAYPAL======================================================================
    //=============================================================

    /*    $gateway = Omnipay::create('PayPal_Rest');

    // Initialise the gateway
    $gateway->initialize(array(
        'clientId' => 'AZYOqdpjZqfr2x4PSjqzTeSVm59z0doVO3lgALecdth14Sd4sUf9GFu8LaWlPZaJQC5FG3z52eU3MW0U',
        'secret'   => 'EK_eaWZucohU_i7q-Sk_K5MyCs8wH7sdoNMqrblHaI03Ke5d5y0ZQjditsofArSjonLgCc0fCI8yL4d-',
        'testMode' => true, // Or false when you are ready for live transactions
    ));

    try {
        $transaction = $gateway->purchase(array(
            'amount'        => '10.00',
            'currency'      => 'USD',
            'description'   => 'This is a test purchase transaction.',
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
            // payment was successful: update database
            print_r($response);
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

 */

    //PAYU========================================================
    //=============================================================

    /* 
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// default is official sandbox
$posId = isset($_ENV['POS_ID']) ? $_ENV['POS_ID'] : '300046';
$secondKey = isset($_ENV['SECOND_KEY']) ? $_ENV['SECOND_KEY'] : '0c017495773278c50c7b35434017b2ca';
$oAuthClientSecret = isset($_ENV['OAUTH_CLIENT_SECRET']) ? $_ENV['OAUTH_CLIENT_SECRET'] : 'c8d4b7ac61758704f38ed5564d8c0ae0';

$gateway = GatewayFactory::createInstance($posId, $secondKey, $oAuthClientSecret, true);

try {
    $orderNo = '12345677';
    $returnUrl = 'http://localhost:8000/gateway-return.php';
    $description = 'Shopping at myStore.com';

    $purchaseRequest = [
        'customerIp'    => '127.0.0.1',
        'continueUrl'   => $returnUrl,
        'merchantPosId' => $posId,
        'description'   => $description,
        'currencyCode'  => 'PLN',
        'totalAmount'   => 15000,
        'exOrderId'     => $orderNo,
        'buyer'         => (object)[
            'email'     => 'test@example.com',
            'firstName' => 'Peter',
            'lastName'  => 'Morek',
            'language'  => 'pl'
        ],
        'products'      => [
            (object)[
                'name'      => 'Lenovo ThinkPad Edge E540',
                'unitPrice' => 15000,
                'quantity'  => 1
            ]
        ],
        'payMethods'    => (object) [
            'payMethod' => (object) [
                'type'  => 'PBL', // this is for card-only forms (no bank transfers available)
                'value' => 'c'
            ]
        ]
    ];

    $response = $gateway->purchase($purchaseRequest);

    echo "TransactionId: " . $response->getTransactionId() . PHP_EOL;
    echo 'Is Successful: ' . (bool) $response->isSuccessful() . PHP_EOL;
    echo 'Is redirect: ' . (bool) $response->isRedirect() . PHP_EOL;

    // Payment init OK, redirect to the payment gateway
    echo $response->getRedirectUrl() . PHP_EOL;
} catch (\Exception $e) {
    dump((string)$e);
} */
    //test cards for payu
    /* VISA	4010968243274
VISA	4006566732412511
MAESTRO	5000579348745235
MAESTRO	6999631853158960001
MASTER CARD	5100052384536818 */
})->name('test4');


Route::get('/paypal_chekout', function () {
    $payId = session('payId');
    $amount = session('amount');
    $wallet = session('wallet');

    return paypal_checkout($amount, $wallet, $payId);
})->name('paypal_chekout');


//Checker

Route::get('/check', function () {

    $user = Filament::auth()->user();

    $usercurrency = $user->currency;

    dd($usercurrency);
});

//INSTALLER
Route::get('/install', [InstallerController::class, 'installer'])->name('installer');
Route::get('/install-Checkrequirement', [InstallerController::class, 'Checkrequirement'])->name('Checkrequirement');
Route::get('/install-license', [InstallerController::class, 'license'])->name('license');
Route::post('verification', [InstallerController::class, 'verification'])->name('verification');
Route::get('/install-database', [InstallerController::class, 'Databasesetup'])->name('Databasesetup');
Route::post('process_database', [InstallerController::class, 'process_database'])->name('process_database');
Route::get('/install-install', [InstallerController::class, 'install'])->name('install');
Route::get('/install-adminsetup', [InstallerController::class, 'adminsetup'])->name('adminsetup');
Route::post('admin_setup', [InstallerController::class, 'admin_setup'])->name('admin_setup');
Route::post('process_db', [InstallerController::class, 'process_db'])->name('process_db');
Route::get('/install-done', [InstallerController::class, 'done'])->name('done');
