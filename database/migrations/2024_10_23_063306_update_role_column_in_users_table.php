<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateRoleColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() === 'pgsql') {
            // Step 1: Ensure all existing role values are valid
            DB::table('users')->whereNotIn('role', ['user', 'admin', 'employee'])
                ->update(['role' => 'user']); // Default to 'user' if invalid

            // Step 2: Create the ENUM type for PostgreSQL if it does not exist
            DB::statement("DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_role') THEN
                    CREATE TYPE user_role AS ENUM ('user', 'admin', 'employee');
                END IF;
            END $$;");

            // Step 3: Add a temporary column with the new ENUM type
            DB::statement("ALTER TABLE users ADD COLUMN new_role user_role");

            // Step 4: Migrate data to the new column
            DB::statement("UPDATE users SET new_role = role::user_role");

            // Step 5: Drop the old 'role' column
            DB::statement("ALTER TABLE users DROP COLUMN role");

            // Step 6: Rename 'new_role' to 'role'
            DB::statement("ALTER TABLE users RENAME COLUMN new_role TO role");

            // Step 7: Set the default value for the new 'role' column
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'user'");
        } else {
            // For MySQL and SQLite, use Laravel's enum logic
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin', 'employee'])->default('user')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop the custom ENUM type if rolling back
            DB::statement('DROP TYPE IF EXISTS user_role');

            // Revert 'role' column to string
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin'])->default('user')->change();
            });
        }
    }
}
