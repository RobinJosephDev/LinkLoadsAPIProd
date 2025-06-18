<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('invoice_terms')->nullable();
            $table->string('rate_conf_terms')->nullable();
            $table->string('quote_terms')->nullable();
            $table->string('invoice_reminder')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('cell')->nullable();
            $table->string('fax')->nullable();

            $table->string('invoice_prefix')->nullable();
            $table->string('SCAC')->nullable();
            $table->string('docket_no')->nullable();
            $table->string('carrier_code')->nullable();

            $table->string('gst_hst_no')->nullable();
            $table->string('qst_no')->nullable();
            $table->string('ca_bond_no')->nullable();
            $table->string('us_tax_id')->nullable();
            $table->string('payroll_no')->nullable();
            $table->string('wcb_no')->nullable();

            $table->string('dispatch_email')->nullable();
            $table->string('ap_email')->nullable();
            $table->string('ar_email')->nullable();
            $table->string('cust_comm_email')->nullable();
            $table->string('quot_email')->nullable();

            $table->json('bank_info')->nullable();
            $table->json('cargo_insurance')->nullable();
            $table->json('liablility_insurance')->nullable();

            $table->string('company_package')->nullable();
            $table->string('insurance')->nullable();

            $table->boolean('obsolete')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
