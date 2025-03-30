<?php

namespace Database\Factories;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'quote_type' => $this->faker->randomElement(['Standard', 'Expedited', 'Express']),
            'quote_customer' => $this->faker->company,
            'quote_cust_ref_no' => 'REF-' . $this->faker->unique()->numerify('######'), // Unique structured reference
            'quote_booked_by' => $this->faker->name,
            'quote_temperature' => $this->faker->randomFloat(2, -30, 30),
            'quote_hot' => $this->faker->boolean,
            'quote_team' => $this->faker->boolean,
            'quote_air_ride' => $this->faker->boolean,
            'quote_tarp' => $this->faker->boolean,
            'quote_hazmat' => $this->faker->boolean,
            'quote_pickup' => json_encode([
                'city' => $this->faker->city,
                'state' => $this->faker->stateAbbr,
                'zip' => $this->faker->postcode,
                'date' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            ]),
            'quote_delivery' => json_encode([
                'city' => $this->faker->city,
                'state' => $this->faker->stateAbbr,
                'zip' => $this->faker->postcode,
                'date' => $this->faker->dateTimeBetween('+2 days', '+2 weeks')->format('Y-m-d'),
            ]),
        ];
    }
}

