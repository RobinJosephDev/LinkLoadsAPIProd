<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shipment;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'ship_load_date' => Carbon::now()->subDays(rand(1, 30)), // Random past date
            'ship_pickup_location' => $this->faker->address(),
            'ship_delivery_location' => $this->faker->address(),
            'ship_driver' => $this->faker->name(),
            'ship_weight' => $this->faker->randomFloat(2, 500, 50000), // Weight in kg
            'ship_ftl_ltl' => $this->faker->randomElement(['FTL', 'LTL']),
            'ship_tarp' => $this->faker->boolean(50), // 50% chance of having tarp
            'ship_equipment' => $this->faker->randomElement(['Flatbed', 'Reefer', 'Dry Van']),
            'ship_price' => $this->faker->randomFloat(2, 500, 10000), // Shipment price
            'ship_notes' => $this->faker->sentence(),
        ];
    }
}
