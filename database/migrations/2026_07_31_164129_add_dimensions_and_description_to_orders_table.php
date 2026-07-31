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
            $table->decimal('length_inch', 8, 2)->nullable()->after('quantity');
            $table->decimal('length_cm', 8, 2)->nullable()->after('length_inch');
            $table->decimal('breadth_inch', 8, 2)->nullable()->after('length_cm');
            $table->decimal('breadth_cm', 8, 2)->nullable()->after('breadth_inch');
            $table->text('description')->nullable()->after('breadth_cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['length_inch', 'length_cm', 'breadth_inch', 'breadth_cm', 'description']);
        });
    }
};
