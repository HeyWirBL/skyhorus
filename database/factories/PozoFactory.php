<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pozo>
 */
class PozoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'punto_muestreo' => $this->faker->sentence(3),
            'fecha_hora' => Carbon::now(),
            'identificador' => $this->faker->word(),
            'presion_kgcm2' => $this->faker->randomFloat(1, 20, 30),
            'presion_psi' => $this->faker->randomFloat(1, 20, 30),
            'temp_C' => $this->faker->randomFloat(1, 20, 30),
            'temp_F' => $this->faker->randomFloat(1, 20, 30),
            'volumen_cm3' => $this->faker->randomFloat(1),
            'volumen_lts' => $this->faker->randomFloat(1),
            'nombre_pozo' => $this->faker->sentence(3),
        ];
    }
}
