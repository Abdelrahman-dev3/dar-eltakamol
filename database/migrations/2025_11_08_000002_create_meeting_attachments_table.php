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
        Schema::create('meeting_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->string('file_name'); // اسم الملف الأصلي
            $table->string('file_path'); // مسار الملف المخزن
            $table->string('file_type', 50); // نوع الملف (pdf, doc, image, etc)
            $table->unsignedBigInteger('file_size'); // حجم الملف بالبايت
            $table->string('mime_type'); // نوع MIME
            $table->text('description')->nullable(); // وصف المرفق
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_attachments');
    }
};


