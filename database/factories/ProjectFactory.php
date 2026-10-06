<?php

namespace Database\Factories;

use App\Enums\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(12),
            'full_description' => fake()->paragraphs(3, true),
            'category' => fake()->randomElement(ProjectCategory::cases())->value,
            'github_url' => 'https://github.com/example/'.Str::slug($title),
            'live_url' => null,
            'featured' => false,
            'published' => true,
            'sort_order' => 0,
            'project_date' => fake()->dateTimeBetween('-2 years')->format('Y-m-d'),
            'problem' => fake()->paragraph(),
            'solution' => fake()->paragraph(),
            'features' => [fake()->sentence(4), fake()->sentence(4), fake()->sentence(4)],
            'architecture' => fake()->paragraph(),
            'technical_implementation' => fake()->paragraph(),
            'challenges' => fake()->paragraph(),
            'results' => fake()->paragraph(),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['published' => false]);
    }
}
