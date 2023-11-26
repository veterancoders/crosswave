<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallets')->insert([
            'user_id' => '1',
            'balance' => '500',
            'currency_code' => 'NGN'

        ]);
        DB::table('wallets')->insert([
            'user_id' => '3',
            'balance' => '1000',
            'currency_code' => 'NGN'

        ]);
    }
}
