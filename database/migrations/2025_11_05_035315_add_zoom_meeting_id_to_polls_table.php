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
        Schema::table('polls', function (Blueprint $table) {
            $table->unsignedBigInteger('zoom_meeting_id')->nullable()->after('created_by');
            $table->foreign('zoom_meeting_id')->references('id')->on('zoom_meetings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropForeign(['zoom_meeting_id']);
            $table->dropColumn('zoom_meeting_id');
        });
    }
};
