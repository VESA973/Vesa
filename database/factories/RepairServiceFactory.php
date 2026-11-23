<?php

namespace Database\Factories;

use App\Models\RepairDevice;
use App\Models\RepairService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RepairService>
 */
class RepairServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'repair_device_id' => RepairDevice::factory(),
            'name' => fake()->sentence(3),
            'description' => null,
            'price_from' => null,
            'sort_order' => 0,
        ];
    }
}
