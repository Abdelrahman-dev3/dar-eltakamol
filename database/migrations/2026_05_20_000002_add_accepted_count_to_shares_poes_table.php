<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shares_poes', function (Blueprint $table): void {
            if (!Schema::hasColumn('shares_poes', 'accepted_count')) {
                $table->decimal('accepted_count', 15, 2)->default(0)->after('count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shares_poes', function (Blueprint $table): void {
            if (Schema::hasColumn('shares_poes', 'accepted_count')) {
                $table->dropColumn('accepted_count');
            }
        });
    }
};
