<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'iso' => 'NG',
            'name' => 'NIGERIA',
            'nicename' => 'Nigeria',
            'iso3' => 'NGA',
            'numcode' => '566',
            'phonecode' => '234',
            'status' => 'active',
          
        ]);
    }
}
