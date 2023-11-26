<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illse\Console\Seeds\WithoutModelEvents;
use Illse\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*
            1 = express
            7 = weekly
            14 = silver
            30 = gold
            90 = platinum
            180 = Black
            365 = Diamond
        */


        Plan::create([
            'name' => 'Premium Express',
            'description' => 'Premium plan for one day',
            'price' => 1.99,
            'profit_percent' => 20,
            'signup_fee' => 0.99,
            'min' => 100,
            'min' => 5000,
            'invoice_period' => 1,
            'invoice_interval' => 'day',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'USD',
            'slug' => '',
        ]);

        Plan::create([
            'name' => 'Premium Weekly',
            'description' => 'Premium plan for one week',
            'price' => 9.99,
            'profit_percent' => 40,
            'signup_fee' => 0.99,
            'min' => 100,
            'min' => 5000,
            'invoice_period' => 7,
            'invoice_interval' => 'day',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'USD',
            'slug' => '',
        ]);

        // Platinum, Gold, Express, Black
        Plan::create([
            'name' => 'Premium Silver',
            'description' => 'Premium plan for one week1',
            'price' => 14.99,
            'signup_fee' => 0.99,
            'min' => 100,
            'min' => 5000,
            'profit_percent' => 60,
            'invoice_period' => 14,
            'invoice_interval' => 'day',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'USD',
            'slug' => '',
        ]);
    }
}
