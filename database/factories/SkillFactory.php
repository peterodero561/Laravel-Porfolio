<?php

namespace Database\Factories;

use App\Enums\SkillCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'category' => fake()->randomElement(SkillCategory::cases())->value,
            'icon' => null,
            'published' => true,
            'sort_order' => 0,
        ];
    }
}
