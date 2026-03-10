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
        Schema::table('users_profits', function (Blueprint $table) {
            $table->datetime('payment_date')->nullable();
            $table->boolean('is_paid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_profits', function (Blueprint $table) {
            $table->dropColumn(['payment_date', 'is_paid']);
        });
    }
};
