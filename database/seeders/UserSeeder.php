<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        DB::table('users')->insert([
            'name' => 'goodness',
            'email' => 'goody@gmail.com',
            'password' => Hash::make('goody123'),
        ]);

        DB::table('users')->insert([
            'name' => 'new',
            'email' => 'new@gmail.com',
            'password' => Hash::make('new12345'),
        ]);

    }
}
