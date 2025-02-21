<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'cust_name' => $this->faker->name(),
            'cust_type' => 'Regular', // Set a default value
            'cust_ref_no' => 'CUST-' . strtoupper($this->faker->unique()->bothify('??###')), // Unique + readable
            'cust_website' => $this->faker->url(),
            'cust_email' => $this->faker->unique()->safeEmail(),
            'cust_contact_no' => $this->faker->unique()->numerify('##########'), // Ensure unique phone numbers
            'cust_primary_address' => $this->faker->address(),
            'cust_primary_city' => $this->faker->city(),
            'cust_primary_state' => $this->faker->state(),
            'cust_primary_country' => $this->faker->country(),
            'cust_primary_postal' => $this->faker->postcode(),
            'cust_credit_status' => 'Approved',
            'cust_credit_terms' => 'Net 30',
            'cust_credit_limit' => $this->faker->randomFloat(2, 5000, 50000), // Vary credit limit
            'cust_credit_currency' => $this->faker->randomElement(['USD', 'EUR', 'INR']),
        ];
    }
}

