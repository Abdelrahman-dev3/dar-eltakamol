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
        // Add type and meeting_id to polls table
        Schema::table('polls', function (Blueprint $table) {
            $table->enum('poll_type', ['general', 'meeting'])->default('general')->after('question');
            $table->foreignId('meeting_id')->nullable()->after('poll_type')->constrained('meetings')->onDelete('cascade');
            $table->string('title', 255)->after('id'); // عنوان الاستطلاع
            $table->text('description')->nullable()->after('title'); // وصف الاستطلاع
        });

        // Create poll_questions table
        Schema::create('poll_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->text('question_text'); // نص السؤال
            $table->enum('question_type', ['single', 'multiple'])->default('single'); // نوع السؤال: اختيار واحد أو متعدد
            $table->integer('order')->default(0); // ترتيب السؤال
            $table->boolean('is_required')->default(true); // هل السؤال إجباري
            $table->timestamps();
        });

        // Update poll_options to reference poll_questions instead of poll
        Schema::table('poll_options', function (Blueprint $table) {
            // First, drop foreign key if exists
            if (Schema::hasColumn('poll_options', 'poll_id')) {
                try {
                    $table->dropForeign(['poll_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            }
            // Add nullable poll_question_id first
            $table->foreignId('poll_question_id')->nullable()->after('poll_id')->constrained()->onDelete('cascade');
        });

        // Update poll_answers to reference poll_questions
        Schema::table('poll_answers', function (Blueprint $table) {
            $table->foreignId('poll_question_id')->nullable()->after('poll_option_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poll_answers', function (Blueprint $table) {
            $table->dropForeign(['poll_question_id']);
            $table->dropColumn('poll_question_id');
        });

        Schema::table('poll_options', function (Blueprint $table) {
            $table->dropForeign(['poll_question_id']);
            $table->dropColumn('poll_question_id');
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
        });

        Schema::dropIfExists('poll_questions');

        Schema::table('polls', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->dropColumn(['poll_type', 'meeting_id', 'title', 'description']);
        });
    }
};

