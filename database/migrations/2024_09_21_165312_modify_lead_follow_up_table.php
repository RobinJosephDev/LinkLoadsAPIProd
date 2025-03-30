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
        Schema::table('lead_follow_up', function (Blueprint $table) {
            // Check if columns exist before dropping
            if (Schema::hasColumn('lead_follow_up', 'product_name')) {
                $table->dropColumn('product_name');
            }
            if (Schema::hasColumn('lead_follow_up', 'quantity')) {
                $table->dropColumn('quantity');
            }

            // Add the new column if it doesn't exist
            if (!Schema::hasColumn('lead_follow_up', 'products')) {
                $table->longText('products')->nullable(); // Add new products field
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_follow_up', function (Blueprint $table) {
            // Add back columns if they don't exist
            if (!Schema::hasColumn('lead_follow_up', 'product_name')) {
                $table->string('product_name'); // Add back product_name
            }
            if (!Schema::hasColumn('lead_follow_up', 'quantity')) {
                $table->integer('quantity'); // Add back quantity
            }

            // Drop the products column if it exists
            if (Schema::hasColumn('lead_follow_up', 'products')) {
                $table->dropColumn('products'); // Drop products field
            }
        });
    }
};
