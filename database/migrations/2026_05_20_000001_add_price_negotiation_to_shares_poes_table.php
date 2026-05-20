<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shares_poes', function (Blueprint $table): void {
            if (!Schema::hasColumn('shares_poes', 'price_negotiation_requested_at')) {
                $table->timestamp('price_negotiation_requested_at')->nullable()->after('defaulted_at');
            }

            if (!Schema::hasColumn('shares_poes', 'price_negotiation_message')) {
                $table->text('price_negotiation_message')->nullable()->after('price_negotiation_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shares_poes', function (Blueprint $table): void {
            if (Schema::hasColumn('shares_poes', 'price_negotiation_message')) {
                $table->dropColumn('price_negotiation_message');
            }

            if (Schema::hasColumn('shares_poes', 'price_negotiation_requested_at')) {
                $table->dropColumn('price_negotiation_requested_at');
            }
        });
    }
};
