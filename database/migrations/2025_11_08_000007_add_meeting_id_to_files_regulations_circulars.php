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
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('meeting_id')->nullable()->after('name')->constrained('meetings')->onDelete('set null');
        });

        Schema::table('regulations', function (Blueprint $table) {
            $table->foreignId('meeting_id')->nullable()->after('name')->constrained('meetings')->onDelete('set null');
        });

        Schema::table('circulars', function (Blueprint $table) {
            $table->foreignId('meeting_id')->nullable()->after('name')->constrained('meetings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->dropColumn('meeting_id');
        });

        Schema::table('regulations', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->dropColumn('meeting_id');
        });

        Schema::table('circulars', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->dropColumn('meeting_id');
        });
    }
};


