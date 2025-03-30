<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer' => $this->faker->company,
            'customer_ref_no' => $this->faker->uuid,
            'branch' => $this->faker->city,
            'booked_by' => $this->faker->name,
            'account_rep' => $this->faker->name,
            'sales_rep' => $this->faker->name,
            'customer_po_no' => $this->faker->randomNumber(6),
            'commodity' => $this->faker->word,
            'equipment' => $this->faker->word,
            'load_type' => $this->faker->randomElement(['Full', 'Partial']),
            'temperature' => $this->faker->randomFloat(2, -30, 30),
            'origin_location' => ['city' => $this->faker->city, 'state' => $this->faker->state],
            'destination_location' => ['city' => $this->faker->city, 'state' => $this->faker->state],
            'hot' => $this->faker->boolean,
            'team' => $this->faker->boolean,
            'air_ride' => $this->faker->boolean,
            'tarp' => $this->faker->boolean,
            'hazmat' => $this->faker->boolean,
            'currency' => 'USD',
            'base_price' => $this->faker->randomFloat(2, 500, 5000),
            'charges' => [['type' => 'Fuel', 'amount' => 100]],
            'discounts' => [['type' => 'Promo', 'amount' => 50]],
            'gst' => $this->faker->randomFloat(2, 1, 10),
            'pst' => $this->faker->randomFloat(2, 1, 10),
            'hst' => $this->faker->randomFloat(2, 1, 10),
            'qst' => $this->faker->randomFloat(2, 1, 10),
            'final_price' => $this->faker->randomFloat(2, 1000, 10000),
            'notes' => $this->faker->sentence,
        ];
    }
}
