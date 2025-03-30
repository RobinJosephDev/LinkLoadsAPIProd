<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable(); // Make sure to handle this correctly for authentication
            $table->enum('role', ['user', 'admin', 'employee'])->default('user')->nullable();
            $table->timestamp('email_verified_at')->nullable(); // Add this line
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
