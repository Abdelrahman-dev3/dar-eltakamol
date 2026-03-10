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
        Schema::create('shares_poes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sale_number');
            $table->float('count');
            $table->float('amount_per_share');
            $table->boolean('accept')->default(false);
            $table->datetime('insert_date')->nullable();
            $table->integer('po_status')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('contributors');
            $table->foreign('sale_number')->references('id')->on('sell_shares');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares_poes');
    }
};