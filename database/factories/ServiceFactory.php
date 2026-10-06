<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->catchPhrase(),
            'description' => fake()->paragraph(),
            'icon' => null,
            'technologies' => ['Laravel', 'Tailwind'],
            'published' => true,
            'sort_order' => 0,
        ];
    }
}
