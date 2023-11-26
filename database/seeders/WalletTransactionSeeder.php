<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallet_transactions')->insert([
            'amount' => '500.00',
            'currency_code' => '',
            'ref' => '',
            'reason' => 'Wallet Funding',
            'session_id' => '1LgPu9sg2nAkn9UXktMq9dXnF8LualRc2eyDCvyC',
            'wallet_id' => '2',
            'payment_method_id' => '1',
            'status' => 'Pending',
            'is_credit' => '1',
        ]);
    }
}
