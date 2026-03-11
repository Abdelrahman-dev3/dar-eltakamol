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
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('id_number', 10)->nullable();
            $table->string('phone_num', 15)->nullable();
            $table->string('temp_password', 10)->nullable();
            $table->string('user_id', 128)->nullable();
            $table->string('iban', 24)->nullable();
            $table->string('bank_name', 15)->nullable();
            $table->string('position', 100)->nullable();
            $table->float('share_count_cr')->nullable();
            $table->boolean('is_board_member')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};