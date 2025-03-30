<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('legal_name');
            $table->string('remit_name')->nullable();
            $table->string('vendor_type')->nullable();
            $table->string('service')->nullable();
            $table->string('primary_address')->nullable();
            $table->string('primary_city')->nullable();
            $table->string('primary_state')->nullable();
            $table->string('primary_country')->nullable();
            $table->string('primary_postal')->nullable();
            $table->string('primary_email')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('primary_fax')->nullable();
            $table->string('scac')->nullable();
            $table->string('docket_number')->nullable();
            $table->string('vendor_code')->nullable();
            $table->string('gst_hst_number')->nullable();
            $table->string('qst_number')->nullable();
            $table->string('ca_bond_number')->nullable();
            $table->string('website')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_country')->nullable();
            $table->string('mailing_postal')->nullable();
            $table->string('mailing_email')->nullable();
            $table->string('mailing_phone')->nullable();
            $table->string('mailing_fax')->nullable();
            $table->string('us_tax_id')->nullable();
            $table->string('payroll_no')->nullable();
            $table->string('wcb_no')->nullable();
            $table->string('ar_name')->nullable();
            $table->string('ar_email')->nullable();
            $table->string('ar_contact_no')->nullable();
            $table->string('ar_ext')->nullable();
            $table->string('ap_name')->nullable();
            $table->string('ap_email')->nullable();
            $table->string('ap_contact_no')->nullable();
            $table->string('ap_ext')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_phone')->nullable();
            $table->string('bank_email')->nullable();
            $table->string('bank_us_acc_no')->nullable();
            $table->string('bank_cdn_acc_no')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('cargo_company')->nullable();
            $table->date('cargo_policy_start')->nullable();
            $table->date('cargo_policy_end')->nullable();
            $table->decimal('cargo_ins_amt', 15, 2)->nullable();
            $table->string('liab_company')->nullable();
            $table->date('liab_policy_start')->nullable();
            $table->date('liab_policy_end')->nullable();
            $table->decimal('liab_ins_amt', 15, 2)->nullable();
            $table->json('contacts')->nullable(); // For storing array data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
