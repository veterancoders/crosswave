<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Investment::create([
            'user_id' => 9,
            'amount' => 100,
            'status' =>  'Pending',
            'plan_id' => 6,
            'reinvest_limit' => 2,
             'can_reinvest' => 1,

        ]);

    }
}
