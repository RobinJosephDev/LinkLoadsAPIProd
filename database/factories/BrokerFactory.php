<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Broker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Broker>
 */
class BrokerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Broker::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'broker_name' => $this->faker->company,
            'broker_address' => $this->faker->streetAddress,
            'broker_city' => $this->faker->city,
            'broker_state' => $this->faker->state,
            'broker_country' => $this->faker->country,
            'broker_postal' => $this->faker->postcode,
            'broker_email' => 'broker' . mt_rand(1000, 9999) . '@example.com',
            'broker_phone' => $this->faker->phoneNumber,
            'broker_ext' => $this->faker->optional()->numerify('###'),
            'broker_fax' => $this->faker->optional()->phoneNumber,
        ];
    }
}
