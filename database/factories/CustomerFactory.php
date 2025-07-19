<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => 1, // adjust as needed, or make it dynamic
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['m', 'f', 'o']),
            'email' => $this->faker->unique()->safeEmail(),
            'country_code' => '+91',
            'phone' => $this->faker->numerify('##########'),
            'dob' => $this->faker->date('Y-m-d', '2010-01-01'),
            'address' => json_encode([
                'value' => $this->faker->address()
            ]),
            'base_measurements' => json_encode([
                'unit' => $this->faker->randomElement(['cm', 'in']),
                'length' => $this->faker->numberBetween(100, 180),
                'front_neck' => $this->faker->numberBetween(5, 20),
                'sleeve_circle' => $this->faker->numberBetween(5, 20),
                'sleeve_length' => $this->faker->numberBetween(10, 60),
                'seat' => $this->faker->numberBetween(30, 50),
                'arms' => $this->faker->numberBetween(10, 50),
                'waist' => $this->faker->numberBetween(25, 40),
                'chest' => $this->faker->numberBetween(30, 50),
                'shoulder' => $this->faker->numberBetween(10, 20),
                'back_neck' => $this->faker->numberBetween(5, 15),
            ]),
            'notes' => json_encode([
                [
                    'label' => $this->faker->sentence(3),
                    'text' => $this->faker->sentence(5),
                ]
            ]),
        ];
    }
}
