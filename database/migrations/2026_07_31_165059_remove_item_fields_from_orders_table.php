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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'item_image',
                'quantity',
                'length_inch',
                'length_cm',
                'breadth_inch',
                'breadth_cm',
                'description'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('item_image')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('length_inch', 8, 2)->nullable();
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('breadth_inch', 8, 2)->nullable();
            $table->decimal('breadth_cm', 8, 2)->nullable();
            $table->text('description')->nullable();
        });
    }
};
