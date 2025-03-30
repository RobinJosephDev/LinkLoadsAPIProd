<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vendor;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['Supplier', 'Carrier', 'Broker']),
            'legal_name' => $this->faker->company,
            'remit_name' => $this->faker->company,
            'vendor_type' => $this->faker->randomElement(['Logistics', 'Equipment', 'Services']),
            'service' => $this->faker->word,
            'primary_address' => $this->faker->address,
            'primary_city' => $this->faker->city,
            'primary_state' => $this->faker->state,
            'primary_country' => $this->faker->country,
            'primary_postal' => $this->faker->postcode,
            'primary_email' => $this->faker->unique()->safeEmail,
            'primary_phone' => $this->faker->phoneNumber,
            'primary_fax' => $this->faker->optional()->phoneNumber,
            'scac' => strtoupper($this->faker->bothify('??##')), // SCAC typically follows this format
            'docket_number' => $this->faker->unique()->numerify('DOC-#####'),
            'vendor_code' => strtoupper($this->faker->unique()->bothify('VND-??###')),
            'gst_hst_number' => $this->faker->regexify('[0-9]{9}'),
            'qst_number' => $this->faker->regexify('[0-9]{10}'),
            'ca_bond_number' => strtoupper($this->faker->regexify('[A-Z0-9]{12}')),
            'website' => $this->faker->optional()->url,
            'mailing_address' => $this->faker->address,
            'mailing_city' => $this->faker->city,
            'mailing_state' => $this->faker->state,
            'mailing_country' => $this->faker->country,
            'mailing_postal' => $this->faker->postcode,
            'mailing_email' => $this->faker->unique()->safeEmail,
            'mailing_phone' => $this->faker->phoneNumber,
            'mailing_fax' => $this->faker->optional()->phoneNumber,
            'us_tax_id' => $this->faker->regexify('[0-9]{2}-[0-9]{7}'),
            'payroll_no' => $this->faker->unique()->numerify('PAY-######'),
            'wcb_no' => strtoupper($this->faker->regexify('[A-Z0-9]{8}')),
            'ar_name' => $this->faker->name,
            'ar_email' => $this->faker->unique()->safeEmail,
            'ar_contact_no' => $this->faker->phoneNumber,
            'ar_ext' => $this->faker->numerify('###'),
            'ap_name' => $this->faker->name,
            'ap_email' => $this->faker->unique()->safeEmail,
            'ap_contact_no' => $this->faker->phoneNumber,
            'ap_ext' => $this->faker->numerify('###'),
            'bank_name' => $this->faker->company,
            'bank_phone' => $this->faker->phoneNumber,
            'bank_email' => $this->faker->unique()->safeEmail,
            'bank_us_acc_no' => $this->faker->bankAccountNumber,
            'bank_cdn_acc_no' => $this->faker->bankAccountNumber,
            'bank_address' => $this->faker->address,
            'cargo_company' => $this->faker->company,
            'cargo_policy_start' => $this->faker->date,
            'cargo_policy_end' => $this->faker->date,
            'cargo_ins_amt' => $this->faker->randomFloat(2, 10000, 100000),
            'liab_company' => $this->faker->company,
            'liab_policy_start' => $this->faker->date,
            'liab_policy_end' => $this->faker->date,
            'liab_ins_amt' => $this->faker->randomFloat(2, 50000, 500000),
            'contacts' => json_encode([
                [
                    'name' => $this->faker->name,
                    'email' => $this->faker->unique()->safeEmail,
                    'phone' => $this->faker->phoneNumber,
                ],
                [
                    'name' => $this->faker->name,
                    'email' => $this->faker->unique()->safeEmail,
                    'phone' => $this->faker->phoneNumber,
                ],
            ]),
        ];
    }
}
