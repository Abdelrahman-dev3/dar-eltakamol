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
        Schema::create('share_trans_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contributor_id');
            $table->string('line_notes', 100)->nullable();
            $table->float('count_debit')->nullable();
            $table->float('count_credit')->nullable();
            $table->float('amount_per_share')->nullable();
            $table->unsignedBigInteger('trans_id');
            $table->boolean('posted')->default(false);
            $table->timestamps();
            
            $table->foreign('contributor_id')->references('id')->on('contributors');
            $table->foreign('trans_id')->references('id')->on('shares_trans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_trans_lines');
    }
};