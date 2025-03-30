<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id(); 
            $table->string('dba')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('remit_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('branch')->nullable();
            $table->string('website')->nullable();
            $table->string('fed_id_no')->nullable();
            $table->string('pref_curr', 10)->nullable(); 
            $table->string('pay_terms')->nullable();
            $table->boolean('1099')->default(false); 
            $table->boolean('advertise')->default(false);
            $table->string('advertise_email')->nullable();
            $table->string('carr_type')->nullable(); 
            $table->string('rating')->nullable(); 
            $table->string('brok_carr_aggmt')->nullable(); 
            $table->string('docket_no')->nullable();
            $table->string('dot_number')->nullable();
            $table->string('wcb_no')->nullable();
            $table->string('ca_bond_no')->nullable();
            $table->string('us_bond_no')->nullable();
            $table->string('scac')->nullable(); 
            $table->boolean('csa_approved')->default(false); 
            $table->boolean('hazmat')->default(false);
            $table->string('smsc_code')->nullable();
            $table->boolean('approved')->default(false); 
            $table->string('li_provider')->nullable(); 
            $table->string('li_policy_no')->nullable();
            $table->decimal('li_coverage', 15, 2)->nullable(); 
            $table->date('li_start_date')->nullable();
            $table->date('li_end_date')->nullable();
            $table->string('ci_provider')->nullable(); 
            $table->string('ci_policy_no')->nullable();
            $table->decimal('ci_coverage', 15, 2)->nullable(); 
            $table->date('ci_start_date')->nullable();
            $table->date('ci_end_date')->nullable();
            $table->string('coi_cert')->nullable(); 
            $table->string('primary_address')->nullable();
            $table->string('primary_city')->nullable();
            $table->string('primary_state')->nullable();
            $table->string('primary_country')->nullable();
            $table->string('primary_postal')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_country')->nullable();
            $table->string('mailing_postal')->nullable();
            $table->string('mailing_phone')->nullable();
            $table->text('int_notes')->nullable(); // Internal notes
            $table->json('contact')->nullable(); // Array for contact
            $table->json('equipment')->nullable(); // Array for equipment
            $table->json('lane')->nullable(); // Array for lanes or routes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carriers');
    }
}
