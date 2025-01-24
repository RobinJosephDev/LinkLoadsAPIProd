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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->integer("lead_no");
            $table->date("lead_date");
            $table->date("follow_up_date")->nullable();
            $table->string("customer_name");
            $table->bigInteger("phone");
            $table->string("email");
            $table->string("website")->nullable();
            $table->string("equipment_type")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("country")->nullable();
            $table->string("postal_code")->nullable();
            $table->integer("unit_no")->nullable();
            $table->string("lead_type");
            $table->string("contact_person")->nullable();
            $table->string("lead_status");
            $table->string("notes")->nullable();
            $table->json("contacts")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
