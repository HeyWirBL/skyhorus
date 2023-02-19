<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meses')->insert([
            'nombre' => 'Enero',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Febrero',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Marzo',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Abril',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Mayo',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Junio',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Julio',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Agosto',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Septiembre',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Octubre',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Noviembre',        
        ]);

        DB::table('meses')->insert([
            'nombre' => 'Diciembre',        
        ]);
    }
}
