<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(array_keys(Project::CATEGORIES)),
            'client' => fake()->company(),
            'year' => fake()->numberBetween(2022, 2026),
            'excerpt' => fake()->sentence(),
            'challenge' => fake()->paragraph(),
            'solution' => fake()->paragraph(),
            'result' => fake()->paragraph(),
            'technologies' => ['Laravel', 'Tailwind CSS'],
            'is_featured' => false,
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }
}
