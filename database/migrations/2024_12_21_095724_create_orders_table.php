<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // auto-incrementing ID
            $table->string('customer')->nullable();
            $table->string('customer_ref_no')->nullable();
            $table->string('branch')->nullable();
            $table->string('booked_by')->nullable();
            $table->string('account_rep')->nullable();
            $table->string('sales_rep')->nullable();
            $table->string('customer_po_no')->nullable();
            $table->string('commodity')->nullable();
            $table->string('equipment')->nullable();
            $table->string('load_type')->nullable();
            $table->string('temperature')->nullable();
            $table->json('origin_location')->nullable();
            $table->json('destination_location')->nullable();
            $table->boolean('hot')->default(false)->nullable();
            $table->boolean('team')->default(false)->nullable();
            $table->boolean('air_ride')->default(false)->nullable();
            $table->boolean('tarp')->default(false)->nullable();
            $table->boolean('hazmat')->default(false)->nullable();
            $table->string('currency')->nullable();
            $table->decimal('base_price', 10, 2)->nullable();
            $table->json('charges')->nullable();
            $table->json('discounts')->nullable();
            $table->decimal('gst', 10, 2)->nullable();
            $table->decimal('pst', 10, 2)->nullable();
            $table->decimal('hst', 10, 2)->nullable();
            $table->decimal('qst', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
