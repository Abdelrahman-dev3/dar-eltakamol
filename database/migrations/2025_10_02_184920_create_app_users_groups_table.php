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
        Schema::create('app_users_groups', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 128)->nullable();
            $table->unsignedBigInteger('group_id');
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('app_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_users_groups');
    }
};