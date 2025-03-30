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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cust_name')->nullable();
            $table->string('cust_type')->nullable();
            $table->string('cust_ref_no')->nullable();
            $table->string('cust_website')->nullable();
            $table->string('cust_email')->nullable();
            $table->string('cust_contact_no')->nullable();
            $table->string('cust_contact_no_ext')->nullable();
            $table->string('cust_tax_id')->nullable();
            $table->string('cust_primary_address')->nullable();
            $table->string('cust_primary_city')->nullable();
            $table->string('cust_primary_state')->nullable();
            $table->string('cust_primary_country')->nullable();
            $table->string('cust_primary_postal')->nullable();
            $table->integer('cust_primary_unit_no')->nullable();
            $table->string('cust_mailing_address')->nullable();
            $table->string('cust_mailing_city')->nullable();
            $table->string('cust_mailing_state')->nullable();
            $table->string('cust_mailing_country')->nullable();
            $table->string('cust_mailing_postal')->nullable();
            $table->integer('cust_mailing_unit_no')->nullable();
            $table->string('cust_ap_name')->nullable();
            $table->string('cust_ap_address')->nullable();
            $table->string('cust_ap_city')->nullable();
            $table->string('cust_ap_state')->nullable();
            $table->string('cust_ap_country')->nullable();
            $table->string('cust_ap_postal')->nullable();
            $table->integer('cust_ap_unit_no')->nullable();
            $table->string('cust_ap_email')->nullable();
            $table->string('cust_ap_phone')->nullable();
            $table->string('cust_ap_phone_ext')->nullable();
            $table->string('cust_ap_fax')->nullable();
            $table->string('cust_broker_name')->nullable();
            $table->string('cust_bkp_notes')->nullable();
            $table->text('cust_bkspl_notes')->nullable();
            $table->string('cust_credit_status')->nullable();
            $table->string('cust_credit_mop')->nullable();
            $table->date('cust_credit_appd')->nullable();
            $table->date('cust_credit_expd')->nullable();
            $table->string('cust_credit_terms')->nullable();
            $table->string('cust_credit_limit')->nullable();
            $table->string('cust_credit_notes')->nullable();
            $table->tinyInteger('cust_credit_application')->nullable(); 
            $table->string('cust_credit_currency')->nullable();
            $table->string('cust_sbk_agreement')->nullable();
            $table->string('cust_credit_agreement')->nullable();
            $table->longText('cust_contact')->nullable();
            $table->longText('cust_equipment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
