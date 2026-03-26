<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('poll_options', 'poll_question_id')) {
            DB::statement('ALTER TABLE poll_options MODIFY poll_question_id BIGINT UNSIGNED NULL');
        }

        if (! Schema::hasColumn('poll_answers', 'poll_question_id')) {
            Schema::table('poll_answers', function (Blueprint $table) {
                $table->unsignedBigInteger('poll_question_id')->nullable()->after('poll_option_id');
                $table->index('poll_question_id', 'poll_answers_poll_question_id_index');
            });

            if (Schema::hasTable('poll_questions')) {
                DB::statement('ALTER TABLE poll_answers ADD CONSTRAINT poll_answers_poll_question_id_foreign FOREIGN KEY (poll_question_id) REFERENCES poll_questions(id) ON DELETE CASCADE');
            }
        } else {
            DB::statement('ALTER TABLE poll_answers MODIFY poll_question_id BIGINT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('poll_options', 'poll_question_id')) {
            DB::statement('ALTER TABLE poll_options MODIFY poll_question_id BIGINT UNSIGNED NOT NULL');
        }

        if (Schema::hasColumn('poll_answers', 'poll_question_id')) {
            try {
                DB::statement('ALTER TABLE poll_answers DROP FOREIGN KEY poll_answers_poll_question_id_foreign');
            } catch (\Throwable $exception) {
                // Ignore if the foreign key does not exist.
            }

            try {
                Schema::table('poll_answers', function (Blueprint $table) {
                    $table->dropIndex('poll_answers_poll_question_id_index');
                });
            } catch (\Throwable $exception) {
                // Ignore if the index does not exist.
            }

            Schema::table('poll_answers', function (Blueprint $table) {
                $table->dropColumn('poll_question_id');
            });
        }
    }
};
