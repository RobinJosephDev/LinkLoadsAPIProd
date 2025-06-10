<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEmployeeAccessTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employee_access');
    }

    public function down()
    {
        // Optional: recreate the table if rolling back
        Schema::create('employee_access', function (Blueprint $table) {
            $table->id();
            // define columns again if needed
            $table->timestamps();
        });
    }
}

