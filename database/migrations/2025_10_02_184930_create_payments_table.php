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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->float('amount');
            $table->unsignedBigInteger('shares_po_number');
            $table->string('bank_info', 100)->nullable();
            $table->boolean('confirmed')->default(false);
            $table->string('transfer_document', 100)->nullable();
            $table->timestamps();
            
            $table->foreign('shares_po_number')->references('id')->on('shares_poes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};