<?php

namespace Database\Seeders;

use App\Models\LeadFollowup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadFollowupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("TRUNCATE TABLE lead_follow_up RESTART IDENTITY CASCADE");
        LeadFollowup::factory()->count(1)->create();
    }
}
