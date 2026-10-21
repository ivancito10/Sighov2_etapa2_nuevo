<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonaAsus>
 */
class PersonaAsusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $faker = \Faker\Factory::create();
        return [
            'nombre' => $faker->firstName,
            'apPaterno' => $faker->lastName,
            'apMaterno' => $faker->lastName,
            'ci' => $faker->numerify('##########'),
            'complemento' => $faker->sentence,
            'tipo_documento' => $faker->randomElement(['DNI', 'Pasaporte', 'Carné de Identidad']),
            'matricula' => $faker->numerify('#######'),
            'fecha_nacimiento' => $faker->date($format = 'Y-m-d', $max = 'now'),
        ];
    }
}
