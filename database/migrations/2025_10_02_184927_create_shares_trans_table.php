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
        Schema::create('shares_trans', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->string('notes', 200)->nullable();
            $table->integer('trans_type');
            $table->boolean('posted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares_trans');
    }
};