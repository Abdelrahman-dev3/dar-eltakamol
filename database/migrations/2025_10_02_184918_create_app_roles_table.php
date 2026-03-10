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
        Schema::create('app_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('name_en', 100)->nullable();
            $table->string('controller_name', 30)->nullable();
            $table->string('action_name', 30)->nullable();
            $table->unsignedBigInteger('main_id')->nullable();
            $table->timestamps();
            
            $table->foreign('main_id')->references('id')->on('main_menus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_roles');
    }
};