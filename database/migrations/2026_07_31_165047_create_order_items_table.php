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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('item_image')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('length_inch', 8, 2)->nullable();
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('breadth_inch', 8, 2)->nullable();
            $table->decimal('breadth_cm', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Admin might assign price per item later
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
