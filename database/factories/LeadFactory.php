<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition()
    {
        return [
            'lead_no' => 'LEAD-' . strtoupper($this->faker->unique()->bothify('??###')), // Unique, structured ID
            'lead_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'follow_up_date' => $this->faker->optional()->dateTimeBetween('now', '+6 months'),
            'customer_name' => $this->faker->company,
            'phone' => $this->faker->unique()->numerify('##########'), // Ensure unique phone numbers
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->optional()->url,
            'equipment_type' => $this->faker->randomElement(['Truck', 'Trailer', 'Forklift', 'Crane']),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'unit_no' => $this->faker->optional()->buildingNumber,
            'lead_type' => $this->faker->randomElement(['New', 'Existing', 'Referred']),
            'contact_person' => $this->faker->name,
            'lead_status' => $this->faker->randomElement(['Open', 'Closed', 'Pending']),
            'notes' => $this->faker->optional()->sentence,
            'assigned_to' => $this->faker->optional()->name,
            'contacts' => json_encode([
                [
                    'name' => $this->faker->name,
                    'phone' => $this->faker->unique()->numerify('##########'),
                    'email' => $this->faker->unique()->safeEmail,
                ],
                [
                    'name' => $this->faker->name,
                    'phone' => $this->faker->unique()->numerify('##########'),
                    'email' => $this->faker->unique()->safeEmail,
                ]
            ]),
        ];
    }
}
