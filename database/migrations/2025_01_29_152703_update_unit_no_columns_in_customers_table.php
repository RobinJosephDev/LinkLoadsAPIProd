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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('cust_primary_unit_no')->nullable()->change();
            $table->string('cust_mailing_unit_no')->nullable()->change();
            $table->string('cust_ap_unit_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('cust_primary_unit_no')->nullable()->change();
            $table->integer('cust_mailing_unit_no')->nullable()->change();
            $table->integer('cust_ap_unit_no')->nullable()->change();
        });
    }
};
