<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Convert JSONB to JSON (PostgreSQL does not allow direct type change)
        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->dropColumn('contacts'); // Drop the existing jsonb column
        });

        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->json('contacts')->nullable(); // Recreate it as JSON
        });
    }

    public function down()
    {
        // Convert JSON back to JSONB (if rollback is needed)
        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->dropColumn('contacts'); // Drop the existing json column
        });

        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->jsonb('contacts')->nullable(); // Recreate it as JSONB
        });
    }
};

