<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Step 1: Add a new column as TEXT
            DB::statement("ALTER TABLE users ADD COLUMN new_role TEXT");

            // Step 2: Copy data from ENUM column to TEXT column
            DB::statement("UPDATE users SET new_role = role::TEXT");

            // Step 3: Drop the ENUM column
            DB::statement("ALTER TABLE users DROP COLUMN role");

            // Step 4: Rename new column to 'role'
            DB::statement("ALTER TABLE users RENAME COLUMN new_role TO role");
        } else {
            // For MySQL and SQLite, change ENUM to STRING
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 50)->default('user')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Reverse to ENUM type (if needed)
            DB::statement("CREATE TYPE user_role AS ENUM ('user', 'admin', 'employee')");
            DB::statement("ALTER TABLE users ADD COLUMN new_role user_role");
            DB::statement("UPDATE users SET new_role = role::user_role");
            DB::statement("ALTER TABLE users DROP COLUMN role");
            DB::statement("ALTER TABLE users RENAME COLUMN new_role TO role");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin', 'employee'])->default('user')->change();
            });
        }
    }
};
