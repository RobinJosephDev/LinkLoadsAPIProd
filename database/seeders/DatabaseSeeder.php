<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LeadSeeder::class,
            LeadFollowupSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            QuoteSeeder::class,
            ShipmentSeeder::class,
            CarrierSeeder::class,
            VendorSeeder::class,
            BrokerSeeder::class,
        ]);
    }
}
