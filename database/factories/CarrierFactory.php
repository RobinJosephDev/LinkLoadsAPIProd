<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Carrier;

class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    public function definition(): array
    {
        return [
            'dba' => $this->faker->company(),
            'legal_name' => $this->faker->company(),
            'remit_name' => $this->faker->company(),
            'acc_no' => 'ACC-' . mt_rand(100000, 999999), // Removed unique()
            'branch' => $this->faker->city(),
            'website' => $this->faker->url(),
            'fed_id_no' => mt_rand(100000000, 999999999), // Removed unique()
            'pref_curr' => $this->faker->randomElement(['USD', 'EUR', 'INR']),
            'pay_terms' => $this->faker->randomElement(['Net 30', 'Net 45', 'Net 60']),
            'form_1099' => $this->faker->boolean(),
            'advertise' => $this->faker->boolean(),
            'advertise_email' => $this->faker->unique()->safeEmail(), // Kept unique()
            'carr_type' => $this->faker->randomElement(['Owner Operator', 'Fleet']),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'brok_carr_aggmt' => null, // Placeholder for file upload
            'docket_no' => 'DKT' . mt_rand(100000, 999999), // Reduced uniqueness issues
            'dot_number' => 'DOT' . mt_rand(100000, 999999), // Kept unique format but removed Faker's unique()
            'wcb_no' => 'WCB' . mt_rand(100000, 999999), // Removed unique()
            'ca_bond_no' => 'CAB' . mt_rand(100000, 999999), // Removed unique()
            'us_bond_no' => 'USB' . mt_rand(100000, 999999), // Removed unique()
            'scac' => strtoupper($this->faker->lexify('????')),
            'csa_approved' => $this->faker->boolean(),
            'hazmat' => $this->faker->boolean(),
            'smsc_code' => strtoupper($this->faker->lexify('?????')),
            'approved' => $this->faker->boolean(),
            'li_provider' => $this->faker->company(),
            'li_policy_no' => 'LI-' . mt_rand(10000, 99999), // Removed unique()
            'li_coverage' => $this->faker->randomFloat(2, 100000, 5000000),
            'li_start_date' => $this->faker->date(),
            'li_end_date' => $this->faker->date(),
            'ci_provider' => $this->faker->company(),
            'ci_policy_no' => 'CI-' . mt_rand(10000, 99999), // Removed unique()
            'ci_coverage' => $this->faker->randomFloat(2, 100000, 5000000),
            'ci_start_date' => $this->faker->date(),
            'ci_end_date' => $this->faker->date(),
            'coi_cert' => null, // Placeholder for file upload
            'primary_address' => $this->faker->address(),
            'primary_city' => $this->faker->city(),
            'primary_state' => $this->faker->state(),
            'primary_country' => $this->faker->country(),
            'primary_postal' => $this->faker->postcode(),
            'primary_phone' => $this->faker->phoneNumber(),
            'mailing_address' => $this->faker->address(),
            'mailing_city' => $this->faker->city(),
            'mailing_state' => $this->faker->state(),
            'mailing_country' => $this->faker->country(),
            'mailing_postal' => $this->faker->postcode(),
            'mailing_phone' => $this->faker->phoneNumber(),
            'int_notes' => $this->faker->sentence(),
            'contact' => json_encode([
                [
                    'name' => $this->faker->name(),
                    'phone' => $this->faker->phoneNumber(),
                    'email' => $this->faker->email(),
                ],
            ]),
            'equipment' => json_encode($this->faker->randomElements(['Flatbed', 'Reefer', 'Dry Van', 'Box Truck'], 2)),
            'lane' => json_encode([
                'origin' => $this->faker->city(),
                'destination' => $this->faker->city(),
                'distance' => $this->faker->randomFloat(2, 100, 3000),
            ]),
        ];
    }
}
