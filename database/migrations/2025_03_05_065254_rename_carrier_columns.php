<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->renameColumn('contact', 'contacts');
            $table->renameColumn('equipment', 'equipments');
            $table->renameColumn('lane', 'lanes');
        });
    }

    public function down()
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->renameColumn('contacts', 'contact');
            $table->renameColumn('equipments', 'equipment');
            $table->renameColumn('lanes', 'lane');
        });
    }
};
