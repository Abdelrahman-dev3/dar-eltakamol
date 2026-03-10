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
        Schema::create('sell_shares', function (Blueprint $table) {
            $table->id();
            $table->float('count');
            $table->float('amount_per_share');
            $table->datetime('end_date')->nullable();
            $table->string('notes', 100)->nullable();
            $table->datetime('insert_date')->nullable();
            $table->integer('ad_status');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('contributors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_shares');
    }
};