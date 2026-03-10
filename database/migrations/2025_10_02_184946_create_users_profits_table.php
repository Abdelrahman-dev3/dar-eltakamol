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
        Schema::create('users_profits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profits_id');
            $table->float('amount')->nullable();
            $table->unsignedBigInteger('contributor_id');
            $table->timestamps();
            
            $table->foreign('profits_id')->references('id')->on('profits');
            $table->foreign('contributor_id')->references('id')->on('contributors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_profits');
    }
};