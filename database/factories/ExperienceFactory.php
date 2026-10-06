<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-5 years', '-1 year');

        return [
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'location' => fake()->city(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween($start, 'now')->format('Y-m-d'),
            'is_current' => false,
            'description' => fake()->paragraph(),
            'responsibilities' => [fake()->sentence(6), fake()->sentence(6)],
            'technologies' => ['Laravel', 'PostgreSQL'],
            'sort_order' => 0,
        ];
    }
}
