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
        foreach (['circulars', 'documents', 'regulations'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                if (!Schema::hasColumn($tableName, 'audience_scope')) {
                    $table->string('audience_scope', 50)->nullable()->after('meeting_id');
                }

                if (!Schema::hasColumn($tableName, 'audience_committee')) {
                    $table->string('audience_committee')->nullable()->after('audience_scope');
                }

                if (!Schema::hasColumn($tableName, 'audience_category_id')) {
                    $table->foreignId('audience_category_id')->nullable()->after('audience_committee')->constrained('categories')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['circulars', 'documents', 'regulations'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                if (Schema::hasColumn($tableName, 'audience_category_id')) {
                    $table->dropConstrainedForeignId('audience_category_id');
                }

                if (Schema::hasColumn($tableName, 'audience_committee')) {
                    $table->dropColumn('audience_committee');
                }

                if (Schema::hasColumn($tableName, 'audience_scope')) {
                    $table->dropColumn('audience_scope');
                }
            });
        }
    }
};
