<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('anos')->insert([
            'ano' => '2023'
        ]);

        DB::table('anos')->insert([
            'ano' => '2022'
        ]);

        DB::table('anos')->insert([
            'ano' => '2021'
        ]);

        DB::table('anos')->insert([
            'ano' => '2020'
        ]);
    }
}
