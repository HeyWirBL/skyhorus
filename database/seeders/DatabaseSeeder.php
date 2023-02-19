<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'nombre' => 'Jesús Manuel',
            'apellidos' => 'Méndez García',
            'usuario' => 'Jesus.Mendez',
            'email' => 'jesus123@admin.com',
            'password' => Hash::make('Desarrollo123#2023'),
            'rol' => 'Administrador',
        ]);            

        \App\Models\User::factory()->create([
            'nombre' => 'Juan',
            'apellidos' => 'Solano',
            'usuario' => 'Juan.Solano',
            'email' => 'juan123@admin.com',
            'password' => Hash::make('PetroHorus#2023'),
            'rol' => 'Administrador',
        ]);  

        \App\Models\User::factory()->create([
            'nombre' => 'Carlos Adrián',
            'apellidos' => 'Gómez Monroy',
            'usuario' => 'Carlos.Monroy',
            'email' => 'carlos123@admin.com',
            'password' => Hash::make('PetroHorus#2023'),
            'rol' => 'Administrador',
        ]);  
    }
}
