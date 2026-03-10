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
        Schema::create('contributor_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contributor_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('file_type', 50)->default('document'); // 'image' or 'document'
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('mime_type', 100)->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('contributor_id')->references('id')->on('contributors')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributor_documents');
    }
};
