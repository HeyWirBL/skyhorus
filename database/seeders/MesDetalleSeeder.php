<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mes_detalles')->insert([
            'nombre' => 'Enero',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Febrero',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Marzo',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Abril',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Mayo',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Junio',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Julio',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Agosto',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Septiembre',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Octubre',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Noviembre',        
        ]);

        DB::table('mes_detalles')->insert([
            'nombre' => 'Diciembre',        
        ]);
    }
}
