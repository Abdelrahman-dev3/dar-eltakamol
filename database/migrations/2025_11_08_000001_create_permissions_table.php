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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الصلاحية
            $table->string('slug')->unique(); // معرف فريد
            $table->string('description')->nullable(); // وصف الصلاحية
            $table->string('module')->nullable(); // الوحدة (contributors, meetings, etc)
            $table->timestamps();
        });

        Schema::create('category_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['category_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_permission');
        Schema::dropIfExists('permissions');
    }
};


