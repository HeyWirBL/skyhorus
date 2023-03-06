<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('directorios')->insert([
            'nombre_dir' => 'Informe Final',
            'fecha_dir' => '2022-12-12',
        ]);

        DB::table('directorios')->insert([
            'nombre_dir' => 'Mediciones',
            'fecha_dir' => '2022-12-12',
        ]);

        DB::table('directorios')->insert([
            'nombre_dir' => 'Actas',
            'fecha_dir' => '2022-12-12',
        ]);
    }
}
