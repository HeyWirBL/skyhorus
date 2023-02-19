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
            'email' => 'jesus@admin.com',
            'password' => Hash::make('Desarrollo123#2023'),
            'rol' => 'Administrador',
        ]);            
    }
}
