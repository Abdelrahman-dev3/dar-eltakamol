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
        Schema::create('app_group_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->boolean('group_permission');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();
            
            $table->foreign('role_id')->references('id')->on('app_roles');
            $table->foreign('group_id')->references('id')->on('app_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_group_roles');
    }
};