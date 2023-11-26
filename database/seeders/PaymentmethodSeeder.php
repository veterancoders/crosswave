<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentmethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            'name' => 'Paystack',
            'is_active' => '1',
        ]);
        DB::table('payment_methods')->insert([
            'name' => 'Flutterwave',
            'is_active' => '1',
        ]);
        DB::table('payment_methods')->insert([
            'name' => 'Bank Transfer',
            'is_active' => '1',
        ]);
        
    }
}
