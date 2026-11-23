<?php

namespace Database\Factories;

use App\Models\RepairDevice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RepairDevice>
 */
class RepairDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'family' => fake()->randomElement(array_keys(RepairDevice::FAMILIES)),
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }
}
