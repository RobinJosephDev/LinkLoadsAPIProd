<?php

namespace Database\Factories;

use App\Models\LeadFollowup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeadFollowupFactory extends Factory
{
    protected $model = LeadFollowup::class;

    public function definition()
    {
        return [
            'lead_no' => $this->faker->unique()->regexify('L[0-9]{5}'),
            'lead_date' => $this->faker->date(),
            'customer_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'lead_status' => $this->faker->randomElement([
                'New',
                'In Progress',
                'Completed',
                'On Hold',
                'Lost'
            ]),
            'next_follow_up_date' => $this->faker->date(),
            'remarks' => $this->faker->sentence,
            'equipment' => $this->faker->randomElement([
                'Van',
                'Reefer',
                'Flatbed',
                'Triaxle',
                'Maxi',
                'Btrain',
                'Roll tite'
            ]),
            'products' => json_encode([
                [
                    'id' => Str::uuid(), // Unique ID for each product
                    'name' => $this->faker->word,
                    'quantity' => $this->faker->numberBetween(1, 100),
                ],
                [
                    'id' => Str::uuid(), // Unique ID for each product
                    'name' => $this->faker->word,
                    'quantity' => $this->faker->numberBetween(1, 50),
                ]
            ]),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'unit_no' => $this->faker->word,
            'lead_type' => $this->faker->randomElement([
                'AB',
                'BC',
                'BDS',
                'CA',
                'DPD MAGMA',
                'MB',
                'ON',
                'Super Leads',
                'TBAB',
                'USA'
            ]),
            'contact_person' => $this->faker->name,
            'notes' => $this->faker->sentence,
            'contacts' => json_encode([
                [
                    'id' => Str::uuid(), // Unique ID for each contact
                    'name' => $this->faker->name,
                    'phone' => $this->faker->phoneNumber,
                    'email' => $this->faker->safeEmail,
                ]
            ]),
        ];
    }
}
